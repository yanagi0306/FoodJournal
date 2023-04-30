<?php

namespace app\Services\Aspit;

use App\Exceptions\SkipImportException;
use App\Models\Order;
use app\Services\Usen\CsvOrderRow;
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
     *
     * @param array $csvData
     * @throws Exception
     */
    public function __construct(array $csvData)
    {
        $this->addOrdersFromCsv($csvData);
    }

    /**
     * CsvDataを元にOrderオブジェクトを生成し、OrderCollectionに格納する
     *
     * @param array $csvData
     * @throws Exception
     */
    private function addOrdersFromCsv(array $csvData): void
    {
        foreach ($csvData as $key => $row) {
            try {
                Log::info(($key+1) . '行目取込開始');
                $csvOrder = new CsvOrderRow($row);
                $this->orders[] = $csvOrder;
            } catch (SkipImportException $e) {
                Log::info($e->getMessage());
                continue;
            }
            Log::info(($key+1) . '行目取込完了');
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
