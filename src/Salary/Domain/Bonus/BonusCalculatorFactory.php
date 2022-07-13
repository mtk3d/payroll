<?php

declare(strict_types=1);

namespace Payroll\Salary\Domain\Bonus;

use Payroll\Salary\Domain\BonusRule;

class BonusCalculatorFactory
{
    /** @var array<string, BonusCalculator> */
    private array $cache;

    public function create(BonusRule $bonusRule): BonusCalculator
    {
        if ($calculator = $this->cached($bonusRule)) {
            return $calculator;
        }

        switch ($bonusRule->bonusType) {
            case BonusType::PERCENTAGE:
                $calculator = new PercentageBonus($bonusRule->value);
                break;
            case BonusType::PERMANENT:
                $calculator = new PermanentBonus($bonusRule->value);
                break;
        }

        $this->cache($bonusRule, $calculator);

        return $calculator;
    }

    private function cached(BonusRule $bonusRule): ?BonusCalculator
    {
        $key = $this->key($bonusRule);

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        return null;
    }

    private function cache(BonusRule $bonusRule, BonusCalculator $calculator): void
    {
        $key = $this->key($bonusRule);

        $this->cache[$key] = $calculator;
    }

    private function key(BonusRule $bonusRule): string
    {
        return sprintf('%s_%s', $bonusRule->bonusType->name, $bonusRule->value);
    }
}
