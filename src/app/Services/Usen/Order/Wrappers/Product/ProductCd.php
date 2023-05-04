<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * ProductCd(商品コード)
 * example inputValue:「00000001967:金の鯛茶漬け」
 * example value :「00000001967」
 */
class ProductCd extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
}
