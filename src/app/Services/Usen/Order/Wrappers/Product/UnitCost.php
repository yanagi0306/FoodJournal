<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * UnitCost(理論原価)
 * example inputValue:「335.6」
 * example value :「335.6」
 */
class UnitCost extends BaseWrapper
{
    protected string $permittedValueType = 'double';
    protected array $invalidValues = [null, '', 0];
}
