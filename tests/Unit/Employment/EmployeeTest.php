<?php

declare(strict_types=1);

namespace Tests\Unit\Employment;

use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Employment\Application\CreateEmployeeHandler;
use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Domain\EmployeeRegistered;
use Payroll\Employment\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Employment\Infrastructure\Repository\InMemoryEmployeeRepository;
use Payroll\Shared\InMemoryDomainEventBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryEmployeeRepository $employeeRepository;
    private InMemoryDepartmentRepository $departmentRepository;
    private CreateEmployeeHandler $handler;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->employeeRepository = new InMemoryEmployeeRepository();
        $this->departmentRepository = new InMemoryDepartmentRepository();
        $this->handler = new CreateEmployeeHandler($this->bus, $this->employeeRepository, $this->departmentRepository);
    }

    public function testDepartmentCreating(): void
    {
        // Given
        $employeeId = EmployeeId::newOne();
        $firstname = 'John';
        $lastname = 'Doe';
        $departmentId = DepartmentId::newOne();
        $departmentName = 'Developers';
        $department = new Department($departmentId, $departmentName);
        $this->departmentRepository->save($department);

        // When
        $command = new CreateEmployee($employeeId, $firstname, $lastname, $departmentId);
        $this->handler->__invoke($command);

        // Then
        $event = $this->bus->latestEvent();
        $expected = new EmployeeRegistered($event->eventId(), $employeeId, $firstname, $lastname, $departmentId);
        self::assertEquals($expected, $event);

        $expectedDepartment = new Employee($employeeId, $firstname, $lastname, $department);
        self::assertEquals($expectedDepartment, $this->employeeRepository->find($employeeId));
    }
}
