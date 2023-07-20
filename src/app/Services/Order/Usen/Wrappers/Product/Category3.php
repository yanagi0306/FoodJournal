<?php

namespace App\Services\Order\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category3(商品カテゴリ1)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category3 extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $invalidValues      = null;
}
