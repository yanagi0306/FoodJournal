<?php

namespace App\Services\Usen\Order\Wrappers\SkipDecision;

use App\Services\Usen\Order\Wrappers\ColumnBase;

/**
 * PaymentStatus(支払いステータス)
 * 「12」「30」「50」の時は取り込みスキップ
 */
class PaymentStatus extends ColumnBase
{
    protected bool $isExtractLeft = true;
    protected array $invalidValues = [12, 30, 50];
}
