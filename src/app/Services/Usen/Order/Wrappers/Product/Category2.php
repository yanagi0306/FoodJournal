<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category1(商品カテゴリ2)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category2 extends ColumnBase
{
    protected string $permittedValueType = 'string';
}
