<?php

namespace App\Services\Purchase\Aspit;

use App\Constants\Common;
use App\Exceptions\SkipImportException;
use App\Models\Company;
use App\Models\PurchaseSupplier;
use App\Models\Store;
use ArrayIterator;
use Exception;
use Illuminate\Support\Facades\Log;
use IteratorAggregate;

class CsvPurchaseCollection implements IteratorAggregate
{
    private array  $purchases   = [];
    private int    $companyId;
    private string $companyCd;
    private array  $storeCds;
    private array  $supplierCds;
    private string $skipMessage = '';

    /**
     * @param array $csvOrderArray
     * @param int   $companyId
     * @param array $storeIds
     * @throws Exception
     */
    public function __construct(array $csvOrderArray, int $companyId, array $storeIds)
    {
        $this->companyId   = $companyId;
        $this->storeCds    = $this->getStoreCds($storeIds);
        $this->companyCd   = $this->getCompanyCd($companyId);
        $this->supplierCds = $this->getSupplierCds($companyId);
        $this->addCollection($csvOrderArray);
    }

    /**
     * CsvDataを元にOrderオブジェクトを生成し、伝票番号keyにOrderCollectionに格納する
     * @param array $csvOrderArray
     * @return void
     * @throws Exception
     */
    private function addCollection(array $csvOrderArray): void
    {
        $lineNumber = 0;

        foreach ($csvOrderArray as $row) {
            try {
                $lineNumber++;
                if (Common::ASPIT_CSV_SKIP_ROW >= $lineNumber) {
                    throw new SkipImportException('ヘッダー行のためスキップ');
                }

                $csvPurchase = new CsvPurchaseRow($row, $this->companyCd, $this->storeCds, $this->supplierCds);

                // 伝票番号ごとにCsvPurchaseRowを格納
                $slipNumber = $csvPurchase->getSlipNumber();
                if (!isset($this->orders[$slipNumber])) {
                    $this->purchases[$slipNumber][] = $csvPurchase;
                }

            } catch (SkipImportException $e) {
                Log::info(($lineNumber) . '行目取込処理をスキップします。' . $e->getMessage());
                $this->skipMessage .= $e->getMessage();
                continue;
            } catch (Exception $e) {
                throw new Exception($lineNumber . '行目取込処理に失敗しました:' . $e->getMessage());
            }
        }
    }

    /**
     * 仕入情報一覧を取得する
     * @return array
     */
    public function getPurchases(): array
    {
        return $this->purchases;
    }

    /**
     * モデルを参照してpurchase_store_cdを取得する
     * @param array $storeIds
     * @return array
     * @throws Exception
     */
    private function getStoreCds(array $storeIds): array
    {
        try {
            return Store::whereIn('id', $storeIds)->where('is_closed', null)->pluck('purchase_store_cd')->toArray();
        } catch (Exception $e) {
            throw new Exception('purchase_store_cdの取得に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * モデルを参照してpurchase_company_cdを取得する
     * @param int $companyId
     * @return string
     * @throws Exception
     */
    private function getCompanyCd(int $companyId): string
    {
        try {
            return Company::where('id', $companyId)->value('purchase_company_cd');
        } catch (Exception $e) {
            throw new Exception('purchase_company_cdの取得に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * モデルを参照してcompanyIdに紐づくsupplierCdを取得する
     * @param int $companyId
     * @return array
     * @throws Exception
     */
    private function getSupplierCds(int $companyId): array
    {
        try {
            return PurchaseSupplier::where('id', $companyId)->pluck('supplier_cd')->toArray();
        } catch (Exception $e) {
            throw new Exception('supplier_cdの取得に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * IteratorAggregateインタフェースに必要なgetIteratorメソッドの実装
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->orders);
    }
}
