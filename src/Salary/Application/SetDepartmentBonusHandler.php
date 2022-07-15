<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\BonusRule;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Salary\Domain\Exception\DepartmentAlreadyExistException;
use Payroll\Shared\CommandHandler;
use Payroll\Shared\DomainEventBus;

class SetDepartmentBonusHandler implements CommandHandler
{
    public function __construct(private DomainEventBus $bus, private DepartmentRepository $repository)
    {
    }

    /**
     * @throws DepartmentAlreadyExistException
     */
    public function handle(SetDepartmentBonus $command): void
    {
        $bonusType = BonusType::fromString($command->bonusType);
        $bonusRule = new BonusRule($bonusType, $command->bonusFactor);
        $department = new Department($command->departmentId, $bonusRule);

        $this->repository->save($department);

        $this->bus->dispatch(DepartmentBonusChanged::newOne(
            $department->id,
            $department->bonusRule()->bonusType->name,
            $department->bonusRule()->factor
        ));
    }
}
