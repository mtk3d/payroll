<?php

declare(strict_types=1);

namespace Payroll\Shared\UUID\DoctrineType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Payroll\Shared\UUID\ReportId;

class ReportIdType extends GuidType
{
    public const REPORT_ID = 'report_id';

    /**
     * @return ReportId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ReportId::of($value);
    }

    /**
     * @param ReportId $value
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
        return self::REPORT_ID;
    }
}
