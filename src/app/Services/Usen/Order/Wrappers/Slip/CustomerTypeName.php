<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * CustomerTypeCd(客層)
 * example inputValue:「客層:夫婦」
 * example value :「夫婦」
 */
class CustomerTypeName extends ColumnBase
{
    protected string $permittedValueType     = 'string';
    protected ?bool  $isExtractRight         = true;
    protected ?array $invalidValues          = null;

}
