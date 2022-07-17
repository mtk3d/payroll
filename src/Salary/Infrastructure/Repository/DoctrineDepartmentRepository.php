<?php

declare(strict_types=1);

namespace Payroll\Salary\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Salary\Domain\Department;
use Payroll\Salary\Domain\DepartmentRepository;
use Payroll\Salary\Domain\Exception\DepartmentNotFoundException;
use Payroll\Shared\UUID\DepartmentId;

class DoctrineDepartmentRepository implements DepartmentRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Department $department): void
    {
        $this->em->persist($department);
        $this->em->flush();
    }

    public function find(DepartmentId $departmentId): Department
    {
        if (!$department = $this->em->find(Department::class, $departmentId)) {
            throw new DepartmentNotFoundException(sprintf('Department %s not found', $departmentId->toString()));
        }

        return $department;
    }
}
