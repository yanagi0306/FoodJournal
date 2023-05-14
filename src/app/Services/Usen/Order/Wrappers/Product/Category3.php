<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category3(商品カテゴリ1)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category3 extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected array $invalidValues = [];
}
