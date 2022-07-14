<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use DateTimeImmutable;
use Money\Money;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Application\CreateEmployeeSalaryHandler;
use Payroll\Salary\Domain\EmployeeSalaryCreated;
use Payroll\Salary\Infrastructure\Repository\InMemoryDepartmentRepository;
use Payroll\Salary\Infrastructure\Repository\InMemoryEmployeeRepository;
use Payroll\Shared\EmployeeId;
use Payroll\Shared\InMemoryDomainEventBus;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    private InMemoryDomainEventBus $bus;
    private InMemoryEmployeeRepository $employeeRepository;
    private InMemoryDepartmentRepository $departmentRepository;

    public function setUp(): void
    {
        $this->bus = new InMemoryDomainEventBus();
        $this->employeeRepository = new InMemoryEmployeeRepository();
        $this->departmentRepository = new InMemoryDepartmentRepository();
    }

    public function testSetDepartmentBonus(): void
    {
        // Setup
        $handler = new CreateEmployeeSalaryHandler(
            $this->bus,
            $this->employeeRepository,
            $this->departmentRepository
        );

        //Given
        $department = aDepartment();
        $this->departmentRepository->save($department);

        $employeeId = EmployeeId::newOne();
        $employmentDate = new DateTimeImmutable("2005-03-14");
        $baseSalary = Money::USD(100000);

        // When
        $command = new CreateEmployeeSalary($employeeId, $employmentDate, $baseSalary, $department->id);
        $handler->handle($command);

        // Then
        $event = $this->bus->latestEvent();
        $expectedEvent = new EmployeeSalaryCreated(
            $event->eventId(),
            $employeeId,
            $employmentDate,
            $baseSalary,
            $department->id
        );
        self::assertEquals($expectedEvent, $event);
    }
}
