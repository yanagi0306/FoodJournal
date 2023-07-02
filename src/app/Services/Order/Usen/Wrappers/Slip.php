<?php

namespace App\Services\Order\Usen\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Order\Usen\Wrappers\Slip\CustomerTypeName;
use App\Services\Order\Usen\Wrappers\Slip\MenCount;
use App\Services\Order\Usen\Wrappers\Slip\OrderDate;
use App\Services\Order\Usen\Wrappers\Slip\PaymentDate;
use App\Services\Order\Usen\Wrappers\Slip\SalesType;
use App\Services\Order\Usen\Wrappers\Slip\SlipNumber;
use App\Services\Order\Usen\Wrappers\Slip\StoreCd;
use App\Services\Order\Usen\Wrappers\Slip\WomenCount;
use Exception;

/**
 * 注文伝票クラス(親)
 */
class Slip extends ColumnGroupBase
{
    protected StoreCd          $storeCd;
    protected SlipNumber       $slipNumber;
    protected OrderDate        $orderDate;
    protected PaymentDate      $paymentDate;
    protected MenCount         $menCount;
    protected WomenCount       $womenCount;
    protected CustomerTypeName $customerTypeName;
    protected SalesType        $salesType;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {

        $this->storeCd          = new StoreCd($row['storeCd'], $row['storeCds']);
        $this->slipNumber       = new SlipNumber($row['slipNumber'], '伝票番号');
        $this->orderDate        = new OrderDate($row['orderDate'], '伝票発行日');
        $this->paymentDate      = new PaymentDate($row['paymentDate'], '伝票処理日');
        $this->menCount         = new MenCount($row['menCount'], '客数男性');
        $this->womenCount       = new WomenCount($row['womenCount'], '客数女性');
        $this->customerTypeName = new CustomerTypeName($row['customerTypeName'], '客層名');
        $this->salesType        = new SalesType($row['salesType'], '販売形態');
    }

    /**
     * 伝票番号を取得する
     * @return string
     */
    public function getSlipNumber(): string
    {
        return $this->slipNumber->getValue();
    }

    /**
     * 店舗コードを取得する
     * @return string
     */
    public function getStoreCd(): string
    {
        return $this->storeCd->getValue();
    }
}

