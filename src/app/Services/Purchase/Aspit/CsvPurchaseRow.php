<?php

namespace App\Services\Purchase\Aspit;

use App\Constants\Common;
use App\Exceptions\SkipImportException;
use App\Services\Company\FetchesCompanyInfo;
use App\Services\Purchase\Aspit\Wrappers\Purchase;
use Exception;
use Illuminate\Support\Facades\Log;

class CsvPurchaseRow
{

    private Purchase           $purchase;
    private FetchesCompanyInfo $companyInfo;

    /**
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row, $companyInfo)
    {
        $this->companyInfo = $companyInfo;
        $storeCds          = $this->companyInfo->getValueArrayFromColumn(FetchesCompanyInfo::TABLE_STORE, 'purchase_store_cd');
        $supplierCds       = $this->companyInfo->getValueArrayFromColumn(FetchesCompanyInfo::TABLE_PURCHASE_SUPPLIER, 'supplier_cd');
        $companyCd         = $this->companyInfo->getCompanyValue('purchase_company_cd');


        if (count($row) !== Common::ASPIT_CSV_ROW_COUNT) {
            Log::info(print_r($row, true));
            throw new Exception('不正な列数を持つ連携ファイルが検出されました。正しい桁列数:' . Common::ASPIT_CSV_ROW_COUNT . ' 検出された列数:' . count($row));
        }

        if ($row[0] !== $companyCd) {
            Log::info(print_r($row, true));
            throw new Exception("不正な会社コードが検出されました。正しい会社コード:{$companyCd} 検出された会社コード:{$row[0]}");
        }

        $this->purchase = new Purchase([
                                           'storeCd'       => $row[6],
                                           'supplierCd'    => $row[10],
                                           'acquisitionCd' => $row[16],
                                           'slipNumber'    => $row[18],
                                           'date'          => $row[24],
                                           'categoryCd'    => $row[26],
                                           'amount'        => $row[45],
                                           'storeCds'      => $storeCds,
                                           'supplierCds'   => $supplierCds,
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
     * companyInfoインスタンスを参照して、store_idを取得する
     * @return int
     * @throws Exception
     */
    public function getStoreId(): int
    {
        $slip    = $this->purchase->getValues();
        $storeCd = $slip['storeCd'];
        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'purchase_store_cd', $storeCd);
    }

    /**
     * Purchaseに関するデータを取得する
     * @return array
     * @throws Exception
     */
    public function getPurchaseForRegistration(): array
    {
        $purchase          = $this->purchase->getValues();

        $expenseCategoryId = $this->getExpenseCategoryId($purchase['categoryCd']);
        $storeId    = $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'purchase_store_cd', $purchase['storeCd']);
        $supplierId = $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_PURCHASE_SUPPLIER, 'supplier_cd', $purchase['supplierCd']);

        return [
            'store_id'             => $storeId,
            'purchase_supplier_id' => $supplierId,
            'expense_category_id'  => $expenseCategoryId,
            'slip_number'          => $purchase['slipNumber'],
            'amount'               => $purchase['amount'],
            'date'                 => $purchase['date'],
        ];
    }

    /**
     * storeInfoインスタンスを参照しexpense_category_idを取得する
     * @throws Exception
     */
    private function getExpenseCategoryId(string $categoryCd): int
    {
        $expenseCategoryCode = $this->getExpenseCategoryCode($categoryCd);

        if (!$expenseCategoryCode) {
            $validCodes = implode(', ', array_column(Common::ASPIT_CATEGORIES, 'category_cd'));
            throw new Exception("カテゴリコードに誤りがあります。 値:{$categoryCd} 許可された値:(" . $validCodes . ')');
        }

        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_EXPENSE_CATEGORY, 'cat_cd', $expenseCategoryCode);
    }

    /**
     * 定数ASPIT_CATEGORIESを参照してexpense_category_codeを取得する
     * @param string $categoryCd
     * @return string|null
     */
    private function getExpenseCategoryCode(string $categoryCd): ?string
    {
        foreach (Common::ASPIT_CATEGORIES as $category) {
            if (isset($category['category_cd']) && $category['category_cd'] == $categoryCd) {
                return $category['expense_category_code'];
            }
        }

        return null;
    }

}

