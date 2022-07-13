<?php

declare(strict_types=1);

namespace Test\Unit\Salary;

use Carbon\Carbon;
use Money\Money;
use Payroll\Salary\Domain\BonusRule\PercentageBonus;
use Payroll\Salary\Domain\BonusRule\PermanentBonus;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\Employee;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\EmployeeId;
use PHPUnit\Framework\TestCase;

class SalaryTest extends TestCase
{
    public function testPercentageBonus(): void
    {
        $employeeId = EmployeeId::newOne();
        $departmentId = DepartmentId::newOne();
        $employmentDate = Carbon::now()->subYears(5);
        $baseSalary = Money::USD(110000);
        $bonus = new PercentageBonus(10);
        $department = new Department($departmentId, $bonus);
        $employee = new Employee($employeeId, $employmentDate, $baseSalary, $department);

        self::assertEquals(Money::USD(121000), $employee->fullSalary);
    }

    public function testPermanentBonus(): void
    {
        $employeeId = EmployeeId::newOne();
        $departmentId = DepartmentId::newOne();
        $employmentDate = Carbon::now()->subYears(10);
        $baseSalary = Money::USD(100000);
        $bonus = new PermanentBonus(Money::USD(10000));
        $department = new Department($departmentId, $bonus);
        $employee = new Employee($employeeId, $employmentDate, $baseSalary, $department);

        self::assertEquals(Money::USD(200000), $employee->fullSalary);
    }
}
