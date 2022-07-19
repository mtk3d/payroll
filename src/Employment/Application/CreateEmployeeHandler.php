<?php

declare(strict_types=1);

namespace Payroll\Employment\Application;

use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Employment\Domain\DepartmentRepository;
use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Domain\EmployeeRegistered;
use Payroll\Employment\Domain\EmployeeRepository;
use Payroll\Shared\CQRS\CommandHandler;
use Payroll\Shared\Event\DomainEventBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateEmployeeHandler implements CommandHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private EmployeeRepository $employeeRepository,
        private DepartmentRepository $departmentRepository
    ) {
    }

    public function __invoke(CreateEmployee $command): void
    {
        $department = $this->departmentRepository->find($command->departmentId);
        $employee = new Employee(
            $command->id,
            $command->firstname,
            $command->lastname,
            $department
        );

        $this->employeeRepository->save($employee);

        $this->bus->dispatch(
            EmployeeRegistered::newOne(
                $employee->id,
                $command->firstname,
                $command->lastname,
                $department->id
            )
        );
    }
}
