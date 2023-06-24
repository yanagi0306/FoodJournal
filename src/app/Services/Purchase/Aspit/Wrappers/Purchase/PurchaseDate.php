<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * PurchaseDate(伝票発行日)
 * example inputValue:「2022/12/25」
 * example value :「2022/12/25」
 */
class PurchaseDate extends ColumnBase
{
    protected string $permittedValueType = 'date';
}
