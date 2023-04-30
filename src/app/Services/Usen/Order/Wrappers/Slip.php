<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use Exception;
use app\Services\Usen\Order\Wrappers\Slip\CustomerSegment;
use app\Services\Usen\Order\Wrappers\Slip\MenCount;
use app\Services\Usen\Order\Wrappers\Slip\OrderDate;
use app\Services\Usen\Order\Wrappers\Slip\PaymentDate;
use app\Services\Usen\Order\Wrappers\Slip\SalesType;
use app\Services\Usen\Order\Wrappers\Slip\SlipNumber;
use app\Services\Usen\Order\Wrappers\Slip\StoreCd;
use app\Services\Usen\Order\Wrappers\Slip\WomenCount;

class Slip
{
    private StoreCd         $storeCd;
    private SlipNumber      $slipNumber;
    private OrderDate       $orderDate;
    private PaymentDate     $paymentDate;
    private MenCount        $menCount;
    private WomenCount      $womenCount;
    private CustomerSegment $customerSegment;
    private SalesType       $salesType;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->storeCd         = new StoreCd($row['storeCd']);
        $this->slipNumber      = new SlipNumber($row['slipNumber']);
        $this->orderDate       = new OrderDate($row['orderDate']);
        $this->paymentDate     = new PaymentDate($row['paymentDate']);
        $this->menCount        = new MenCount($row['menCount']);
        $this->womenCount      = new WomenCount($row['womenCount']);
        $this->customerSegment = new CustomerSegment($row['customerSegment']);
        $this->salesType       = new SalesType($row['salesType']);
    }
}

