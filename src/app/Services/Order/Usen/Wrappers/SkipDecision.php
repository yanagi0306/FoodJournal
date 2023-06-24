<?php

namespace App\Services\Order\Usen\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Order\Usen\Wrappers\SkipDecision\AggregateFlag;
use App\Services\Order\Usen\Wrappers\SkipDecision\OrderStatus;
use App\Services\Order\Usen\Wrappers\SkipDecision\PaymentStatus;
use Exception;

/**
 * 注文データ取込判定クラス
 */
class SkipDecision extends ColumnGroupBase
{
    protected AggregateFlag $aggregateFlag;
    protected PaymentStatus $paymentStatus;
    protected OrderStatus   $orderStatus;

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


