<?php

namespace App\Services\Usen\Order\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * ProductCd(商品コード)
 * example inputValue:「00000001967:金の鯛茶漬け」
 * example value :「00000001967」
 */
class ProductCd extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractLeft = true;
}
