<?php

namespace App\Services\Usen\Order\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Usen\Order\Wrappers\Slip\CustomerTypeName;
use App\Services\Usen\Order\Wrappers\Slip\MenCount;
use App\Services\Usen\Order\Wrappers\Slip\OrderDate;
use App\Services\Usen\Order\Wrappers\Slip\PaymentDate;
use App\Services\Usen\Order\Wrappers\Slip\SalesType;
use App\Services\Usen\Order\Wrappers\Slip\SlipNumber;
use App\Services\Usen\Order\Wrappers\Slip\StoreCd;
use App\Services\Usen\Order\Wrappers\Slip\WomenCount;
use Exception;
use Illuminate\Support\Facades\Log;

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

        $this->storeCd          = new StoreCd($row['storeCd'], '店舗コード');
        $this->slipNumber       = new SlipNumber($row['slipNumber'], '伝票番号');
        $this->orderDate        = new OrderDate($row['orderDate'], '伝票発行日');
        $this->paymentDate      = new PaymentDate($row['paymentDate'], '伝票処理日');
        $this->menCount         = new MenCount($row['menCount'], '客数男性');
        $this->womenCount       = new WomenCount($row['womenCount'], '客数女性');
        $this->customerTypeName = new CustomerTypeName($row['customerTypeName'], '客層名');
        $this->salesType        = new SalesType($row['salesType'], '販売形態');
    }
}

