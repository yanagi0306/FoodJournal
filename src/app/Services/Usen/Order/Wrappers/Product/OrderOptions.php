<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * ProductOption(サブメニュー)
 * example inputValue:「白米x1 / 厳選カンパチ60gx1」
 * example value :「白米x1 / 厳選カンパチ60gx1」
 */
class OrderOptions extends ColumnBase
{
    protected array $invalidValues = [];
    protected string $permittedValueType = 'string';
}
