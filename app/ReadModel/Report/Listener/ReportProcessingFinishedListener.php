<?php

declare(strict_types=1);

namespace App\ReadModel\Report\Listener;

use Doctrine\DBAL\Connection;
use Payroll\Report\Domain\ReportProcessingFinished;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReportProcessingFinishedListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ReportProcessingFinished $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            UPDATE report_read_model SET status=:status WHERE id=:id
        SQL);

        $stmt->bindValue(':id', $event->reportId->toString());
        $stmt->bindValue(':status', 'GENERATED');

        $stmt->executeQuery();
    }
}
