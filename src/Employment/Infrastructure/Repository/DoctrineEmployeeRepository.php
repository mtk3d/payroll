<?php

declare(strict_types=1);

namespace Payroll\Employment\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Employment\Domain\Employee;
use Payroll\Employment\Domain\EmployeeRepository;
use Payroll\Shared\UUID\EmployeeId;

class DoctrineEmployeeRepository implements EmployeeRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(Employee $employee): void
    {
        $this->em->persist($employee);
        $this->em->flush();
    }

    public function find(EmployeeId $employeeId): ?Employee
    {
        /** @var Employee[] $result */
        $query = $this->em->createQuery(<<<DQL
            SELECT e, d
            FROM Payroll\Employment\Domain\Employee e
            JOIN e.department d
            WHERE e.id = :id
        DQL);

        $query->setParameter('id', $employeeId->toString());
        $result = $query->getResult();

        reset($result);

        return current($result) ?: null;
    }
}
