<?php

declare(strict_types=1);

namespace Tests\Functional;

use App\ReadModel\Department\Query\ListDepartments;
use Payroll\Employment\Application\Command\CreateDepartment;
use Payroll\Salary\Application\Command\SetDepartmentBonus;
use Payroll\Shared\CQRS\CommandBus;
use Payroll\Shared\CQRS\MessengerCommandBus;
use Payroll\Shared\CQRS\MessengerQueryBus;
use Payroll\Shared\CQRS\QueryBus;
use Payroll\Shared\UUID\DepartmentId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateDepartmentTest extends KernelTestCase
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

        $departments = $this->queryBus->query(new ListDepartments());

        $expected = [
            'id' => $departmentId->toString(),
            'name' => 'IT',
            'bonus_type' => 'PERMANENT',
            'bonus_factor' => '1000',
        ];

        self::assertContains($expected, $departments);
    }
}
