<?php

namespace App\Services\Usen\Order;

use Exception;
use Throwable;
use App\Models\OrderInfo;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CsvOrderRegistration
{
    private int $updatedOrderCount           = 0;
    private int $registeredOrderCount        = 0;
    private int $registeredOrderPaymentCount = 0;
    private int $registeredOrderProductCount = 0;
    private string $slipNumMessage;

    /**
     * 作成したOrderCollectionをデータベースに保存
     * @param CsvOrderCollection $csvOrderCollection 伝票番号をキーとするOrderCollectionの連想配列
     * @throws Exception
     * @throws Throwable
     */
    public function saveCsvCollection(CsvOrderCollection $csvOrderCollection): string
    {
        try {
            DB::beginTransaction();

            // 伝票番号ごとに処理
            foreach ($csvOrderCollection as $slipNumber => $csvOrderRowArray) {
                $this->slipNumMessage = "伝票番号:{$slipNumber}";

                // OrderCollectionをデータベースに保存
                if (!$this->saveOrders($csvOrderRowArray)) {
                    continue;
                }

            }
            DB::commit();

            $resultMessage = "Orderテーブル登録:{$this->registeredOrderCount}件\n";
            $resultMessage .= "Orderテーブル更新:{$this->updatedOrderCount}件\n";
            $resultMessage .= "OrderPaymentテーブル登録:{$this->registeredOrderPaymentCount}件\n";
            $resultMessage .= "OrderProductテーブル登録:{$this->registeredOrderProductCount}件\n";

            // 成功時は登録件数を返す
            return $resultMessage;

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
        /** @var OrderInfo|null $existingOrder */
        $existingOrder = OrderInfo::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の注文がない場合のみ、Orderをデータベースに登録
        if (!$existingOrder) {
            $orderId = $this->createOrder($csvOrderRowArray[0]);
            $this->registeredOrderCount++;
        }

        // 既存の注文がある場合、その注文のIDを使用
        if ($existingOrder) {
            $orderId = $existingOrder->id;

            // Orderテーブルの情報を更新
            $orderData = $csvOrderRowArray[0]->getOrderForRegistration();
            $existingOrder->update($orderData);

            // OrderPaymentテーブルの情報を削除
            OrderPayment::where('order_info_id', $orderId)->delete();
            $this->updatedOrderCount++;
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
        $orderData = $csvOrderRow->getOrderForRegistration();
        /** @var OrderInfo|null $createdOrder */
        $createdOrder = OrderInfo::create($orderData);

        if (!$createdOrder) {
            throw new Exception('order_infoテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
        }

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
            $this->registeredOrderPaymentCount++;
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
        $this->registeredOrderProductCount++;
    }
}
