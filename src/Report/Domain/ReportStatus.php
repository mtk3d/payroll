<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

enum ReportStatus
{
    case PROCESSING;
    case GENERATED;
}
