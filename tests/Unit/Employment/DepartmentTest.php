<?php

declare(strict_types=1);

namespace Test\Unit\Employment;

use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Application\CreateDepartmentHandler;
use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\DepartmentRegistered;
use Payroll\Employment\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Shared\InMemoryDomainEventBus;
use Payroll\Shared\UUID\DepartmentId;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryDepartmentRepository $repository;
    private CreateDepartmentHandler $handler;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->repository = new InMemoryDepartmentRepository();
        $this->handler = new CreateDepartmentHandler($this->bus, $this->repository);
    }

    public function testDepartmentCreating(): void
    {
        $departmentId = DepartmentId::newOne();
        $departmentName = 'Developers';

        $command = new CreateDepartment($departmentId, $departmentName);
        $this->handler->handle($command);

        $event = $this->bus->latestEvent();
        $expected = new DepartmentRegistered($event->eventId(), $departmentId, $departmentName);
        self::assertEquals($expected, $event);

        $expectedDepartment = new Department($departmentId, $departmentName);
        self::assertEquals($expectedDepartment, $this->repository->find($departmentId));
    }
}
