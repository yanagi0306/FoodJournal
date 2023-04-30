<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * Cash(現金支払額)
 * example inputValue:「1000」
 * example value :「1000」
 */
class Cash extends BaseWrapper
{
    protected bool $isCheckPositiveInteger = true;
}
