<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * OtherPayment(その他支払額)
 * example inputValue:「1000」
 * example value :「1000」
 */
class OtherPayment extends BaseWrapper
{
    protected bool $isCheckPositiveInteger = true;
}
