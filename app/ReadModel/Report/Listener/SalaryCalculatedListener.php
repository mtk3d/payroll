<?php

declare(strict_types=1);

namespace App\ReadModel\Report\Listener;

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
            INSERT INTO report_line_read_model (id, report_id, employee_id, first_name, last_name, department, base_salary, raw_base_salary, bonus, raw_bonus, bonus_type, salary, raw_salary)
                VALUES (:id, :reportId, :employeeId, NULL, NULL, NULL, :baseSalary, :rawBaseSalary, :bonus, :rawBonus, NULL, :salary, :rawSalary)
                ON DUPLICATE KEY UPDATE base_salary=:baseSalary, raw_base_salary=:rawBaseSalary, bonus=:bonus, raw_bonus=:rawBonus, salary=:salary, raw_salary=:rawSalary
        SQL);

        $stmt->bindValue(':id', UUID::random()->toString());
        $stmt->bindValue(':reportId', $event->reportId->toString());
        $stmt->bindValue(':employeeId', $event->employeeId->toString());
        $stmt->bindValue(':baseSalary', $this->moneyFormat($event->baseSalary));
        $stmt->bindValue(':rawBaseSalary', (int) $event->baseSalary->getAmount());
        $stmt->bindValue(':bonus', $this->moneyFormat($event->bonus));
        $stmt->bindValue(':rawBonus', (int) $event->bonus->getAmount());
        $stmt->bindValue(':salary', $this->moneyFormat($event->baseSalary->add($event->bonus)));
        $stmt->bindValue(':rawSalary', (int) $event->baseSalary->add($event->bonus)->getAmount());

        $stmt->executeQuery();
    }
}
