<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Salary\Domain\Employee;
use Payroll\Salary\Domain\EmployeeRepository;
use Payroll\Salary\Domain\EmployeeSalaryCreated;
use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Shared\DomainEventBus;

class CreateEmployeeSalaryHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private EmployeeRepository $employeeRepository,
        private DepartmentRepository $departmentRepository
    ) {}

    /**
     * @throws DepartmentNotFoundException
     */
    public function handle(CreateEmployeeSalary $command): void
    {
        $department = $this->departmentRepository->find($command->departmentId);
        $employee = new Employee($command->id, $command->employmentDate, $command->baseSalary, $department);
        $this->employeeRepository->save($employee);

        $this->bus->dispatch(EmployeeSalaryCreated::newOne(
            $employee->employeeId,
            $employee->bonusCriteria()->employmentDate,
            $employee->bonusCriteria()->baseSalary,
            $department->id
        ));
    }
}
