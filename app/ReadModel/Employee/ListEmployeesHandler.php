<?php

declare(strict_types=1);

namespace App\ReadModel\Employee;

use App\ReadModel\Employee\Query\ListEmployees;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListEmployeesHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListEmployees $query): array
    {
        return $this->conn->executeQuery(<<<SQL
            SELECT * FROM employee_read_model
        SQL)->fetchAllAssociative();
    }
}
