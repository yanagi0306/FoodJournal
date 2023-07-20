<?php

namespace App\Services\Order\Usen;

use App\Constants\UsenConstants;
use App\Exceptions\SkipImportException;
use App\Models\Store;
use App\Services\Company\FetchesCompanyInfo;
use ArrayIterator;
use Exception;
use Illuminate\Support\Facades\Log;
use IteratorAggregate;

/**
 * Class csvCollection(Order)
 * 注文情報のコレクション
 * @package App\Collections
 */
class CsvOrderCollection implements IteratorAggregate
{
    private array              $orders        = [];
    private array              $orderProducts = [];
    private FetchesCompanyInfo $companyInfo;
    private string             $skipMessage   = '';

    /**
     * @param array              $csvOrderArray
     * @param FetchesCompanyInfo $companyInfo
     * @throws Exception
     */
    public function __construct(array $csvOrderArray, FetchesCompanyInfo $companyInfo)
    {
        $this->companyInfo = $companyInfo;
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
                if (UsenConstants::USEN_CSV_SKIP_ROW >= $lineNumber) {
                    throw new SkipImportException('ヘッダー行のためスキップ');
                }

                $productMaster = new CsvOrderProductMasterRow($row, $this->companyInfo->getCompanyValue('id'));
                $csvOrder      = new CsvOrderRow($row, $this->companyInfo);

                // 商品番号ごとにCsvOrderProductMasterRowを格納
                $productCd = $productMaster->getProductCd();
                if (!isset($this->orderProducts[$productCd])) {
                    $this->orderProducts[$productCd] = $productMaster;
                }

                // 伝票番号ごとにCsvOrderRowを格納
                $slipNumber = $csvOrder->getSlipNumber();
                if (!isset($this->orders[$slipNumber])) {
                    $this->orders[$slipNumber][] = $csvOrder;
                }

            } catch (SkipImportException $e) {
                Log::info(($lineNumber) . "行目取込処理をスキップします。" . $e->getMessage());
                $this->skipMessage .= $e->getMessage();
                continue;
            } catch (Exception $e) {
                throw new Exception($lineNumber . '行目取込処理に失敗しました:' . $e->getMessage());
            }
        }
    }

    /**
     * 注文商品マスタ一覧を取得する
     * @return array
     */
    public function getOrderProducts(): array
    {
        return $this->orderProducts;
    }

    /**
     * 注文情報一覧を取得する
     * @return array
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * モデルを参照してorder_store_cdを取得する
     * @param array $storeIds
     * @return array
     * @throws Exception
     */
    private function getStoreCds(array $storeIds): array
    {
        try {
            return Store::whereIn('id', $storeIds)->where('is_closed', null)->pluck('order_store_cd')->toArray();
        } catch (Exception $e) {
            throw new Exception('order_store_cdの取得に失敗しました: ' . $e->getMessage());
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
