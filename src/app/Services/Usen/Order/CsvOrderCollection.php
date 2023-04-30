<?php

namespace app\Services\Aspit;

use App\Exceptions\SkipImportException;
use App\Models\Order;
use ArrayIterator;
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
    private CsvOrderRow $csvOrder;

    /**
     *
     * @param array $csvData
     */
    public function __construct(array $csvData)
    {
        $this->addOrdersFromCsv($csvData);
    }

    /**
     * CsvDataを元にOrderオブジェクトを生成し、OrderCollectionに格納する
     *
     * @param array $csvData
     */
    private function addOrdersFromCsv(array $csvData): void
    {
        foreach ($csvData as $row) {
            try {
                $csvOrder = new CsvOrderRow($row);
                $this->orders[] = $csvOrder;
            } catch (SkipImportException $e) {
                // Skip this row and continue with the next one
                continue;
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
