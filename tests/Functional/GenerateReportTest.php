<?php

declare(strict_types=1);

namespace Tests\Functional;

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

class GenerateReportTest extends KernelTestCase
{
    private ?CommandBus $commandBus;
    private ?QueryBus $queryBus;

    public function setUp(): void
    {
        $kernel = $this->bootKernel();
        $container = $kernel->getContainer();
        $this->commandBus = $container->get(MessengerCommandBus::class);
        $this->queryBus = $container->get(MessengerQueryBus::class);
    }

    public function testGenerateReport(): void
    {
        $adamId = EmployeeId::newOne();
        $aniaId = EmployeeId::newOne();
        $this->provideFixtures($adamId, $aniaId);

        $reportId = ReportId::newOne();
        $this->commandBus->dispatch(new GenerateSalaryReport($reportId));

        $report = $this->queryBus->query(new GetReport($reportId));
        $reportLines = $this->queryBus->query(new ListReportLines($reportId));

        $expectedReport = [
            'id' => $reportId->toString(),
            'date' => '2005-03-14 00:00:00',
            'status' => 'GENERATED',
        ];

        self::assertEquals($expectedReport, $report);
        self::assertEquals(
            [
                'employee_id' => $adamId->toString(),
                'first_name' => 'Adam',
                'last_name' => 'Kowalski',
                'department' => 'HR',
                'base_salary' => '$1,000.00',
                'bonus' => '$1,000.00',
                'bonus_type' => 'PERMANENT',
                'salary' => '$2,000.00',
            ],
            $this->filterLine($reportLines, $adamId)
        );
        self::assertEquals(
            [
                'employee_id' => $aniaId->toString(),
                'first_name' => 'Ania',
                'last_name' => 'Nowak',
                'department' => 'Customer Service',
                'base_salary' => '$1,100.00',
                'bonus' => '$110.00',
                'bonus_type' => 'PERCENTAGE',
                'salary' => '$1,210.00',
            ],
            $this->filterLine($reportLines, $aniaId)
        );
    }

    private function filterLine(array $lines, EmployeeId $id): array
    {
        $result = array_filter($lines, fn ($line) => $line['employee_id'] == $id->toString());

        return current($result);
    }

    private function provideFixtures(EmployeeId $adamId, EmployeeId $aniaId): void
    {
        $HRDepartmentId = DepartmentId::newOne();
        $this->createDepartment($HRDepartmentId, 'HR', 'PERMANENT', 10000);
        $CSDepartment = DepartmentId::newOne();
        $this->createDepartment($CSDepartment, 'Customer Service', 'PERCENTAGE', 1000);

        $this->createEmployee($adamId, 'Adam', 'Kowalski', '1990-03-14', 100000, $HRDepartmentId);
        $this->createEmployee($aniaId, 'Ania', 'Nowak', '2000-03-14', 110000, $CSDepartment);
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
        $createEmployeeSalary = new CreateEmployeeSalary($id, new DateTimeImmutable($employeeDate), Money::USD($baseSalary), $departmentId);
        $this->commandBus->dispatch($createEmployee);
        $this->commandBus->dispatch($createEmployeeSalary);
    }
}
