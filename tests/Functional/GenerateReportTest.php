<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\ReadModel\Report\Query\GetReport;
use App\ReadModel\Report\Query\ListReportLines;
use App\ReadModel\Report\Query\ListReports;
use App\ReadModel\Shared\FilterBy;
use App\ReadModel\Shared\SortBy;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\CQRS\MessengerCommandBus;
use Payroll\Shared\CQRS\MessengerQueryBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\FakeClock;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateReportTest extends KernelTestCase
{
    public static ReportId $reportId;
    private ?QueryBus $queryBus;
    private ?CommandBus $commandBus;
    private FakeClock $clock;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->queryBus = $container->get(MessengerQueryBus::class);
        $this->commandBus = $container->get(MessengerCommandBus::class);
        $this->clock = new FakeClock();
    }

    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $commandBus = $container->get(MessengerCommandBus::class);

        self::$reportId = ReportId::newOne();
        $commandBus->dispatch(new GenerateSalaryReport(self::$reportId));
    }

    public function testGenerateReport(): void
    {
        $reportId = ReportId::newOne();
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        $report = $this->queryBus->query(new GetReport($reportId));
        $reportLines = $this->queryBus->query(new ListReportLines($reportId));

        $expectedReport = [
            'id' => $reportId->toString(),
            'date' => $this->clock->now()->format('Y-m-d H:i:s'),
            'status' => 'GENERATED',
        ];

        self::assertEquals($expectedReport, $report);
        self::assertContains($this->adamData(), $reportLines);
        self::assertContains($this->aniaData(), $reportLines);
    }

    public function testReportList(): void
    {
        $reports = $this->queryBus->query(new ListReports());

        $expectedReport = [
            'id' => self::$reportId->toString(),
            'date' => $this->clock->now()->format('Y-m-d H:i:s'),
            'status' => 'GENERATED',
        ];

        self::assertContains($expectedReport, $reports);
    }

    public function testFilterReport(): void
    {
        $filters = [
            new FilterBy('first_name', 'Ania'),
            new FilterBy('non_existing_column', 'XYZ'),
        ];

        $query = new ListReportLines(self::$reportId, null, $filters);
        $reportLines = $this->queryBus->query($query);

        self::assertNotContains($this->adamData(), $reportLines);
        self::assertContains($this->aniaData(), $reportLines);
    }

    public function testSortDescReport(): void
    {
        $query = new ListReportLines(self::$reportId, new SortBy('first_name', false));
        $reportLines = $this->queryBus->query($query);

        $adamIndex = array_search($this->adamData(), $reportLines);
        $aniaIndex = array_search($this->aniaData(), $reportLines);

        self::assertLessThan($adamIndex, $aniaIndex);

        $query = new ListReportLines(self::$reportId, new SortBy('first_name', true));
        $reportLines = $this->queryBus->query($query);

        $adamIndex = array_search($this->adamData(), $reportLines);
        $aniaIndex = array_search($this->aniaData(), $reportLines);

        self::assertGreaterThan($adamIndex, $aniaIndex);
    }

    private function adamData(): array
    {
        return [
            'first_name' => 'Adam',
            'last_name' => 'Kowalski',
            'department' => 'HR',
            'base_salary' => '$1,000.00',
            'bonus' => '$1,000.00',
            'bonus_type' => 'PERMANENT',
            'salary' => '$2,000.00',
        ];
    }

    private function aniaData(): array
    {
        return [
            'first_name' => 'Ania',
            'last_name' => 'Nowak',
            'department' => 'Customer Service',
            'base_salary' => '$1,100.00',
            'bonus' => '$110.00',
            'bonus_type' => 'PERCENTAGE',
            'salary' => '$1,210.00',
        ];
    }
}
