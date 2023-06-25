<?php

namespace App\Services\Order\Usen;

use App\Models\OrderInfo;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CsvOrderRegistration
{
    private array  $orderCollection;
    private array  $orderProducts;
    private int    $updatedOrderCount           = 0;
    private int    $registeredOrderCount        = 0;
    private int    $registeredOrderPaymentCount = 0;
    private int    $registeredOrderProductCount = 0;
    private string $slipNumMessage;

    public function __construct(array $orderCollection, array $orderProducts)
    {
        $this->orderCollection = $orderCollection;
        $this->orderProducts   = $orderProducts;
    }

    /**
     * 作成したOrderCollectionをデータベースに保存
     * @throws Exception
     * @throws Throwable
     */
    public function saveCsvCollection(): string
    {
        try {
            DB::beginTransaction();
            // 伝票番号ごとに処理
            /** @var iterable|CsvOrderRow[] $csvOrderRowArray */
            foreach ($this->orderCollection as $slipNumber => $csvOrderRowArray) {
                $this->slipNumMessage = "伝票番号:({$slipNumber}) ";
                // 各伝票番号に対応するOrderCollectionをデータベースに保存
                $this->saveOrders($csvOrderRowArray);
            }
            DB::commit();
        } catch (Exception $e) {
            // 例外が発生した場合、トランザクションをロールバック処理終了
            DB::rollBack();
            throw new Exception($this->slipNumMessage . $e->getMessage());
        }

        return $this->generateResultMessage();
    }

    /**
     * @param iterable|CsvOrderRow[] $csvOrderRowArray 同一伝票番号のCsvOrderRowの入った配列
     * @return void 登録の成功/失敗
     * @throws Exception
     */
    private function saveOrders(array $csvOrderRowArray): void
    {
        $orderId = $this->saveOrder($csvOrderRowArray[0]);
        $this->saveOrderPayments($csvOrderRowArray[0], $orderId);
        $this->saveOrderProducts($csvOrderRowArray, $orderId);
    }

    /**
     * 既存の注文がない場合は新たに注文を作成
     * @param CsvOrderRow $csvOrderRow
     * @return int 注文ID
     * @throws Exception
     */
    private function saveOrder(CsvOrderRow $csvOrderRow): int
    {
        $slipNumber    = $csvOrderRow->getSlipNumber();
        $storeId       = $csvOrderRow->getStoreId();
        $existingOrder = OrderInfo::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の注文がある場合、その注文の情報を更新し、IDを返す
        if ($existingOrder) {
            $this->updateExistingOrder($existingOrder, $csvOrderRow);
            return $existingOrder->id;
        }

        return $this->createOrder($csvOrderRow);
    }

    /**
     * 既存の注文の情報を更新し、注文に紐づく注文支払と注文商品の情報を削除
     * @param OrderInfo   $existingOrder
     * @param CsvOrderRow $csvOrderRow
     * @throws Exception
     */
    private function updateExistingOrder(OrderInfo $existingOrder, CsvOrderRow $csvOrderRow): void
    {
        $orderData = $csvOrderRow->getOrderForRegistration();
        if (!$existingOrder->update($orderData)) {
            throw new Exception('order_infoテーブルの更新に失敗しました。' . __FILE__ . __LINE__);
        }

        // 注文支払情報の情報を削除
        if (!OrderPayment::where('order_info_id', $existingOrder->id)->delete()) {
            throw new Exception('order_paymentテーブルの削除に失敗しました。' . __FILE__ . __LINE__);
        }

        // 注文商品情報の情報を削除
        if (!OrderProduct::where('order_info_id', $existingOrder->id)->delete()) {
            throw new Exception('order_productテーブルの削除に失敗しました。' . __FILE__ . __LINE__);
        }

        $this->updatedOrderCount++;
    }

    /**
     * Orderテーブルに登録
     * @param CsvOrderRow $csvOrderRow
     * @return int
     * @throws Exception
     */
    private function createOrder(CsvOrderRow $csvOrderRow): int
    {
        $orderData = $csvOrderRow->getOrderForRegistration();

        $createdOrder = OrderInfo::create($orderData);

        if (!$createdOrder) {
            throw new Exception('order_infoテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
        }

        $this->registeredOrderCount++;
        return $createdOrder->id;
    }

    /**
     * OrderPaymentテーブルに登録
     * @param CsvOrderRow $csvOrderRow
     * @param int         $orderId
     * @return void
     * @throws Exception
     */
    private function saveOrderPayments(CsvOrderRow $csvOrderRow, int $orderId): void
    {
        $orderPaymentsData = $csvOrderRow->getOrderPaymentsForRegistration($orderId);
        foreach ($orderPaymentsData as $orderPaymentData) {

            if (!OrderPayment::create($orderPaymentData)) {
                throw new Exception('order_paymentテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
            }

            $this->registeredOrderPaymentCount++;
        }
    }

    /**
     * OrderProductテーブルに登録
     * @param iterable|CsvOrderRow[] $csvOrderRowArray
     * @param int                    $orderId
     * @return void
     * @throws Exception
     */
    private function saveOrderProducts(array $csvOrderRowArray, int $orderId): void
    {
        $orderProducts = [];
        foreach ($csvOrderRowArray as $csvOrderRow) {
            $row = $csvOrderRow->getOrderProductForRegistration($orderId, $this->orderProducts);
            $productMasterId = $row['order_product_master_id'];

            // 同一伝票番号の同一商品について数量を集計
            if (isset($orderProducts[$productMasterId])) {
                $orderProducts[$productMasterId]['quantity'] += $row['quantity'];
            } else {
                $orderProducts[$productMasterId] = $row;
            }
        }

        // 集計した商品情報をデータベースに登録
        foreach ($orderProducts as $productData) {
            if (!OrderProduct::create($productData)) {
                throw new Exception('order_productテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
            }
            $this->registeredOrderProductCount++;
        }
    }

    /**
     * 結果メッセージを生成する
     * @return string
     */
    private
    function generateResultMessage(): string
    {
        $resultMessage = "Orderテーブル登録:{$this->registeredOrderCount}件\n";
        $resultMessage .= "Orderテーブル更新:{$this->updatedOrderCount}件\n";
        $resultMessage .= "OrderPaymentテーブル登録:{$this->registeredOrderPaymentCount}件\n";
        $resultMessage .= "OrderProductテーブル登録:{$this->registeredOrderProductCount}件\n";

        // 成功時は登録件数を返す
        return $resultMessage;
    }
}

