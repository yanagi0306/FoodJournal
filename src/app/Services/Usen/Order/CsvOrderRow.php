<?php

namespace app\Services\Usen;

use App\Exceptions\SkipImportException;
use app\Services\Usen\Order\Wrappers\Product;
use app\Services\Usen\Order\Wrappers\SkipDecision;
use app\Services\Usen\Order\Wrappers\Slip;
use app\Services\Usen\Order\Wrappers\Payment;

class CsvOrderRow
{
    private Payment      $payment;
    private Product      $product;
    private SkipDecision $skipDecision;
    private Slip         $slip;

    /**
     * @throws SkipImportException
     */
    public function __construct(array $row)
    {
        $this->payment = new Payment([
            'cash' => $row[39],
            'creditCard' => $row[40],
            'points' => $row[41],
            'electronicMoney' => $row[42],
            'giftCertNoChangeAmount' => $row[43],
            'giftCertNoChangeDiff' => $row[44],
            'giftCertWithChangeAmount' => $row[45],
            'giftCertWithChangeCashChange' => $row[46],
            'other' => $row[47],
        ]);

        $this->product = new Product([
            'productCategory1' => $row[66],
            'productCategory2' => $row[67],
            'productCategory3' => $row[68],
            'productCategory4' => $row[69],
            'productCategory5' => $row[70],
            'product' => $row[72],
            'productOption' => $row[73],
            'unitPrice' => $row[76],
            'unitCost' => $row[78],
            'quantity' => $row[81],
        ]);

        $this->skipDecision = new SkipDecision([
            'aggregateFlag' => $row[0],
            'orderStatus' => $row[90],
            'paymentStatus' => $row[11],
        ]);

        $this->slip = new Slip([
            'storeCd' => $row[1],
            'slipNumber' => $row[2],
            'orderDate' => $row[12],
            'paymentDate' => $row[13],
            'menCount' => $row[19],
            'womenCount' => $row[20],
            'customerSegment' => $row[64],
            'salesType' => $row[91],
        ]);
    }
}



