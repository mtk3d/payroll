<?php

declare(strict_types=1);

namespace Test\Integration;

use DateTimeImmutable;
use Payroll\Report\Domain\Exception\ReportNotFoundException;
use Payroll\Report\Domain\Report;
use Payroll\Report\Infrastructure\Repository\DoctrineReportRepository;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class ReportDoctrineTest extends KernelTestCase
{
    use InitDatabaseTrait;

    private ?DoctrineReportRepository $reportRepository;

    protected function setUp(): void
    {
        $kernel = $this->bootKernel();
        $this->initDatabase($kernel);
        $container = $kernel->getContainer();
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
