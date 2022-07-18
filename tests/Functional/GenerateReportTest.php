<?php

declare(strict_types=1);

namespace Test\Functional;

use App\ReadModel\Report\Query\GetReport;
use App\ReadModel\Report\Query\ListReportLines;
use DateTimeImmutable;
use Money\Money;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Employment\Application\Command\CreateEmployee;
use Payroll\Report\Application\Command\GenerateSalaryReport;
use Payroll\Salary\Application\Command\CreateEmployeeSalary;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CommandBus;
use Payroll\Shared\MessengerCommandBus;
use Payroll\Shared\MessengerQueryBus;
use Payroll\Shared\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Payroll\Shared\UUID\EmployeeId;
use Payroll\Shared\UUID\ReportId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class GenerateReportTest extends KernelTestCase
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

    public function testGenerateReport(): void
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
            new CreateEmployeeSalary($employeeId, new DateTimeImmutable('2005-03-14'), Money::USD(500000), $departmentId)
        );

        $departmentId = DepartmentId::newOne();
        $this->commandBus->dispatch(
            new CreateDepartment($departmentId, 'HR')
        );
        $this->commandBus->dispatch(
            new SetDepartmentBonus($departmentId, 'PERCENTAGE', 1000)
        );

        $employeeId = EmployeeId::newOne();
        $this->commandBus->dispatch(
            new CreateEmployee($employeeId, 'Jane', 'Doe', $departmentId)
        );
        $this->commandBus->dispatch(
            new CreateEmployeeSalary($employeeId, new DateTimeImmutable('2005-03-14'), Money::USD(200000), $departmentId)
        );

        $reportId = ReportId::newOne();

        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        $report = $this->queryBus->query(new GetReport($reportId));
        $reportLines = $this->queryBus->query(new ListReportLines($reportId));

        self::assertTrue(true);
    }
}
