<?php

declare(strict_types=1);

namespace App\ReadModel\Department;

use Doctrine\DBAL\Connection;
use Payroll\Employment\Domain\DepartmentRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DepartmentRegisteredListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(DepartmentRegistered $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO department_read_model (id, name, bonus_type) VALUES(:id, :name, NULL)
            ON DUPLICATE KEY UPDATE name=:name
        SQL);

        $stmt->bindValue(':id', $event->departmentId->toString());
        $stmt->bindValue(':name', $event->name);

        $stmt->executeQuery();
    }
}
