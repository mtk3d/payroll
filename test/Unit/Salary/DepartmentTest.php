<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Application\Command\UpdateDepartmentBonus;
use Payroll\Salary\Application\SetDepartmentBonusHandler;
use Payroll\Salary\Application\UpdateDepartmentBonusHandler;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Salary\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\InMemoryDomainEventBus;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryDepartmentRepository $repository;
    private SetDepartmentBonusHandler $setDepartmentBonusHandler;
    private UpdateDepartmentBonusHandler $updateDepartmentBonusHandler;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryDepartmentRepository();
        $this->setDepartmentBonusHandler = new SetDepartmentBonusHandler($this->bus, $this->repository);
        $this->updateDepartmentBonusHandler = new UpdateDepartmentBonusHandler($this->bus, $this->repository);
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

    public function testUpdateDepartmentBonus(): void
    {
        //Given
        $department = aDepartment();
        $this->repository->save($department);

        // When
        $bonusType = 'PERMANENT';
        $bonusValue = 234;
        $command = new UpdateDepartmentBonus($department->id, $bonusType, $bonusValue);
        $this->updateDepartmentBonusHandler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expectedEvent = new DepartmentBonusChanged(
            $event->eventId(),
            $department->id,
            $bonusType,
            $bonusValue
        );
        self::assertEquals($expectedEvent, $event);
    }

    public function testFailUpdatingDepartmentBonus(): void
    {
        // Expected
        self::expectException(DepartmentNotFoundException::class);

        //Given
        $bonusType = 'PERMANENT';
        $bonusValue = 234;

        // When
        $command = new UpdateDepartmentBonus(DepartmentId::newOne(), $bonusType, $bonusValue);
        $this->updateDepartmentBonusHandler->handle($command);
    }
}
