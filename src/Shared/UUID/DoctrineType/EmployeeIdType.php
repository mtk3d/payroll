<?php

declare(strict_types=1);

namespace Payroll\Shared\UUID\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Payroll\Shared\UUID\EmployeeId;

class EmployeeIdType extends GuidType
{
    public const EMPLOYEE_ID = 'employee_id';

    /**
     * @return EmployeeId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return EmployeeId::of($value);
    }

    /**
     * @param EmployeeId $value
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
        return self::EMPLOYEE_ID;
    }
}
