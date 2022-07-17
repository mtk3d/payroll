<?php

declare(strict_types=1);

namespace Payroll\Report\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Report\Domain\Exception\ReportNotFoundException;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Shared\UUID\ReportId;

class DoctrineReportRepository implements ReportRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function find(ReportId $reportId): Report
    {
        if (!$report = $this->em->find(Report::class, $reportId)) {
            throw new ReportNotFoundException(sprintf('Department %s not found', $reportId->toString()));
        }

        return $report;
    }

    public function save(Report $report): void
    {
        $this->em->persist($report);
        $this->em->flush();
    }
}
