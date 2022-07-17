<?php

declare(strict_types=1);

namespace Test\Functional;

use App\ReadModel\Department\Query\ListDepartments;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CommandBus;
use Payroll\Shared\MessengerCommandBus;
use Payroll\Shared\MessengerQueryBus;
use Payroll\Shared\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\InitDatabaseTrait;

class CreateDepartmentTest extends KernelTestCase
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

        $departments = $this->queryBus->query(new ListDepartments());

        $expected = [
            "id" => $departmentId->toString(),
            "name" => 'IT',
            "bonus_type" => 'PERMANENT'
        ];

        self::assertContains($expected, $departments);
    }
}
