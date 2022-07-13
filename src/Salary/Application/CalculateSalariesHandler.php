<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\CalculateSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\EmployeeRepository;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Shared\DomainEventBus;

class CalculateSalariesHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private EmployeeRepository $repository,
        private BonusCalculatorFactory $calculatorFactory
    ) {}

    public function handle(CalculateSalaries $command): void
    {
        $employees = $this->repository->all();
        foreach ($employees as $employee) {
            $calculator = $this->calculatorFactory->create($employee->bonusRule());
            $amount = $calculator->calculate($employee->bonusCriteria());
            $this->bus->dispatch(SalaryCalculated::newOne($employee->employeeId, $command->reportId, $amount));
        }
    }
}
