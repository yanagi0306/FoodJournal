<?php

namespace app\Services\Usen;

use App\Traits\CsvTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CsvOrderUploadService
{
    use CsvTrait;

    protected array $csvArray;
    protected CsvOrderCollection $csvOrderCollection;

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     *
     * @param $csv
     * @return bool 登録の成功/失敗
     * @throws Exception
     */
    public function uploadOrder($csv): bool
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($csv);

        // 配列からCsvOrderCollectionを作成
        $this->processCsvArray($csvArray);

        // 作成したOrderCollectionをデータベースに保存
        return $this->saveOrdersArray();
    }

    /**
     * CSVデータの配列を処理して、OrderCollectionを作成します
     *
     * @param array $csvArray CSVデータの配列
     * @return CsvOrderCollection 注文のコレクション
     */
    public function processCsvArray(array $csvArray): CsvOrderCollection
    {
        $this->csvOrderCollection = new csvOrderCollection($csvArray);

        return $this->csvOrderCollection;
    }

    /**
     * 作成したOrderCollectionをデータベースに保存
     *
     * @param array $ordersArray 伝票番号をキーとするOrderCollectionの連想配列
     * @return bool 登録の成功/失敗
     * @throws Exception
     * @throws Throwable
     */
    private function saveOrdersArray(array $ordersArray): bool
    {
        try {

            // 伝票番号ごとに処理
            foreach ($this->csvOrderCollection as $invoiceNumber => $orderCollection) {
                // トランザクション開始
                DB::beginTransaction();

                // OrderCollectionをデータベースに保存
                if (!$this->saveOrderCollection()) {
                    // 保存に失敗した場合、トランザクションをロールバック
                    DB::rollBack();
                    return false;
                }
            }

            // すべてのOrderCollectionが正常に保存された場合、トランザクションをコミット
            DB::commit();
            return true;
        } catch (Exception $e) {
            // 例外が発生した場合、トランザクションをロールバック
            DB::rollBack();
            return false;
        }
    }

    /**
     * OrderCollectionをデータベースに保存
     *
     * @return bool 登録の成功/失敗
     */
    private function saveOrderCollection(): bool
    {
        // OrderCollectionから保存するべきデータを取得し、データベースに登録
        // 実際のデータベース登録処理をここに記述してください

        // 登録が成功した場合はtrue、失敗した場合はfalseを返す
        return true;
    }

}
