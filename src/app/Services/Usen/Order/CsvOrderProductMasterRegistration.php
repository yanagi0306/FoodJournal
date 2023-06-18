<?php

namespace App\Services\Usen\Order;

use App\Models\OrderProductMaster;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;

class CsvOrderProductMasterRegistration
{
    private array  $csvOrderProductMasterCollection;
    private int    $companyId;
    private int    $updatedCount       = 0;
    private int    $registeredCount    = 0;
    private string $productCdMessage;
    private array  $successfulProducts = [];


    public function __construct(array $csvOrderProductMasterCollection, $companyId)
    {
        $this->companyId                       = $companyId;
        $this->csvOrderProductMasterCollection = $csvOrderProductMasterCollection;
    }

    /**
     * 作成したOrderProductMasterCollectionをデータベースに保存
     * @return string
     * @throws Throwable
     */
    public function saveCsvCollection(): string
    {
        try {
            DB::beginTransaction();

            foreach ($this->csvOrderProductMasterCollection as $productCd => $csvRowArray) {
                $this->productCdMessage = "商品番号:({$productCd}) ";
                // 注文商品マスタをデータベースに保存
                $this->saveProducts($csvRowArray, $productCd);

            }
            DB::commit();

        } catch (Exception $e) {
            // 例外が発生した場合、トランザクションをロールバック処理終了
            DB::rollBack();
            throw new Exception($this->productCdMessage . $e->getMessage());
        }

        return $this->generateResultMessage();
    }

    /**
     * OrderCollectionをデータベースに保存
     * @param CsvOrderProductMasterRow $csvRowArray
     * @param string                   $productCd
     * @return void 登録の成功/失敗
     * @throws Exception
     */
    private function saveProducts(CsvOrderProductMasterRow $csvRowArray, string $productCd): void
    {
        /** @var OrderProductMaster|null $existingOrder */
        $existingProductMaster = OrderProductMaster::where('company_id', $this->companyId)->where('product_cd', $productCd)->first();

        // 既存の注文商品マスタがない場合は登録処理
        if (!$existingProductMaster) {
            $orderProductMasterData = $csvRowArray->getOrderProductMasterForRegistration();
            $orderProductMasterId   = $this->createOrderProductMaster($orderProductMasterData);
            $this->registeredCount++;
        }

        // 既存のレコードが存在する場合は更新処理
        if ($existingProductMaster) {
            $orderProductMasterId   = $existingProductMaster->id;
            $orderProductMasterData = $csvRowArray->getOrderProductMasterForUpdate();
            $existingProductMaster->update($orderProductMasterData);
            $this->updatedCount++;
        }

        // 更新に成功した商品の情報を保存
        if (is_null($orderProductMasterId)) {
            throw new Exception('商品IDの取得に失敗しました。');
        }

        $this->successfulProducts[$productCd] = $orderProductMasterId;
    }

    /**
     * Order データをデータベースに登録
     * @param array $orderProductMasterRow
     * @return int
     * @throws Exception
     */
    private function createOrderProductMaster(array $orderProductMasterRow): int
    {
        /** @var OrderProductMaster|null $orderProductMaster */
        $orderProductMaster = OrderProductMaster::create($orderProductMasterRow);

        if (!$orderProductMaster) {
            throw new Exception('order_product_masterテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
        }

        return $orderProductMaster->id;
    }

    /**
     * 保存に成功した商品の情報を取得するメソッドを追加
     * @return array
     */
    public function getOrderProducts(): array
    {
        return $this->successfulProducts;
    }

    /**
     * 結果メッセージを生成する
     * @return string
     */
    private function generateResultMessage(): string
    {
        $resultMessage = "product_masterテーブル登録:{$this->registeredCount}件\n";
        $resultMessage .= "product_masterテーブル更新:{$this->updatedCount}件\n";

        // 成功時は登録件数を返す
        return $resultMessage;
    }
}
