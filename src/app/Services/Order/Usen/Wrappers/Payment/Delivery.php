<?php

namespace App\Services\Order\Usen\Wrappers\Payment;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Delivery(デリバリー)
 * example inputValue:「1000」
 * example value :「1000」
 */
class Delivery extends ColumnBase
{
    protected string $permittedValueType = 'integer';
}
