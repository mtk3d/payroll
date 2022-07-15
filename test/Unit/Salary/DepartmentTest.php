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
    private InMemoryDepartmentRepository $repository;
    private SetDepartmentBonusHandler $setDepartmentBonusHandler;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryDepartmentRepository();
        $this->setDepartmentBonusHandler = new SetDepartmentBonusHandler($this->bus, $this->repository);
    }

    public function testSetDepartmentBonus(): void
    {
        //Given
        $departmentId = DepartmentId::newOne();
        $bonusType = 'PERCENTAGE';
        $bonusValue = 10;

        // When
        $command = new SetDepartmentBonus($departmentId, $bonusType, $bonusValue);
        $this->setDepartmentBonusHandler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expectedEvent = new DepartmentBonusChanged(
            $event->eventId(),
            $departmentId,
            $bonusType,
            $bonusValue
        );
        self::assertEquals($expectedEvent, $event);
    }
}
