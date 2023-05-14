<?php

namespace App\Services\Usen\Order;

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
    private array $orders = [];
    private int $companyId;

    /**
     * @param array $csvData
     * @param int   $companyId
     * @throws Exception
     */
    public function __construct(array $csvData, int $companyId)
    {
        $this->companyId = $companyId;
        $this->addOrdersFromCsv($csvData);
    }

    /**
     * CsvDataを元にOrderオブジェクトを生成し、伝票番号keyにOrderCollectionに格納する
     * @param array $csvData
     * @return void
     * @throws Exception
     */
    private function addOrdersFromCsv(array $csvData): void
    {
        $lineNumber = 0;

        foreach ($csvData as $row) {
            $lineNumber++;
            Log::info($lineNumber . '行目取込開始');

            try {
                $csvOrder = new CsvOrderRow($row, $this->companyId);

                // 伝票番号ごとに配列に格納
                $slipNumber = $csvOrder->getSlipNumber();

                if (!isset($this->orders[$slipNumber])) {
                    $this->orders[$slipNumber] = [];
                }

                $this->orders[$slipNumber][] = $csvOrder;

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
