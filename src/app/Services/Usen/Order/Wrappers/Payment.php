<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\Payment\Cash;
use App\Services\Usen\Order\Wrappers\Payment\CreditCard;
use App\Services\Usen\Order\Wrappers\Payment\ElectronicMoney;
use App\Services\Usen\Order\Wrappers\Payment\GiftCertNoChange;
use App\Services\Usen\Order\Wrappers\Payment\GiftCertWithChange;
use App\Services\Usen\Order\Wrappers\Payment\OtherPayment;
use App\Services\Usen\Order\Wrappers\Payment\Points;
use Exception;

/**
 * 注文支払クラス
 */
class Payment
{
    private Cash               $cash;
    private CreditCard         $creditCard;
    private Points             $points;
    private ElectronicMoney    $electronicMoney;
    private GiftCertNoChange   $giftCertNoChange;
    private GiftCertWithChange $giftCertWithChange;
    private OtherPayment       $otherPayment;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->cash               = new Cash($row['cash'], '現金');
        $this->creditCard         = new CreditCard($row['creditCard'], 'クレジット');
        $this->points             = new Points($row['points'], 'ポイント');
        $this->electronicMoney    = new ElectronicMoney($row['electronicMoney'], '電子マネー');
        $this->otherPayment       = new OtherPayment($row['otherPayment'], 'その他支払方法');
        $this->giftCertNoChange   = new GiftCertNoChange($row['giftCertNoChangeAmount'], $row['giftCertNoChangeDiff']);
        $this->giftCertWithChange = new GiftCertWithChange($row['giftCertWithChangeAmount'], $row['giftCertWithChangeDiff']);
    }
}


