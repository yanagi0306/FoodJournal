<?php

namespace App\Services\Usen\Order\Wrappers\Slip;

use App\Services\Base\CsvWrappers\ColumnBase;

/**
 * SlipNumber(伝票番号)
 * example inputValue:「No:202200100012152」
 * example value :「202200100012152」
 */
class SlipNumber extends ColumnBase
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractRight = true;
}
