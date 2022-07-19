<?php

declare(strict_types=1);

namespace Payroll\Shared\CQRS;

interface QueryBus
{
    /**
     * @return mixed|null
     */
    public function query(Query $query);
}
