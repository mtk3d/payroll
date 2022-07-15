<?php

declare(strict_types=1);

namespace Payroll\Employment\Application;

use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\DepartmentRegistered;
use Payroll\Employment\Domain\DepartmentRepository;
use Payroll\Shared\DomainEventBus;

class CreateDepartmentHandler
{
    public function __construct(private DomainEventBus $bus, private DepartmentRepository $repository)
    {
    }

    public function handle(CreateDepartment $command): void
    {
        $department = new Department($command->id, $command->name);
        $this->repository->save($department);
        $this->bus->dispatch(DepartmentRegistered::newOne($department->id, $command->name));
    }
}
