<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Application\SetDepartmentBonusHandler;
use Payroll\Salary\Domain\DepartmentBonusSet;
use Payroll\Salary\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\InMemoryDomainEventBus;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryDepartmentRepository $repository;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryDepartmentRepository();
    }

    public function testSetDepartmentBonus(): void
    {
        // Setup
        $handler = new SetDepartmentBonusHandler($this->bus, $this->repository);

        //Given
        $departmentId = DepartmentId::newOne();
        $bonusType = 'percentage';
        $bonusValue = 10;

        // When
        $command = new SetDepartmentBonus($departmentId, $bonusType, $bonusValue);
        $handler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expectedEvent = new DepartmentBonusSet(
            $event->eventId(),
            $departmentId,
            $bonusType,
            $bonusValue
        );
        self::assertEquals($expectedEvent, $event);
    }
}
