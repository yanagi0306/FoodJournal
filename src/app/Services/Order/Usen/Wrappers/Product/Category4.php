<?php

namespace App\Services\Order\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category1(商品カテゴリ4)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category4 extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $invalidValues      = null;
}

