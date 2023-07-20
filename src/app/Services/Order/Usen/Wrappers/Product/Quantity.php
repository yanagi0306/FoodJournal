<?php

namespace App\Services\Order\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Quantity(数量)
 * example inputValue:「1」
 * example value :「1」
 */
class Quantity extends ColumnBase
{
    protected string $permittedValueType = 'integer';
    protected ?array $invalidValues = [null,0];
}
