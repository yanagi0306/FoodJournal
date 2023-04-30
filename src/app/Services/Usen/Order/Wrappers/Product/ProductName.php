<?php

namespace app\Services\Usen\Order\Wrappers\Product;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * ProductCd(商品名)
 * example inputValue:「00000001967:金の鯛茶漬け」
 * example value :「金の鯛茶漬け」
 */
class ProductName extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
}
