<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\ReadModel\Employee\Query\ListEmployees;
use DateTimeImmutable;
use Money\Money;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\CQRS\MessengerCommandBus;
use Payroll\Shared\CQRS\MessengerQueryBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateEmployeeTest extends KernelTestCase
{
    private ?CommandBus $commandBus;
    private ?QueryBus $queryBus;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->commandBus = $container->get(MessengerCommandBus::class);
        $this->queryBus = $container->get(MessengerQueryBus::class);
    }

    public function testCreateDepartment(): void
    {
        $departmentId = DepartmentId::newOne();
        $createDepartment = new CreateDepartment($departmentId, 'IT');
        $setDepartmentBonus = new SetDepartmentBonus($departmentId, 'PERMANENT', 1000);
        $this->commandBus->dispatch($createDepartment);
        $this->commandBus->dispatch($setDepartmentBonus);

        $employeeId = EmployeeId::newOne();
        $createEmployee = new CreateEmployee($employeeId, 'John', 'Doe', $departmentId);
        $createEmployeeSalary = new CreateEmployeeSalary($employeeId, new DateTimeImmutable('2005-03-14'), Money::USD(500000), $departmentId);
        $this->commandBus->dispatch($createEmployee);
        $this->commandBus->dispatch($createEmployeeSalary);

        $employees = $this->queryBus->query(new ListEmployees());

        $expected = [
            'id' => $employeeId->toString(),
            'first_name' => 'John',
            'last_name' => 'Doe',
            'employment_date' => '2005-03-14 00:00:00',
            'base_salary' => '$5,000.00',
            'department_id' => $departmentId->toString(),
        ];

        self::assertContains($expected, $employees);
    }
}
