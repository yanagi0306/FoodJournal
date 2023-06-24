<?php

namespace App\Services\Purchase\Aspit\Wrappers;

use App\Exceptions\SkipImportException;
use App\Services\Base\CsvWrappers\ColumnGroupBase;
use App\Services\Purchase\Aspit\Wrappers\Purchase\AcquisitionCd;
use App\Services\Purchase\Aspit\Wrappers\Purchase\Amount;
use App\Services\Purchase\Aspit\Wrappers\Purchase\CategoryCd;
use App\Services\Purchase\Aspit\Wrappers\Purchase\PurchaseDate;
use App\Services\Purchase\Aspit\Wrappers\Purchase\SlipNumber;
use App\Services\Purchase\Aspit\Wrappers\Purchase\StoreCd;
use App\Services\Purchase\Aspit\Wrappers\Purchase\SupplierCd;
use Exception;

/**
 * 仕入クラス
 */
class Purchase extends ColumnGroupBase
{
    protected StoreCd       $storeCd;
    protected SupplierCd    $supplierCd;
    protected AcquisitionCd $acquisitionCd;
    protected SlipNumber    $slipNumber;
    protected PurchaseDate  $date;
    protected CategoryCd    $categoryCd;
    protected Amount        $amount;

    /**
     * @param array $row
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row)
    {
        $this->storeCd       = new StoreCd($row['storeCd'], $row['storeCds'], '店舗コード');
        $this->supplierCd    = new SupplierCd($row['supplierCd'], $row['supplierCds'], '伝票発行日');
        $this->acquisitionCd = new AcquisitionCd($row['acquisitionCd'], '科目コード');
        $this->slipNumber    = new SlipNumber($row['slipNumber'], '伝票番号');
        $this->date          = new PurchaseDate($row['date'], '仕入日時');
        $this->categoryCd    = new CategoryCd($row['categoryCd'], '分類コード');
        $this->amount        = new Amount($row['amount'], '仕入価格');
    }
}

