<?php

declare(strict_types=1);

namespace Tests\Unit\Salary;

use InvalidArgumentException;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Application\SetDepartmentBonusHandler;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Payroll\Salary\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Shared\Event\InMemoryDomainEventBus;
use Payroll\Shared\UUID\DepartmentId;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private SetDepartmentBonusHandler $setDepartmentBonusHandler;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $repository = new InMemoryDepartmentRepository();
        $this->setDepartmentBonusHandler = new SetDepartmentBonusHandler($this->bus, $repository);
    }

    /**
     * @dataProvider bonusTypes
     */
    public function testSetDepartmentBonus(BonusType $bonusType): void
    {
        // Given
        $departmentId = DepartmentId::newOne();
        $bonusType = $bonusType->name;
        $bonusFactor = 10;

        // When
        $command = new SetDepartmentBonus($departmentId, $bonusType, $bonusFactor);
        $this->setDepartmentBonusHandler->__invoke($command);

        // Then
        $event = $this->bus->latestEvent();
        $expectedEvent = new DepartmentBonusChanged(
            $event->eventId(),
            $departmentId,
            $bonusType,
            $bonusFactor
        );
        self::assertEquals($expectedEvent, $event);
    }

    /**
     * @dataProvider bonusTypes
     */
    public function testFailSetDepartmentBonus(BonusType $bonusType): void
    {
        // Expected
        self::expectException(InvalidArgumentException::class);

        // Given
        $departmentId = DepartmentId::newOne();
        $bonusType = $bonusType->name;
        $bonusFactor = -1;

        // When
        $command = new SetDepartmentBonus($departmentId, $bonusType, $bonusFactor);
        $this->setDepartmentBonusHandler->__invoke($command);
    }

    /**
     * @return array{BonusType}[]
     */
    public function bonusTypes(): array
    {
        return [
            [BonusType::PERCENTAGE],
            [BonusType::PERMANENT],
        ];
    }
}
