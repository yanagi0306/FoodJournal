<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * UnitCost(理論原価)
 * example inputValue:「335.6」
 * example value :「335.6」
 */
class UnitCost extends ColumnBase
{
    protected string $permittedValueType = 'double';
}
