<?php

declare(strict_types=1);

namespace App\ReadModel\Shared;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

trait MoneyFormatter
{
    private function moneyFormat(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
