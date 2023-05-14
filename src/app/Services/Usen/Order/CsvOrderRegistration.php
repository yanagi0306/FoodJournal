<?php

namespace App\Services\Usen\Order;

use Exception;
use Throwable;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CsvOrderRegistration
{
    private int $orderCnt = 0;
    private int $orderPaymentCnt = 0;
    private int $orderProductCnt = 0;

    /**
     * 作成したOrderCollectionをデータベースに保存
     * @param CsvOrderCollection $csvOrderCollection 伝票番号をキーとするOrderCollectionの連想配列
     * @throws Exception
     * @throws Throwable
     */
    public function saveCsvCollection(CsvOrderCollection $csvOrderCollection): void
    {
        try {
            DB::beginTransaction();

            // 伝票番号ごとに処理
            foreach ($csvOrderCollection as $slipNumber => $csvOrderRowArray) {
                $infoMessage = "[伝票番号:{$slipNumber}]";
                Log::info("{$infoMessage}登録処理開始");

                // OrderCollectionをデータベースに保存
                if (!$this->saveOrders($csvOrderRowArray)) {
                    continue;
                }
                $this->orderCnt++;
                Log::info("{$infoMessage}登録処理完了");
            }
            DB::commit();
            Log::info("Orderテーブル登録:{$this->orderCnt}件");
            Log::info("OrderPaymentテーブル登録:{$this->orderPaymentCnt}件");
            Log::info("OrderProductテーブル登録:{$this->orderProductCnt}件");
        } catch (Exception $e) {
            // 例外が発生した場合、トランザクションをロールバック処理終了
            DB::rollBack();
            throw new Exception($e);
        }
    }

    /**
     * OrderCollectionをデータベースに保存
     * @param iterable|CsvOrderRow[] $csvOrderRowArray 同一伝票番号のCsvOrderRowの入った配列
     * @return bool 登録の成功/失敗
     * @throws Exception
     */
    private function saveOrders(array $csvOrderRowArray): bool
    {
        // CsvOrderRow クラスから伝票番号 店舗IDを取得
        $slipNumber = $csvOrderRowArray[0]->getSlipNumber();
        $storeId    = $csvOrderRowArray[0]->getStoreId();

        // 伝票番号を基にOrderテーブルを検索し、既存の注文があるか確認
        $existingOrder = Order::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の注文がない場合のみ、Orderをデータベースに登録
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
     * @return int
     * @throws Exception
     */
    private function createOrder(CsvOrderRow $csvOrderRow): int
    {
        $orderData    = $csvOrderRow->getOrderForRegistration();
        $createdOrder = Order::create($orderData);

        $this->orderCnt++;
        return $createdOrder->id;
    }

    /**
     * OrderPayment データをデータベースに登録
     * @param CsvOrderRow $csvOrderRow
     * @param int         $orderId
     * @return void
     * @throws Exception
     */
    private function createOrderPayments(CsvOrderRow $csvOrderRow, int $orderId): void
    {
        $orderPaymentsData = $csvOrderRow->getOrderPaymentsForRegistration($orderId);
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
        $orderProductData = $csvOrderRow->getOrderProductForRegistration($orderId);
        OrderProduct::create($orderProductData);
        $this->orderProductCnt++;
    }
}
