<?php

namespace App\Services\Purchase\Aspit;

use App\Exceptions\SkipImportException;
use App\Models\Store;
use App\Services\Purchase\Aspit\Wrappers\Purchase;
use Exception;
use Illuminate\Support\Facades\Log;

class CsvPurchaseRow
{

    private Purchase $purchase;
    private string   $companyCd;
    private array    $storeCds;
    private array    $supplierCds;

    /**
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row, $companyCd, array $storeCds, array $supplierCds, $rowCount = 95)
    {
        $this->companyCd   = $companyCd;
        $this->storeCds    = $storeCds;
        $this->supplierCds = $supplierCds;

        if (count($row) !== $rowCount) {
            Log::info(print_r($row, true));
            throw new Exception("不正な列数を持つ連携ファイルが検出されました。正しい桁列数:{$rowCount} 検出された列数:" . count($row));
        }

        if ($row[0] !== $this->companyCd) {
            Log::info(print_r($row, true));
            throw new Exception("不正な会社コードが検出されました。正しい会社コード:{$this->companyCd} 検出された会社コード:{$row[0]}");
        }

        $this->purchase = new Purchase([
                                           'storeCd'       => $row[6],
                                           'supplierCd'    => $row[10],
                                           'acquisitionCd' => $row[16],
                                           'slipNumber'    => $row[18],
                                           'date'          => $row[24],
                                           'categoryCd'    => $row[26],
                                           'amount'        => $row[55],
                                           'storeCds'      => $this->storeCds,
                                           'supplierCds'   => $this->supplierCds,
                                       ]);
    }

    /**
     * 伝票番号を取得する
     * @return string
     */
    public function getSlipNumber(): string
    {
        $slip = $this->purchase->getValues();
        return $slip['slipNumber'];
    }

    /**
     * モデルを参照して、store_idを取得する
     * @param int $companyId
     * @return int
     * @throws Exception
     */
    public function getStoreId(int $companyId): int
    {
        $slip    = $this->purchase->getValues();
        $storeCd = $slip['storeCd'];
        $store   = Store::where('company_id', $companyId)->where('purchase_store_cd', $storeCd)->first();
        if (!$store) {
            throw new Exception("storeが存在しません companyId:{$companyId} storeCd:({$storeCd})");
        }
        return $store->id;
    }

}
