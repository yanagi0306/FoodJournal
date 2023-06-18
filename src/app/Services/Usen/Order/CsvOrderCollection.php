<?php

namespace App\Services\Usen\Order;

use App\Constants\Common;
use App\Exceptions\SkipImportException;
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
    private array  $orders      = [];
    private int    $companyId;
    private array  $orderProducts;
    private string $skipMessage = '';

    /**
     * @param array $csvOrderArray
     * @param int   $companyId
     * @param array $orderProducts
     * @throws Exception
     */
    public function __construct(array $csvOrderArray, int $companyId, array $orderProducts)
    {
        $this->companyId     = $companyId;
        $this->orderProducts = $orderProducts;
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
                if (Common::USEN_CSV_SKIP_ROW >= $lineNumber) {
                    throw new SkipImportException('ヘッダー行のためスキップ');
                }

                $csvOrder = new CsvOrderRow($row, $this->companyId, $this->orderProducts);

                // 伝票番号ごとに配列に格納
                $slipNumber = $csvOrder->getSlipNumber();

                if (!isset($this->orders[$slipNumber])) {
                    $this->orders[$slipNumber] = [];
                }

                $this->orders[$slipNumber][] = $csvOrder;

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
     * IteratorAggregateインタフェースに必要なgetIteratorメソッドの実装
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->orders);
    }
}
