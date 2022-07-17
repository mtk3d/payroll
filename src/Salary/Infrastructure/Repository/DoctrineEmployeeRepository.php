<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Salary\Domain\Employee;
use Payroll\Salary\Domain\EmployeeRepository;

class DoctrineEmployeeRepository implements EmployeeRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function all(): array
    {
        /** @var Employee[] $result */
        $result = $this->em->createQuery(<<<DQL
            SELECT e FROM Payroll\Salary\Domain\Employee e
        DQL)->getResult();

        return $result;
    }

    public function save(Employee $employee): void
    {
        $this->em->persist($employee);
        $this->em->flush();
    }
}
