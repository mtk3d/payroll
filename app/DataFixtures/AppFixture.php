<?php

declare(strict_types=1);

namespace App\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Money\Money;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Payroll\Shared\UUID\ReportId;

class AppFixture extends Fixture
{
    public function __construct(private CommandBus $commandBus) {}

    public function load(ObjectManager $manager)
    {
        $HRDepartmentId = DepartmentId::newOne();
        $this->createDepartment($HRDepartmentId, 'HR', 'PERMANENT', 10000);
        $CSDepartment = DepartmentId::newOne();
        $this->createDepartment($CSDepartment, 'Customer Service', 'PERCENTAGE', 1000);

        $adamId = EmployeeId::newOne();
        $aniaId = EmployeeId::newOne();
        $this->createEmployee($adamId, 'Adam', 'Kowalski', '1990-03-14', 100000, $HRDepartmentId);
        $this->createEmployee($aniaId, 'Ania', 'Nowak', '2000-03-14', 110000, $CSDepartment);

        $this->generateReport(ReportId::newOne());
    }

    private function createDepartment(DepartmentId $id, string $name, string $bonusType, int $bonusFactor): void
    {
        $createDepartment = new CreateDepartment($id, $name);
        $setDepartmentBonus = new SetDepartmentBonus($id, $bonusType, $bonusFactor);
        $this->commandBus->dispatch($createDepartment);
        $this->commandBus->dispatch($setDepartmentBonus);
    }

    private function createEmployee(
        EmployeeId $id,
        string $firstName,
        string $lastName,
        string $employeeDate,
        int $baseSalary,
        DepartmentId $departmentId
    ): void {
        $createEmployee = new CreateEmployee($id, $firstName, $lastName, $departmentId);
        $createEmployeeSalary = new CreateEmployeeSalary(
            $id, new DateTimeImmutable($employeeDate), Money::USD($baseSalary), $departmentId
        );
        $this->commandBus->dispatch($createEmployee);
        $this->commandBus->dispatch($createEmployeeSalary);
    }

    private function generateReport(ReportId $reportId): void
    {
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));
    }
}
