<?php

namespace App\Services\Usen\Order;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use App\Traits\CsvTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CsvOrderUploadService
{
    use CsvTrait;

    private UploadedFile $uploadFile;
    private int $companyId;
    private int $orderCnt = 0;
    private int $orderPaymentCnt = 0;
    private int $orderProductCnt = 0;

    public function __construct($uploadFile, $companyId)
    {
        $this->uploadFile = $uploadFile;
        $this->companyId  = $companyId;
    }

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     * @return bool 登録の成功/失敗
     * @throws Exception|Throwable
     */
    public function processCsv(): bool
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($this->uploadFile);

        // 配列からCsvOrderCollectionを作成
        $csvOrderCollection = new csvOrderCollection($csvArray, $this->companyId);

        // 作成したOrderCollectionをデータベースに保存
        $this->saveOrderCollection($csvOrderCollection);
    }

    /**
     * 作成したOrderCollectionをデータベースに保存
     * @param CsvOrderCollection $csvOrderCollection 伝票番号をキーとするOrderCollectionの連想配列
     * @return bool 登録の成功/失敗
     * @throws Exception
     * @throws Throwable
     */
    private function saveOrderCollection(CsvOrderCollection $csvOrderCollection): bool
    {
        try {
            // 伝票番号ごとに処理
            foreach ($csvOrderCollection as $slipNumber => $csvOrderRowArray) {
                $infoMessage = "[伝票番号:{$slipNumber}]";
                Log::info("{$infoMessage}登録処理開始");
                // トランザクション開始
                DB::beginTransaction();

                // OrderCollectionをデータベースに保存
                if (!$this->saveOrders($csvOrderRowArray)) {
                    Log::error("{$infoMessage}ロールバックします");
                    DB::rollBack();
                    continue;
                }

                DB::commit();
                $this->orderCnt++;
                Log::info("{$infoMessage}登録処理完了");
            }
        } catch (Exception $e) {
            // 例外が発生した場合、トランザクションをロールバック処理終了
            DB::rollBack();
            return false;
        }
    }

    /**
     * OrderCollectionをデータベースに保存
     * @param array $csvOrderRowArray 同一伝票番号のCsvOrderRowの入った配列
     * @return bool 登録の成功/失敗
     */
    private function saveOrders(array $csvOrderRowArray): bool
    {
        // CsvOrderRow クラスから伝票番号を取得
        $slipNumber = $csvOrderRowArray[0]->getSlipNumber();
        $storeId    = $csvOrderRowArray[0]->getStoreId();

        // 伝票番号を基にOrderテーブルを検索し、既存の注文があるか確認
        $existingOrder = Order::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の注文がない場合のみ、Order OrderPaymentをデータベースに登録
        if (!$existingOrder) {
            $orderId = $this->createOrder($csvOrderRowArray[0]);
        } else {
            // 既存の注文がある場合、その注文のIDを使用
            $orderId = $existingOrder->id;

            // Orderテーブルの情報を更新
            $orderData = $csvOrderRowArray[0]->getOrderForRegistration();
            $existingOrder->update($orderData);

            // OrderPaymentテーブルの情報を削除
            OrderPayment::where('order_id', $orderId)->delete();
        }
        // OrderPaymentテーブルの情報を登録
        $this->createOrderPayments($csvOrderRowArray[0], $orderId);

        // 同一伝票番号のCsvOrderRowをループで処理し、OrderProductを登録
        foreach ($csvOrderRowArray as $csvOrderRow) {
            $this->createOrderProduct($csvOrderRow, $orderId);
        }

        return true;
    }


    /**
     * Order データをデータベースに登録
     * @param CsvOrderRow $csvOrderRow
     * @return int 登録された Order の ID
     */
    private function createOrder(CsvOrderRow $csvOrderRow): int
    {
        // CsvOrderRow クラスから Order 関連のデータを取得
        $orderData = $csvOrderRow->getOrderForRegistration();
        // Order データをデータベースに登録し、登録された Order の ID を取得
        $createdOrder = Order::create($orderData);

        $this->orderCnt++;
        return $createdOrder->id;
    }

    /**
     * OrderPayment データをデータベースに登録
     * @param CsvOrderRow $csvOrderRow
     * @param int         $orderId
     * @return void
     */
    private function createOrderPayments(CsvOrderRow $csvOrderRow, int $orderId): void
    {
        // CsvOrderRow クラスから OrderPayment 関連のデータを取得
        $orderPaymentsData = $csvOrderRow->getOrderPaymentsForRegistration($orderId);
        // OrderPayment データをデータベースに登録
        foreach ($orderPaymentsData as $orderPaymentData) {
            OrderPayment::create($orderPaymentData);
            $this->orderPaymentCnt++;
        }
    }

    /**
     * OrderProduct データをデータベースに登録
     * @param CsvOrderRow $csvOrderRow
     * @param int         $orderId
     * @return void
     */
    private function createOrderProduct(CsvOrderRow $csvOrderRow, int $orderId): void
    {
        // CsvOrderRow クラスから OrderProduct 関連のデータを取得
        $orderProductData = $csvOrderRow->getOrderProductForRegistration($orderId);
        // OrderProduct データをデータベースに登録
        OrderProduct::create($orderProductData);
        $this->orderProductCnt++;
    }
}
