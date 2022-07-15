<?php

declare(strict_types=1);

use Money\Money;
use Payroll\Salary\Domain\Bonus\BonusType;
use Payroll\Salary\Domain\BonusRule;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\Employee;
use Payroll\Shared\DepartmentId;
use Payroll\Shared\EmployeeId;

function aEmployee(?DateTimeImmutable $employmentDate = null, ?int $baseSalary = null, ?Department $department = null): Employee
{
    if (is_null($employmentDate)) {
        $employmentDate = new DateTimeImmutable('2005-03-14');
    }

    if (is_null($baseSalary)) {
        $baseSalary = 110000;
    }

    if (is_null($department)) {
        $department = aDepartment();
    }

    $employeeId = EmployeeId::newOne();

    return new Employee($employeeId, $employmentDate, Money::USD($baseSalary), $department);
}

function aDepartment(?BonusType $bonusType = null, ?int $value = null): Department
{
    if (is_null($bonusType)) {
        $bonusType = BonusType::PERCENTAGE;
    }

    if (is_null($value)) {
        $value = 1000;
    }

    $departmentId = DepartmentId::newOne();

    return new Department($departmentId, new BonusRule($bonusType, $value));
}
