<?php

declare(strict_types=1);

namespace App\ReadModel\Report;

use App\ReadModel\Shared\MoneyFormatter;
use Doctrine\DBAL\Connection;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Shared\UUID\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SalaryCalculatedListener
{
    use MoneyFormatter;

    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(SalaryCalculated $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO report_line_read_model (id, report_id, employee_id, first_name, last_name, department, base_salary, bonus, bonus_type, salary)
                VALUES (:id, :reportId, :employeeId, NULL, NULL, NULL, :baseSalary, :bonus, NULL, :salary)
                ON DUPLICATE KEY UPDATE base_salary=:baseSalary, bonus=:bonus, salary=:salary
        SQL);

        $stmt->bindValue(':id', UUID::random()->toString());
        $stmt->bindValue(':reportId', $event->reportId->toString());
        $stmt->bindValue(':employeeId', $event->employeeId->toString());
        $stmt->bindValue(':baseSalary', $this->moneyFormat($event->baseSalary));
        $stmt->bindValue(':bonus', $this->moneyFormat($event->bonus));
        $stmt->bindValue(':salary', $this->moneyFormat($event->baseSalary->add($event->bonus)));

        $stmt->executeQuery();
    }
}
