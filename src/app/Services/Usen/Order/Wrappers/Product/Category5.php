<?php

namespace app\Services\Usen\Order\Wrappers\Product;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * Category1(商品カテゴリ5)
 * example inputValue:「デリバリー」
 * example value :「デリバリー」
 */
class Category5 extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected array $invalidValues = [];
}
