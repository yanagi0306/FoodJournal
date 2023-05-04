<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * UnitPrice(販売価格)
 * example inputValue:「1200」
 * example value :「1200」
 */
class UnitPrice extends BaseWrapper
{
    protected array $invalidValues = [null, '', 0];
}
