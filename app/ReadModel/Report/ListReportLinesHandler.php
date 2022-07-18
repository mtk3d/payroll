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

    public function __invoke(ListReportLines $query)
    {
        $stmt = $this->conn->prepare('SELECT * FROM report_line_read_model WHERE report_id = :reportId LIMIT 1');
        $stmt->bindValue(':reportId', $query->reportId->toString());

        return $stmt->executeQuery()->fetchOne();
    }
}
