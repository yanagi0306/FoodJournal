<?php

namespace app\Services\Usen\Order\Wrappers\Payment;

use app\Services\Usen\Order\Wrappers\BaseWrapper;

/**
 * ElectronicMoney(電子マネー支払額)
 * example inputValue:「1000」
 * example value :「1000」
 */
class ElectronicMoney extends BaseWrapper
{
    protected bool $isCheckPositiveInteger = true;
}
