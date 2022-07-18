<?php

declare(strict_types=1);

namespace App\ReadModel\Department;

use App\ReadModel\Department\Query\ListDepartmentsChoices;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ListDepartmentChoicesHandler
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ListDepartmentsChoices $query): array
    {
        $result = $this->conn->executeQuery(<<<SQL
            SELECT name, id FROM department_read_model
        SQL)->fetchAllAssociative();

        return array_column($result, 'id', 'name');
    }
}
