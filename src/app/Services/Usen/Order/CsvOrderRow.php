<?php

namespace App\Services\Usen\Order;

use Exception;
use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\Product;
use App\Services\Usen\Order\Wrappers\SkipDecision;
use App\Services\Usen\Order\Wrappers\Slip;
use App\Services\Usen\Order\Wrappers\Payment;
use Illuminate\Support\Facades\Log;

class CsvOrderRow
{
    private Payment $payment;
    private Product $product;
    private SkipDecision $skipDecision;
    private Slip $slip;

    /**
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->payment = new Payment([
            'cash'                     => $row[39],
            'creditCard'               => $row[40],
            'points'                   => $row[41],
            'electronicMoney'          => $row[42],
            'giftCertNoChangeAmount'   => $row[43],
            'giftCertNoChangeDiff'     => $row[44],
            'giftCertWithChangeAmount' => $row[45],
            'giftCertWithChangeDiff'   => $row[46],
            'otherPayment'             => $row[47],
        ]);

        $this->product = new Product([
            'category1'     => $row[66],
            'category2'     => $row[67],
            'category3'     => $row[68],
            'category4'     => $row[69],
            'category5'     => $row[70],
            'product'       => $row[72],
            'productOption' => $row[73],
            'unitPrice'     => $row[76],
            'unitCost'      => $row[78],
            'quantity'      => $row[81],
        ]);

        $this->skipDecision = new SkipDecision([
            'aggregateFlag' => $row[0],
            'orderStatus'   => $row[90],
            'paymentStatus' => $row[11],
        ]);

        $this->slip = new Slip([
            'storeCd'         => $row[1],
            'slipNumber'      => $row[2],
            'orderDate'       => $row[12],
            'paymentDate'     => $row[13],
            'menCount'        => $row[19],
            'womenCount'      => $row[20],
            'customerSegment' => $row[64],
            'salesType'       => $row[91],
        ]);
    }

    // 各クラスのインスタンスを取得するためのgetterを追加
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getSkipDecision(): SkipDecision
    {
        return $this->skipDecision;
    }

    public function getSlip(): Slip
    {
        return $this->slip;
    }
}



