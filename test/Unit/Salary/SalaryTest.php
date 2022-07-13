<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use DateTimeImmutable;
use Money\Money;
use Payroll\Salary\Application\CalculateSalariesHandler;
use Payroll\Salary\Application\Command\CalculateSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Salary\Infrastructure\Repository\InMemoryEmployeeRepository;
use Payroll\Shared\Clock;
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
        $this->calculatorFactory = new BonusCalculatorFactory($this->clock);
        $this->bus = new InMemoryDomainEventBus();
    }

    public function testPercentageBonus(): void
    {
        $employmentDate = $this->now->modify("-5 years");
        $baseSalary = Money::USD(110000);
        $department = aDepartment(BonusType::PERCENTAGE, 1000);
        $employee = aEmployee($employmentDate, $baseSalary, $department);
        $calculator = $this->calculatorFactory->create($department->bonusRule);

        self::assertEquals(Money::USD(121000), $calculator->calculate($employee->bonusCriteria()));
    }

    public function testPermanentBonus(): void
    {
        $employmentDate = $this->now->modify("-10 years");
        $baseSalary = Money::USD(100000);
        $department = aDepartment(BonusType::PERMANENT, 10000);
        $employee = aEmployee($employmentDate, $baseSalary, $department);
        $calculator = $this->calculatorFactory->create($department->bonusRule);

        self::assertEquals(Money::USD(200000), $calculator->calculate($employee->bonusCriteria()));
    }

    public function testCalculateSalaries(): void
    {
        $employmentDate = $this->now->modify("-10 years");
        $baseSalary = Money::USD(100000);
        $department = aDepartment(BonusType::PERMANENT, 10000);
        $employee = aEmployee($employmentDate, $baseSalary, $department);

        $repository = new InMemoryEmployeeRepository([$employee]);

        $handler = new CalculateSalariesHandler($this->bus, $repository, $this->calculatorFactory);
        $handler->handle(new CalculateSalaries());

        self::assertEquals(new SalaryCalculated($employee->employeeId, Money::USD(200000)), $this->bus->latestEvent());
    }
}
