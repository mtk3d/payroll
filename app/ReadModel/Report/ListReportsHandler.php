<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use App\ReadModel\Report\Query\ListReports;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListReportsHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListReports $query): array
    {
        return $this->conn
            ->executeQuery('SELECT * FROM report_read_model')
            ->fetchAllAssociative();
    }
}
