<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use DateTimeImmutable;
use Money\Money;
use Payroll\Salary\Application\CalculateSalariesHandler;
use Payroll\Salary\Application\Command\CalculateSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\Bonus\PercentageBonus;
use Payroll\Salary\Domain\Bonus\PermanentBonus;
use Payroll\Salary\Domain\BonusRule;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\Employee;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Salary\Infrastructure\Repository\InMemoryEmployeeRepository;
use Payroll\Shared\Clock;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\EmployeeId;
use Payroll\Shared\InMemoryDomainEventBus;
use PHPUnit\Framework\TestCase;

class SalaryTest extends TestCase
{
    private DateTimeImmutable $now;
    private Clock $clock;

    public function setUp(): void
    {
        $this->now = new DateTimeImmutable("2005-03-14");
        $this->clock = self::createMock(Clock::class);
        $this->clock->method('now')->willReturn($this->now);
    }

    public function testPercentageBonus(): void
    {
        $employeeId = EmployeeId::newOne();
        $departmentId = DepartmentId::newOne();
        $employmentDate = $this->now->modify("-5 years");
        $baseSalary = Money::USD(110000);
        $bonus = new PercentageBonus(1000);
        $department = new Department($departmentId, new BonusRule(BonusType::PERCENTAGE, 1000));
        $employee = new Employee($employeeId, $employmentDate, $baseSalary, $department);

        self::assertEquals(Money::USD(121000), $bonus->calculate($employee->bonusCriteria()));
    }

    public function testPermanentBonus(): void
    {
        $employeeId = EmployeeId::newOne();
        $departmentId = DepartmentId::newOne();
        $employmentDate = $this->now->modify("-10 years");
        $baseSalary = Money::USD(100000);

        $bonus = new PermanentBonus($this->clock, 10000);
        $department = new Department($departmentId, new BonusRule(BonusType::PERMANENT, 10000));
        $employee = new Employee($employeeId, $employmentDate, $baseSalary, $department);

        self::assertEquals(Money::USD(200000), $bonus->calculate($employee->bonusCriteria()));
    }

    public function testCalculateSalaries(): void
    {
        $employeeId = EmployeeId::newOne();
        $departmentId = DepartmentId::newOne();
        $employmentDate = $this->now->modify("-10 years");
        $baseSalary = Money::USD(100000);
        $department = new Department($departmentId, new BonusRule(BonusType::PERMANENT, 10000));
        $employee = new Employee($employeeId, $employmentDate, $baseSalary, $department);

        $bus = new InMemoryDomainEventBus();
        $repository = new InMemoryEmployeeRepository([$employee]);
        $calculatorFactory = new BonusCalculatorFactory($this->clock);
        $handler = new CalculateSalariesHandler($bus, $repository, $calculatorFactory);
        $handler->handle(new CalculateSalaries());

        self::assertEquals(new SalaryCalculated($employeeId, Money::USD(200000)), $bus->latestEvent());
    }
}
