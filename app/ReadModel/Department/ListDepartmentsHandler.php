<?php

declare(strict_types=1);

namespace App\ReadModel\Department;

use App\ReadModel\Department\Query\ListDepartments;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListDepartmentsHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListDepartments $query): array
    {
        return $this->conn->executeQuery(<<<SQL
            SELECT * FROM department_read_model
        SQL)->fetchAllAssociative();
    }
}
