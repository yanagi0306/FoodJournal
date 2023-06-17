<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * UnitPrice(販売価格)
 * example inputValue:「1200」
 * example value :「1200」
 */
class UnitPrice extends ColumnBase
{
    protected string $permittedValueType = 'integer';
}
