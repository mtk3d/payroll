<?php

declare(strict_types=1);

namespace Payroll\Shared\UUID\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Payroll\Shared\UUID\DepartmentId;

class DepartmentIdType extends GuidType
{
    public const DEPARTMENT_ID = 'department_id';

    /**
     * @param string $value
     *
     * @return DepartmentId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return DepartmentId::of($value);
    }

    /**
     * @param DepartmentId $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toString();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::DEPARTMENT_ID;
    }
}
