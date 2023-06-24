<?php

namespace App\Services\Order\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category1(商品カテゴリ1)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category1 extends ColumnBase
{
    protected string $permittedValueType = 'string';
}
