<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\UpdateDepartmentBonus;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\BonusRule;
use Payroll\Salary\Domain\DepartmentBonusChanged;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Salary\Domain\Exception\DepartmentAlreadyExistException;
use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Shared\DomainEventBus;

class UpdateDepartmentBonusHandler
{
    public function __construct(private DomainEventBus $bus, private DepartmentRepository $repository)
    {
    }

    /**
     * @throws DepartmentNotFoundException
     * @throws DepartmentAlreadyExistException
     */
    public function handle(UpdateDepartmentBonus $command): void
    {
        $bonusType = BonusType::fromString($command->bonusType);
        $bonusRule = new BonusRule($bonusType, $command->bonusValue);

        $department = $this->repository->find($command->departmentId);

        $department->setBonusRule($bonusRule);
        $this->repository->save($department);

        $this->bus->dispatch(DepartmentBonusChanged::newOne(
            $department->id,
            $department->bonusRule()->bonusType->name,
            $department->bonusRule()->value
        ));
    }
}
