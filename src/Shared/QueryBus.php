<?php

declare(strict_types=1);

namespace Payroll\Shared;

interface QueryBus
{
    /**
     * @return mixed
     */
    public function query(Query $query);
}
