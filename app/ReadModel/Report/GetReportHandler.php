<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use App\ReadModel\Report\Query\GetReport;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetReportHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(GetReport $query): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM report_read_model WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $query->reportId->toString());

        $result = $stmt->executeQuery()->fetchAllAssociative();
        reset($result);

        return current($result);
    }
}
