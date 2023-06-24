<?php

namespace App\Services\Order\Usen\Wrappers\Product;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * ProductCd(商品名)
 * example inputValue:「00000001967:金の鯛茶漬け」
 * example value :「金の鯛茶漬け」
 */
class ProductName extends ColumnBase
{
    protected string $permittedValueType     = 'string';
    protected ?bool  $isExtractRight          = true;
}
