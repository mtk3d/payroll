<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Application\SetDepartmentBonusHandler;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Payroll\Salary\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\InMemoryDomainEventBus;
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

    public function testSetDepartmentBonus(): void
    {
        //Given
        $departmentId = DepartmentId::newOne();
        $bonusType = 'PERCENTAGE';
        $bonusFactor = 10;

        // When
        $command = new SetDepartmentBonus($departmentId, $bonusType, $bonusFactor);
        $this->setDepartmentBonusHandler->handle($command);

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
}
