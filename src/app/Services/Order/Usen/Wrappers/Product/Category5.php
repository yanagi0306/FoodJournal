<?php

namespace app\Services\Order\Usen\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Category1(商品カテゴリ5)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category5 extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected ?array $invalidValues      = null;
}
