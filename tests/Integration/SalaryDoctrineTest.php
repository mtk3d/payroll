<?php

declare(strict_types=1);

namespace Test\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Payroll\Salary\Infrastructure\Repository\DoctrineDepartmentRepository;
use Payroll\Salary\Infrastructure\Repository\DoctrineEmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class SalaryDoctrineTest extends KernelTestCase
{
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

    public function testEmployeePersistence(): void
    {
        $department = aDepartment();
        $this->departmentRepository->save($department);
        $employee = aEmployee(null, null, $department);
        $this->employeeRepository->save($employee);

        self::assertEquals($department, $this->departmentRepository->find($department->id));
        self::assertContains($employee, $this->employeeRepository->all());
    }

    private function initDatabase(KernelInterface $kernel): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }
}
