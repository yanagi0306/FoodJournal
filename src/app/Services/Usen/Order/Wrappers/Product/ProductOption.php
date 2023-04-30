<?php

namespace app\Services\Usen\Order\Wrappers\Product;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * ProductOption(サブメニュー)
 * example inputValue:「白米x1 / 厳選カンパチ60gx1」
 * example value :「白米x1 / 厳選カンパチ60gx1」
 */
class ProductOption extends BaseWrapper
{
    protected array $invalidValues = [];
    protected string $permittedValueType = 'string';
}
