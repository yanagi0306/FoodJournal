<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * Category1(商品カテゴリ2)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category2 extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected array $invalidValues = [];
}
