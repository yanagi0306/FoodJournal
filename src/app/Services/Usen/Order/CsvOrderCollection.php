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
    private array  $orders      = [];
    private int    $companyId;
    private string $skipMessage = '';

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
            try {
                $csvOrder = new CsvOrderRow($row, $this->companyId);

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
     * スキップした項目を取得する
     * @return string
     */
    public function getSkipMessage(): string
    {
        return $this->skipMessage;
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
