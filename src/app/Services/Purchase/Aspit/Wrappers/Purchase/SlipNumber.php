<?php

namespace App\Services\Purchase\Aspit\Wrappers\Purchase;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * SlipNumber(伝票番号)
 * example inputValue:「5996」
 * example value :「5996」
 */
class SlipNumber extends ColumnBase
{
    protected string $permittedValueType = 'string';
}
