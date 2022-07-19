<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\DataFixtures\AppFixture;
use App\ReadModel\Report\Query\GetReport;
use App\ReadModel\Report\Query\ListReportLines;
use App\ReadModel\Shared\FilterBy;
use Doctrine\Persistence\ObjectManager;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Shared\CQRS\MessengerCommandBus;
use Payroll\Shared\CQRS\MessengerQueryBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateReportTest extends KernelTestCase
{
    private ?MessengerCommandBus $commandBus;
    private ?QueryBus $queryBus;
    private ?ObjectManager $manager;
    private ?AppFixture $appFixture;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(MessengerCommandBus::class);
        $this->queryBus = $container->get(MessengerQueryBus::class);
        $this->manager = self::createMock(ObjectManager::class);
        $this->appFixture = $container->get(AppFixture::class);
    }

    public function testGenerateReport(): void
    {
        $this->appFixture->load($this->manager);

        $reportId = ReportId::newOne();
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        $report = $this->queryBus->query(new GetReport($reportId));
        $reportLines = $this->queryBus->query(new ListReportLines($reportId));

        $expectedReport = [
            'id' => $reportId->toString(),
            'date' => '2005-03-14 00:00:00',
            'status' => 'GENERATED',
        ];

        self::assertEquals($expectedReport, $report);
        self::assertContains(
            [
                'first_name' => 'Adam',
                'last_name' => 'Kowalski',
                'department' => 'HR',
                'base_salary' => '$1,000.00',
                'bonus' => '$1,000.00',
                'bonus_type' => 'PERMANENT',
                'salary' => '$2,000.00',
            ],
            $reportLines
        );
        self::assertContains(
            [
                'first_name' => 'Ania',
                'last_name' => 'Nowak',
                'department' => 'Customer Service',
                'base_salary' => '$1,100.00',
                'bonus' => '$110.00',
                'bonus_type' => 'PERCENTAGE',
                'salary' => '$1,210.00',
            ],
            $reportLines
        );
    }

    public function testFilterReport(): void
    {
        $reportId = ReportId::newOne();
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        $query = new ListReportLines($reportId, null, [new FilterBy('first_name', 'Ania')]);
        $reportLines = $this->queryBus->query($query);

        self::assertNotContains(
            [
                'first_name' => 'Adam',
                'last_name' => 'Kowalski',
                'department' => 'HR',
                'base_salary' => '$1,000.00',
                'bonus' => '$1,000.00',
                'bonus_type' => 'PERMANENT',
                'salary' => '$2,000.00',
            ],
            $reportLines
        );
        self::assertContains(
            [
                'first_name' => 'Ania',
                'last_name' => 'Nowak',
                'department' => 'Customer Service',
                'base_salary' => '$1,100.00',
                'bonus' => '$110.00',
                'bonus_type' => 'PERCENTAGE',
                'salary' => '$1,210.00',
            ],
            $reportLines
        );
    }
}
