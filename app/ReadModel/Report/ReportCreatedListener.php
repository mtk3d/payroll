<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use Doctrine\DBAL\Connection;
use Payroll\Report\Domain\ReportCreated;
use Payroll\Shared\UUID\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ReportCreatedListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ReportCreated $event): void
    {
        $this->createReport($event);
        $this->createReportLines($event);
    }

    private function createReport(ReportCreated $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO report_read_model (id, date, status) VALUES(:id, :date, :status)
        SQL);

        $stmt->bindValue(':id', $event->reportId->toString());
        $stmt->bindValue(':date', $event->date->format('Y-m-d'));
        $stmt->bindValue(':status', $event->status);

        $stmt->executeQuery();
    }

    private function createReportLines(ReportCreated $event): void
    {
        $employees = $this->conn->executeQuery(<<<SQL
            SELECT e.id, e.first_name, e.last_name, d.name AS department, d.bonus_type
            FROM employee_read_model e INNER JOIN department_read_model d ON e.department_id = d.id
        SQL)->fetchAllAssociative();

        foreach ($employees as $employee) {
            $stmt = $this->conn->prepare(<<<SQL
                INSERT INTO report_line_read_model (id, report_id, employee_id, first_name, last_name, department, base_salary, bonus, bonus_type, salary)
                VALUES (:id, :reportId, :employeeId, :firstName, :lastName, :department, NULL, NULL, :bonusType, NULL)
                ON DUPLICATE KEY UPDATE first_name=:firstName, last_name=:lastName, department=:department, bonus_type=:bonusType
            SQL);

            $stmt->bindValue(':id', UUID::random()->toString());
            $stmt->bindValue(':reportId', $event->reportId->toString());
            $stmt->bindValue(':employeeId', $employee['id']);
            $stmt->bindValue(':firstName', $employee['first_name']);
            $stmt->bindValue(':lastName', $employee['last_name']);
            $stmt->bindValue(':department', $employee['department']);
            $stmt->bindValue(':bonusType', $employee['bonus_type']);
            $stmt->executeQuery();
        }
    }
}
