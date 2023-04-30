<?php

namespace app\Services\Usen\Order\Wrappers\Product;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * UnitCost(理論原価)
 * example inputValue:「335.6」
 * example value :「335.6」
 */
class UnitCost extends BaseWrapper
{
    protected string $permittedValueType = 'float';
    protected bool $isCheckPositiveInteger = true;
    protected array $invalidValues = [null, '', 0];
}
