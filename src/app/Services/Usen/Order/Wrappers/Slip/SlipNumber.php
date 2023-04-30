<?php

namespace app\Services\Usen\Order\Wrappers\Slip;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * SlipNumber(伝票番号)
 * example inputValue:「No:202200100012152」
 * example value :「202200100012152」
 */
class SlipNumber extends BaseWrapper
{
    protected string $permittedValueType = 'string';
    protected bool $isExtractRight = true;
}
