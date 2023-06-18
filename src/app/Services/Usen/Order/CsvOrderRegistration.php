<?php

namespace App\Services\Usen\Order;

use Exception;
use Throwable;
use App\Models\OrderInfo;
use App\Models\OrderPayment;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class CsvOrderRegistration
{
    private array  $csvOrderCollection;
    private array  $orderProducts;
    private int    $updatedOrderCount           = 0;
    private int    $registeredOrderCount        = 0;
    private int    $registeredOrderPaymentCount = 0;
    private int    $registeredOrderProductCount = 0;
    private string $slipNumMessage;

    public function __construct(array $csvOrderCollection, array $orderProducts)
    {
        $this->csvOrderCollection = $csvOrderCollection;
        $this->orderProducts      = $orderProducts;
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
            foreach ($this->csvOrderCollection as $slipNumber => $csvOrderRowArray) {
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
     * 同一伝票番号のCsvOrderRowをループで処理し、注文を保存
     * @param iterable|CsvOrderRow[] $csvOrderRowArray 同一伝票番号のCsvOrderRowの入った配列
     * @return void 登録の成功/失敗
     * @throws Exception
     */
    private function saveOrders(array $csvOrderRowArray): void
    {
        // 注文IDを取得
        $orderId = $this->getOrderId($csvOrderRowArray[0]);

        // 注文決済をデータベースに登録
        $this->createOrderPayments($csvOrderRowArray[0], $orderId);

        // 同一伝票番号のCsvOrderRowをループで処理し、注文商品を登録
        $this->createOrderProducts($csvOrderRowArray, $orderId);
    }

    /**
     * 注文IDを取得する。既存の注文がない場合は新たに注文を作成
     * @param CsvOrderRow $csvOrderRow
     * @return int 注文ID
     * @throws Exception
     */
    private function getOrderId(CsvOrderRow $csvOrderRow): int
    {
        $slipNumber    = $csvOrderRow->getSlipNumber();
        $storeId       = $csvOrderRow->getStoreId();
        $existingOrder = OrderInfo::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の注文がある場合、その注文の情報を更新し、IDを返す
        if ($existingOrder) {
            $this->updateExistingOrder($existingOrder, $csvOrderRow);
            return $existingOrder->id;
        }

        // 既存の注文がない場合、新たに注文を作成し、そのIDを返す
        $this->registeredOrderCount++;
        return $this->createOrder($csvOrderRow);
    }

    /**
     * 既存の注文の情報を更新し、注文決済と注文商品の情報を削除
     * @param OrderInfo   $existingOrder
     * @param CsvOrderRow $csvOrderRow
     * @throws Exception
     */
    private function updateExistingOrder(OrderInfo $existingOrder, CsvOrderRow $csvOrderRow): void
    {
        $orderData = $csvOrderRow->getOrderForRegistration();
        $existingOrder->update($orderData);

        // 注文決済の情報を削除
        OrderPayment::where('order_info_id', $existingOrder->id)->delete();

        // 注文商品の情報を削除
        OrderProduct::where('order_info_id', $existingOrder->id)->delete();

        $this->updatedOrderCount++;
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
     * @param iterable|CsvOrderRow[] $csvOrderRowArray
     * @param int                    $orderId
     * @return void
     */
    private function createOrderProducts(array $csvOrderRowArray, int $orderId): void
    {
        $orderProducts = [];
        foreach ($csvOrderRowArray as $csvOrderRow) {
            $row = $csvOrderRow->getOrderProductForRegistration($orderId, $this->orderProducts);

            $productMasterId = $row['order_product_master_id'];

            // 同一伝票番号の商品について数量を集計
            if (isset($orderProducts[$productMasterId])) {
                $orderProducts[$productMasterId]['quantity'] += $row['quantity'];
            } else {
                $orderProducts[$productMasterId] = $row;
            }
        }

        // 集計した商品情報をデータベースに登録
        foreach ($orderProducts as $productData) {
            OrderProduct::create($productData);
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

