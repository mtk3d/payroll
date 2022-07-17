<?php

declare(strict_types=1);

namespace Functional;

use App\ReadModel\Employee\Query\ListEmployees;
use DateTimeImmutable;
use Money\Money;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CommandBus;
use Payroll\Shared\MessengerCommandBus;
use Payroll\Shared\MessengerQueryBus;
use Payroll\Shared\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class CreateEmployeeTest extends KernelTestCase
{
    use InitDatabaseTrait;

    private ?CommandBus $commandBus;
    private ?QueryBus $queryBus;

    public function setUp(): void
    {
        $kernel = $this->bootKernel();
//        $this->initDatabase($kernel);
        $container = $kernel->getContainer();
        $this->commandBus = $container->get(MessengerCommandBus::class);
        $this->queryBus = $container->get(MessengerQueryBus::class);
    }

    public function testCreateDepartment(): void
    {
        $departmentId = DepartmentId::newOne();
        $this->commandBus->dispatch(
            new CreateDepartment($departmentId, 'IT')
        );
        $this->commandBus->dispatch(
            new SetDepartmentBonus($departmentId, 'PERMANENT', 1000)
        );

        $employeeId = EmployeeId::newOne();
        $this->commandBus->dispatch(
            new CreateEmployee($employeeId, 'John', 'Doe', $departmentId)
        );
        $this->commandBus->dispatch(
            new CreateEmployeeSalary($employeeId, new DateTimeImmutable("2005-03-14"), Money::USD(500000), $departmentId)
        );

        $employees = $this->queryBus->query(new ListEmployees());

        $expected = [
            "id" => $employeeId->toString(),
            "first_name" => 'John',
            "last_name" => 'Doe',
            "employment_date" => "2005-03-14 00:00:00",
            "base_salary" => "$5,000.00",
            "department_id" => $departmentId->toString()
        ];

        self::assertContains($expected, $employees);
    }
}
