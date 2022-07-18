<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use App\ReadModel\Report\Query\ListReportLines;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListReportLinesHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListReportLines $query): array
    {
        $stmt = $this->conn->prepare(<<<SQL
            SELECT employee_id, first_name, last_name, department, base_salary, bonus, bonus_type, salary
            FROM report_line_read_model WHERE report_id = :reportId
        SQL);
        $stmt->bindValue(':reportId', $query->reportId->toString());

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
