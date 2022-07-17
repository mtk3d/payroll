<?php

declare(strict_types=1);

namespace App\ReadModel\Department;

use Doctrine\DBAL\Connection;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DepartmentBonusChangedListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(DepartmentBonusChanged $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO department_read_model (id, name, bonus_type) VALUES(:id, NULL, :bonusType)
            ON DUPLICATE KEY UPDATE bonus_type=:bonusType
        SQL);

        $stmt->bindValue(':id', $event->departmentId->toString());
        $stmt->bindValue(':bonusType', $event->bonusType);

        $stmt->executeQuery();
    }
}
