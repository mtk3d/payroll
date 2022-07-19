<?php

declare(strict_types=1);

namespace Tests\Integration;

use DateTimeImmutable;
use Payroll\Report\Domain\Exception\ReportNotFoundException;
use Payroll\Report\Domain\Report;
use Payroll\Report\Infrastructure\Repository\DoctrineReportRepository;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReportDoctrineTest extends KernelTestCase
{
    private ?DoctrineReportRepository $reportRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->reportRepository = $container->get(DoctrineReportRepository::class);
    }

    public function testReportPersistence(): void
    {
        $reportId = ReportId::newOne();
        $report = new Report($reportId, new DateTimeImmutable());

        $this->reportRepository->save($report);

        self::assertEquals($report, $this->reportRepository->find($reportId));
    }

    public function testReportNotFound(): void
    {
        self::expectException(ReportNotFoundException::class);
        $this->reportRepository->find(ReportId::newOne());
    }
}
