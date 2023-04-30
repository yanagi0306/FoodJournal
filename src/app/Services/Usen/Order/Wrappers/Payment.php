<?php

namespace app\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use Exception;
use app\Services\Usen\Order\Wrappers\Payment\Cash;
use app\Services\Usen\Order\Wrappers\Payment\CreditCard;
use app\Services\Usen\Order\Wrappers\Payment\ElectronicMoney;
use app\Services\Usen\Order\Wrappers\Payment\GiftCertNoChange;
use app\Services\Usen\Order\Wrappers\Payment\GiftCertWithChange;
use app\Services\Usen\Order\Wrappers\Payment\OtherPayment;
use app\Services\Usen\Order\Wrappers\Payment\Points;


/**
 *
 */
class Payment
{
    private Cash $cash;
    private CreditCard $creditCard;
    private Points $points;
    private ElectronicMoney $electronicMoney;
    private GiftCertNoChange $giftCertNoChange;
    private GiftCertWithChange $giftCertWithChange;
    private OtherPayment $otherPayment;


    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->cash = new Cash($row['cash']);
        $this->creditCard = new CreditCard($row['creditCard']);
        $this->points = new Points($row['points']);
        $this->electronicMoney = new ElectronicMoney($row['electronicMoney']);
        $this->otherPayment = new OtherPayment($row['otherPayment']);
        $this->giftCertNoChange = new GiftCertNoChange($row['giftCertNoChangeAmount'], $row['giftCertNoChangeDiff']);
        $this->giftCertWithChange = new GiftCertWithChange($row['giftCertWithChangeAmount'], $row['giftCertWithChangeDiff']);
    }
}


