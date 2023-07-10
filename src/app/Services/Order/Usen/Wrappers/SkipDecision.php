<?php

namespace app\Services\Order\Usen\Usen\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use app\Services\Order\Usen\Usen\Wrappers\SkipDecision\AggregateFlag;
use app\Services\Order\Usen\Usen\Wrappers\SkipDecision\OrderStatus;
use app\Services\Order\Usen\Usen\Wrappers\SkipDecision\PaymentStatus;
use Exception;

/**
 * 注文データ取込判定クラス
 */
class SkipDecision
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


