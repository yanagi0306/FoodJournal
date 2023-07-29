<?php

namespace App\Services\Purchase\Aspit;

use App\Constants\AspitConstants;
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


        if (count($row) !== AspitConstants::ASPIT_CSV_ROW_COUNT) {
            Log::info(print_r($row, true));
            throw new Exception('不正な列数を持つ連携ファイルが検出されました。正しい桁列数:' . AspitConstants::ASPIT_CSV_ROW_COUNT . ' 検出された列数:' . count($row));
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
        return $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'purchase_store_cd', $storeCd)['id'];
    }

    /**
     * Purchaseに関するデータを取得する
     * @return array
     * @throws Exception
     */
    public function getPurchaseForRegistration(): array
    {
        $purchase = $this->purchase->getValues();

        $expenseCategoryId = $this->getExpenseCategoryId($purchase['categoryCd']);
        $storeId           = $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'purchase_store_cd', $purchase['storeCd'])['id'];
        $supplierId        = $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_PURCHASE_SUPPLIER, 'supplier_cd', $purchase['supplierCd'])['id'];

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
        $expenseCategory = $this->convertCategoryCodeFromAspit($categoryCd);

        if (!$expenseCategory) {
            $validCodes = implode(', ', array_column(AspitConstants::CATEGORY_MAPS_FROM_ASPIT_TO_DB, 'aspit_category_cd'));
            throw new Exception("カテゴリコードに誤りがあります。 値:{$categoryCd} 許可された値:(" . $validCodes . ')');
        }

        return $this->companyInfo->getChildRecordFromParentColumnValue(FetchesCompanyInfo::TABLE_PARENT_EXPENSE_CATEGORY, FetchesCompanyInfo::TABLE_EXPENSE_CATEGORY, 'cat_cd', $expenseCategory['parent_expense_category_cd'], 'cat_cd', $expenseCategory['expense_category_cd'])['id'];
    }

    /**
     * 定数CATEGORY_MAPS_FROM_ASPIT_TO_DBを参照してAspitカテゴリコード→親カテゴリ、子カテゴリの配列に変換
     * @param string $categoryCd
     * @return array|null
     */
    private function convertCategoryCodeFromAspit(string $categoryCd): ?array
    {
        foreach (AspitConstants::CATEGORY_MAPS_FROM_ASPIT_TO_DB as $category) {
            if (isset($category['aspit_category_cd']) && $category['aspit_category_cd'] == $categoryCd) {
                return $category;
            }
        }

        return null;
    }

}

