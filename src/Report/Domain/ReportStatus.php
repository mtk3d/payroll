<?php

declare(strict_types=1);

namespace Payroll\Report\Domain;

enum ReportStatus: string
{
    case PROCESSING = 'PROCESSING';
    case GENERATED = 'GENERATED';
}
