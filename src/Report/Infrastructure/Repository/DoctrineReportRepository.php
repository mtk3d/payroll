<?php

declare(strict_types=1);

namespace Payroll\Report\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Report\Domain\Report;
use Payroll\Report\Domain\ReportRepository;
use Payroll\Shared\UUID\ReportId;

class DoctrineReportRepository implements ReportRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function find(ReportId $reportId): Report
    {
        return $this->em->find(Report::class, $reportId->toString());
    }

    public function save(Report $report): void
    {
        $this->em->persist($report);
        $this->em->flush();
    }
}
