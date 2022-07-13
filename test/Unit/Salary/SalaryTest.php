<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use DateTimeImmutable;
use InvalidArgumentException;
use Money\Money;
use Payroll\Salary\Application\CalculateSalariesHandler;
use Payroll\Salary\Application\Command\CalculateSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\Bonus\BonusType;
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
        $this->now = new DateTimeImmutable("2005-03-14");
        $clock = self::createMock(Clock::class);
        $clock->method('now')->willReturn($this->now);
        $this->calculatorFactory = new BonusCalculatorFactory($clock);
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryEmployeeRepository();
    }

    /**
     * @dataProvider salariesCalculations
     */
    public function testCalculateSalaries(
        string $timeModifier,
        int $baseSalary,
        BonusType $bonusType,
        int $bonusValue,
        int $salaryResult
    ): void {
        // Setup
        $handler = new CalculateSalariesHandler($this->bus, $this->repository, $this->calculatorFactory);

        // Given
        $department = aDepartment($bonusType, $bonusValue);
        $employee = aEmployee($this->now->modify($timeModifier), $baseSalary, $department);
        $this->repository->save($employee);

        // When
        $reportId = ReportId::newOne();
        $handler->handle(new CalculateSalaries($reportId));

        // Then
        $dispatchedEvent = $this->bus->latestEvent();
        $expected = new SalaryCalculated(
            $dispatchedEvent->eventId(),
            $employee->employeeId,
            $reportId,
            Money::USD($salaryResult)
        );
        self::assertEquals($expected, $dispatchedEvent);
    }

    /**
     * @return array{string, int, BonusType, int, int}[]
     * {"time modifier", "base salary", "bonus type", "bonus value", "result salary"}
     */
    public function salariesCalculations(): array
    {
        return [
            ['-1 year', 110000, BonusType::PERCENTAGE, 1000, 121000],
            ['-1 year', 100000, BonusType::PERCENTAGE, 11000, 210000],
            ['-10 years', 100000, BonusType::PERMANENT, 10000, 200000],
            ['-364 days', 200000, BonusType::PERMANENT, 10000, 200000],
            ['-365 days', 200000, BonusType::PERMANENT, 10000, 210000],
        ];
    }

    /**
     * @dataProvider bonusTypes
     */
    public function testFailBonusCreation(BonusType $bonusType): void
    {
        self::expectException(InvalidArgumentException::class);
        $department = aDepartment($bonusType, -1);
        $this->calculatorFactory->create($department->bonusRule);
    }

    public function bonusTypes(): array
    {
        return [
            [BonusType::PERCENTAGE],
            [BonusType::PERMANENT],
        ];
    }
}
