<?php

declare(strict_types=1);

namespace App\ReadModel\Employee\Listener;

use Doctrine\DBAL\Connection;
use Payroll\Employment\Domain\EmployeeRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmployeeRegisteredListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(EmployeeRegistered $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO employee_read_model (id, first_name, last_name, employment_date, base_salary, department_id) VALUES(:id, :firstName, :lastName, NULL, NULL, :departmentId)
            ON DUPLICATE KEY UPDATE first_name=:firstName, last_name=:lastName
        SQL);

        $stmt->bindValue(':id', $event->employeeId->toString());
        $stmt->bindValue(':firstName', $event->firstname);
        $stmt->bindValue(':lastName', $event->lastname);
        $stmt->bindValue(':departmentId', $event->departmentId->toString());

        $stmt->executeQuery();
    }
}
