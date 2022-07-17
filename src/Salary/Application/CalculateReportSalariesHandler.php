<?php

declare(strict_types=1);

namespace Payroll\Salary\Application;

use Payroll\Salary\Application\Command\CalculateReportSalaries;
use Payroll\Salary\Domain\Bonus\BonusCalculatorFactory;
use Payroll\Salary\Domain\EmployeeRepository;
use Payroll\Salary\Domain\ReportSalariesCalculated;
use Payroll\Salary\Domain\SalaryCalculated;
use Payroll\Shared\CommandHandler;
use Payroll\Shared\DomainEventBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CalculateReportSalariesHandler implements CommandHandler
{
    public function __construct(
        private DomainEventBus $bus,
        private EmployeeRepository $repository,
        private BonusCalculatorFactory $calculatorFactory
    ) {
    }

    public function __invoke(CalculateReportSalaries $command): void
    {
        $employees = $this->repository->all();
        foreach ($employees as $employee) {
            $calculator = $this->calculatorFactory->create($employee->bonusRule());
            $bonusCriteria = $employee->bonusCriteria();
            $bonus = $calculator->calculate($bonusCriteria);
            $this->bus->dispatch(
                SalaryCalculated::newOne(
                    $employee->id,
                    $command->reportId,
                    $bonusCriteria->baseSalary,
                    $bonus
                )
            );
        }

        $this->bus->dispatch(ReportSalariesCalculated::newOne($command->reportId));
    }
}
