<?php

namespace App\Services\Purchase\Aspit;

use App\Constants\Common;
use App\Exceptions\SkipImportException;
use App\Services\Company\FetchesCompanyInfo;
use ArrayIterator;
use Exception;
use Illuminate\Support\Facades\Log;
use IteratorAggregate;

class CsvPurchaseCollection implements IteratorAggregate
{
    private array              $purchases   = [];
    private FetchesCompanyInfo $companyInfo;
    private string             $skipMessage = '';

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
                if (Common::ASPIT_CSV_SKIP_ROW >= $lineNumber) {
                    throw new SkipImportException('ヘッダー行のためスキップ');
                }

                $csvPurchase = new CsvPurchaseRow($row, $this->companyInfo);

                // 伝票番号ごとにCsvPurchaseRowを格納
                $slipNumber = $csvPurchase->getSlipNumber();
                if (!isset($this->orders[$slipNumber])) {
                    $this->purchases[$slipNumber] = $csvPurchase;
                }

            } catch (SkipImportException $e) {
                Log::info(($lineNumber) . '行目取込処理をスキップします。' . $e->getMessage());
                $this->skipMessage .= $e->getMessage();
                continue;
            } catch (Exception $e) {
                throw new Exception($lineNumber . '行目取込処理に失敗しました:' . $e->getMessage());
            }

            Log::info($lineNumber . '行目取込処理に成功');
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
     * IteratorAggregateインタフェースに必要なgetIteratorメソッドの実装
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->purchases);
    }
}
