<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * Amount(仕入金額)
 * example inputValue:「1000」
 * example value :「1000」
 */
class Amount extends ColumnBase
{
    protected string $permittedValueType = 'integer';
}
