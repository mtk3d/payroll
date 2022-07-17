<?php

declare(strict_types=1);

namespace Integration;

use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Infrastructure\Repository\DoctrineDepartmentRepository;
use Payroll\Employment\Infrastructure\Repository\DoctrineEmployeeRepository;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class EmploymentDoctrineTest extends KernelTestCase
{
    use InitDatabaseTrait;

    private ?DoctrineEmployeeRepository $employeeRepository;
    private ?DoctrineDepartmentRepository $departmentRepository;

    protected function setUp(): void
    {
        $kernel = $this->bootKernel();
        $this->initDatabase($kernel);
        $container = $kernel->getContainer();
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
}
