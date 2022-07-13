<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\BonusRule;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentBonusSet;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Shared\DomainEventBus;

class SetDepartmentBonusHandler
{
    public function __construct(private DomainEventBus $bus, private DepartmentRepository $repository) {}

    public function handle(SetDepartmentBonus $command): void
    {
        $bonusType = BonusType::fromString($command->bonusType);
        $bonusRule = new BonusRule($bonusType, $command->bonusValue);
        $department = new Department($command->departmentId, $bonusRule);

        $this->repository->save($department);

        $this->bus->dispatch(DepartmentBonusSet::newOne(
            $command->departmentId,
            $command->bonusType,
            $command->bonusValue
        ));
    }
}
