<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\ColumnBase;

/**
 * ProductOption(サブメニュー)
 * example inputValue:「白米x1 / 厳選カンパチ60gx1」
 * example value :「白米x1 / 厳選カンパチ60gx1」
 */
class OrderOption extends ColumnBase
{
    protected array $invalidValues = [];
    protected string $permittedValueType = 'string';
}
