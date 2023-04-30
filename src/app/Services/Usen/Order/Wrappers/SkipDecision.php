<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use Exception;
use app\Services\Usen\Order\Wrappers\SkipDecision\AggregateFlag;
use app\Services\Usen\Order\Wrappers\SkipDecision\OrderStatus;
use app\Services\Usen\Order\Wrappers\SkipDecision\PaymentStatus;

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
        $this->aggregateFlag = new AggregateFlag($row['aggregateFlag']);
        $this->paymentStatus = new PaymentStatus($row['paymentStatus']);
        $this->orderStatus   = new OrderStatus($row['orderStatus']);
    }
}


