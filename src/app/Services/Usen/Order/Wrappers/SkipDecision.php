<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\SkipDecision\AggregateFlag;
use App\Services\Usen\Order\Wrappers\SkipDecision\OrderStatus;
use App\Services\Usen\Order\Wrappers\SkipDecision\PaymentStatus;
use Exception;

/**
 * 注文データ取込判定クラス
 */
class SkipDecision
{
    private AggregateFlag $aggregateFlag;
    private PaymentStatus $paymentStatus;
    private OrderStatus   $orderStatus;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->aggregateFlag = new AggregateFlag($row['aggregateFlag'], '集計フラグ');
        $this->paymentStatus = new PaymentStatus($row['paymentStatus'], '支払ステータス');
        $this->orderStatus   = new OrderStatus($row['orderStatus'], 'オーダーステータス');
    }
}


