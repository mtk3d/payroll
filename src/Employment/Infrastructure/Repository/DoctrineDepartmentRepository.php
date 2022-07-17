<?php

declare(strict_types=1);

namespace Payroll\Employment\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payroll\Employment\Domain\Department;
use Payroll\Employment\Domain\DepartmentNotFoundException;
use Payroll\Employment\Domain\DepartmentRepository;
use Payroll\Shared\UUID\DepartmentId;

class DoctrineDepartmentRepository implements DepartmentRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
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
