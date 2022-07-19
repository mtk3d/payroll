<?php

declare(strict_types=1);

namespace App\ReadModel\Department\Listener;

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
            INSERT INTO department_read_model (id, name, bonus_type, bonus_factor) VALUES(:id, NULL, :bonusType, :bonusFactor)
            ON DUPLICATE KEY UPDATE bonus_type=:bonusType, bonus_factor=:bonusFactor
        SQL);

        $stmt->bindValue(':id', $event->departmentId->toString());
        $stmt->bindValue(':bonusType', $event->bonusType);
        $stmt->bindValue(':bonusFactor', $event->bonusFactor);

        $stmt->executeQuery();
    }
}
