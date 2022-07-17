<?php

declare(strict_types=1);

namespace Test\Integration;

use function aDepartment;
use function aEmployee;

use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Salary\Infrastructure\Repository\DoctrineDepartmentRepository;
use Payroll\Salary\Infrastructure\Repository\DoctrineEmployeeRepository;
use Payroll\Shared\UUID\DepartmentId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class SalaryDoctrineTest extends KernelTestCase
{
    use InitDatabaseTrait;

    private ?DoctrineEmployeeRepository $employeeRepository;
    private ?DoctrineDepartmentRepository $departmentRepository;

    protected function setUp(): void
    {
        $kernel = $this->bootKernel();
//        $this->initDatabase($kernel);
        $container = $kernel->getContainer();
        $this->employeeRepository = $container->get(DoctrineEmployeeRepository::class);
        $this->departmentRepository = $container->get(DoctrineDepartmentRepository::class);
    }

    public function testEmployeePersistence(): void
    {
        $department = aDepartment();
        $this->departmentRepository->save($department);
        $employee = aEmployee(null, null, $department);
        $this->employeeRepository->save($employee);

        self::assertEquals($department, $this->departmentRepository->find($department->id));
        self::assertContains($employee, $this->employeeRepository->all());
    }

    public function testDepartmentNotFound(): void
    {
        self::expectException(DepartmentNotFoundException::class);
        $this->departmentRepository->find(DepartmentId::newOne());
    }
}
