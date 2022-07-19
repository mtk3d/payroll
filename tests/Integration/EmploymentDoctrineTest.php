<?php

declare(strict_types=1);

namespace Tests\Integration;

use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\DepartmentNotFoundException;
use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Infrastructure\Repository\DoctrineDepartmentRepository;
use Payroll\Employment\Infrastructure\Repository\DoctrineEmployeeRepository;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmploymentDoctrineTest extends KernelTestCase
{
    private ?DoctrineEmployeeRepository $employeeRepository;
    private ?DoctrineDepartmentRepository $departmentRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->employeeRepository = $container->get(DoctrineEmployeeRepository::class);
        $this->departmentRepository = $container->get(DoctrineDepartmentRepository::class);
    }

    public function testDepartmentPersistence(): void
    {
        $departmentId = DepartmentId::newOne();
        $department = new Department($departmentId, 'IT');
        $this->departmentRepository->save($department);

        self::assertEquals($department, $this->departmentRepository->find($departmentId));
    }

    public function testEmployeePersistence(): void
    {
        $departmentId = DepartmentId::newOne();
        $department = new Department($departmentId, 'IT');
        $employeeId = EmployeeId::newOne();
        $employee = new Employee($employeeId, 'John', 'Doe', $department);

        $this->departmentRepository->save($department);
        $this->employeeRepository->save($employee);

        self::assertEquals($employee, $this->employeeRepository->find($employeeId));
    }

    public function testDepartmentNotFound(): void
    {
        self::expectException(DepartmentNotFoundException::class);
        $this->departmentRepository->find(DepartmentId::newOne());
    }
}
