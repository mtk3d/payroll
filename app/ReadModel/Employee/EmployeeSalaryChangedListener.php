<?php

declare(strict_types=1);

namespace App\ReadModel\Employee;

use Doctrine\DBAL\Connection;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;
use Payroll\Salary\Domain\EmployeeSalaryChanged;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmployeeSalaryChangedListener
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(EmployeeSalaryChanged $event): void
    {
        $stmt = $this->conn->prepare(<<<SQL
            INSERT INTO employee_read_model (id, first_name, last_name, employment_date, base_salary, department_id) VALUES(:id, NULL, NULL, :employmentDate, :baseSalary, :departmentId)
            ON DUPLICATE KEY UPDATE employment_date=:employmentDate, base_salary=:baseSalary
        SQL);

        $stmt->bindValue(':id', $event->id->toString());
        $stmt->bindValue(':employmentDate', $event->employmentDate->format("Y-m-d"));
        $stmt->bindValue(':baseSalary', $this->moneyFormat($event->baseSalary));
        $stmt->bindValue(':departmentId', $event->departmentId->toString());

        $stmt->executeQuery();
    }

    private function moneyFormat(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
