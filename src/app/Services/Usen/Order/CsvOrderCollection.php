<?php

namespace App\Services\Usen\Order;

use App\Exceptions\SkipImportException;
use App\Models\Order;
use ArrayIterator;
use Exception;
use Illuminate\Support\Facades\Log;
use IteratorAggregate;

/**
 * Class csvOrderCollection
 * 売上情報のコレクション
 * @package App\Collections
 */
class CsvOrderCollection implements IteratorAggregate
{
    /**
     * @var Order[]
     */
    private array $orders = [];

    /**
     * @param array $csvData
     * @throws Exception
     */
    public function __construct(array $csvData)
    {
        $this->addOrdersFromCsv($csvData);
    }

    /**
     * CsvDataを元にOrderオブジェクトを生成し、伝票番号keyにOrderCollectionに格納する
     * @param array $csvData
     * @throws Exception
     */
    private function addOrdersFromCsv(array $csvData): void
    {
        $lineNumber = 0;

        foreach ($csvData as $row) {
            $lineNumber++;
            Log::info($lineNumber . '行目取込開始');

            try {
                $csvOrder = new CsvOrderRow($row);

                // 伝票番号ごとに配列に格納
                $slipNumber                = $csvOrder->getSlip()->getSlipNumber();
                $this->orders[$slipNumber] = $csvOrder;

            } catch (SkipImportException $e) {
                Log::info($e->getMessage());
                continue;
            }

            Log::info(($lineNumber) . '行目取込完了');
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
