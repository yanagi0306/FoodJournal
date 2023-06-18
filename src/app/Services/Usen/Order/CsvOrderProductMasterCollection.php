<?php

namespace App\Services\Usen\Order;

use App\Constants\Common;
use App\Exceptions\SkipImportException;
use App\Models\Store;
use ArrayIterator;
use Exception;
use Illuminate\Support\Facades\Log;
use IteratorAggregate;

/**
 * Class csvCollection(OrderProductMaster)
 * 注文商品マスタのコレクション
 * @package App\Collections
 */
class CsvOrderProductMasterCollection implements IteratorAggregate
{

    private array  $products    = [];
    private int    $companyId;
    private array  $storeCds;
    private string $skipMessage = '';

    /**
     * @param array $csvOrderArray
     * @param int   $companyId
     * @param array $storeIds
     * @throws Exception
     */
    public function __construct(array $csvOrderArray, int $companyId, array $storeIds)
    {
        $this->companyId = $companyId;
        $this->storeCds  = $this->getStoreCds($storeIds);
        $this->addCollection($csvOrderArray);
    }

    /**
     * CsvDataを元にOrderProductMasterオブジェクトを生成する
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
                if (Common::USEN_CSV_SKIP_ROW >= $lineNumber) {
                    throw new SkipImportException('ヘッダー行のためスキップ');
                }

                $productMaster = new CsvOrderProductMasterRow($row, $this->companyId);

                // 店舗コードの検証
                $storeCd = $productMaster->getStoreCd();
                if (!in_array($storeCd, $this->storeCds, true)) {
                    throw new Exception("会社に所属しない店舗コードが検出されました。 店舗コード:({$storeCd})");
                }

                // productsインスタンスに追加
                $productCd                  = $productMaster->getProductCd();
                $this->products[$productCd] = $productMaster;

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
     * モデルを参照してorder_store_cdを取得する
     * @param array $storeIds
     * @return array
     * @throws Exception
     */
    private function getStoreCds(array $storeIds): array
    {
        try {
            return Store::whereIn('id', $storeIds)->pluck('order_store_cd')->toArray();
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
        return new ArrayIterator($this->products);
    }


}
