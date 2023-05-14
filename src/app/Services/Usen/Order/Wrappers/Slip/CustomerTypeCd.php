<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * CustomerTypeCd(客層)
 * example inputValue:「客層:夫婦」
 * example value :「夫婦」
 */
class CustomerTypeCd extends ColumnBase
{
    protected bool $isExtractRight = false;
    protected string $permittedValueType = 'string';
    protected array $invalidValues = [];
}
