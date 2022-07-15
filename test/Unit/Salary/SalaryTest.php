<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use DateTimeImmutable;
use Money\Money;
use Payroll\Salary\Application\CalculateReportSalariesHandler;
use Payroll\Salary\Application\Command\CalculateReportSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\ReportSalariesCalculated;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Salary\Infrastructure\Repository\InMemoryEmployeeRepository;
use Payroll\Shared\Clock;
use Payroll\Shared\InMemoryDomainEventBus;
use Payroll\Shared\ReportId;
use PHPUnit\Framework\TestCase;

class SalaryTest extends TestCase
{
    private DateTimeImmutable $now;
    private BonusCalculatorFactory $calculatorFactory;
    private InMemoryDomainEventBus $bus;

    public function setUp(): void
    {
        $this->now = new DateTimeImmutable("2005-03-14 12:00");
        $clock = self::createMock(Clock::class);
        $clock->method('now')->willReturn($this->now);
        $this->calculatorFactory = new BonusCalculatorFactory($clock);
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryEmployeeRepository();
    }

    /**
     * @dataProvider salariesCalculations
     */
    public function testCalculateReportSalaries(
        string    $timeModifier,
        int       $baseSalary,
        BonusType $bonusType,
        int       $bonusFactor,
        int       $bonus
    ): void {
        // Setup
        $handler = new CalculateReportSalariesHandler($this->bus, $this->repository, $this->calculatorFactory);

        // Given
        $department = aDepartment($bonusType, $bonusFactor);
        $employee = aEmployee($this->now->modify($timeModifier), $baseSalary, $department);
        $this->repository->save($employee);

        // When
        $reportId = ReportId::newOne();
        $handler->handle(new CalculateReportSalaries($reportId));

        // Then
        $dispatched = $this->bus->firstEvent();
        $expected = new SalaryCalculated(
            $dispatched->eventId(),
            $employee->employeeId,
            $reportId,
            Money::USD($baseSalary),
            Money::USD($bonus)
        );
        self::assertEquals($expected, $dispatched);
    }

    /**
     * @return array{string, int, BonusType, int, int}[]
     * {"time modifier", "base salary", "bonus type", "bonus value", "result salary"}
     */
    public function salariesCalculations(): array
    {
        return [
            ['-1 year', 110000, BonusType::PERCENTAGE, 1000, 11000],
            ['-1 year', 100000, BonusType::PERCENTAGE, 11000, 110000],
            ['-10 years', 100000, BonusType::PERMANENT, 10000, 100000],
            ['-364 days', 200000, BonusType::PERMANENT, 10000, 0],
            ['-365 days', 200000, BonusType::PERMANENT, 10000, 10000],
        ];
    }

    public function testCalculateReportSalariesForMultipleEmployees(): void
    {
        // Setup
        $handler = new CalculateReportSalariesHandler($this->bus, $this->repository, $this->calculatorFactory);

        // Given
        $department = aDepartment();
        $firstEmployee = aEmployee(null, null, $department);
        $this->repository->save($firstEmployee);
        $secondEmployee = aEmployee(null, null, $department);
        $this->repository->save($secondEmployee);

        // When
        $reportId = ReportId::newOne();
        $handler->handle(new CalculateReportSalaries($reportId));

        // Then
        self::assertCount(3, $this->bus->events());

        $dispatched = $this->bus->latestEvent();
        $expected = new ReportSalariesCalculated(
            $dispatched->eventId(),
            $reportId
        );
        self::assertEquals($expected, $dispatched);
    }
}
