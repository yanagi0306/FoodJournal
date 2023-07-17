<?php

namespace App\Services\Purchase\Aspit;

use App\Models\PurchaseInfo;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class CsvPurchaseRegistration
{
    /** @var iterable|CsvPurchaseRow[] $purchaseCollection */
    private array  $purchaseCollection;
    private int    $updatedCount    = 0;
    private int    $registeredCount = 0;
    private string $slipNumMessage;

    /**
     * @param                    $purchaseCollection
     */
    public function __construct($purchaseCollection)
    {
        $this->purchaseCollection = $purchaseCollection;
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
            /** @var CsvPurchaseRow|null $CsvPurchaseRow */
            foreach ($this->purchaseCollection as $slipNumber => $csvPurchaseRow) {
                $this->slipNumMessage = "伝票番号:({$slipNumber}) ";
                $this->savePurchase($csvPurchaseRow, $slipNumber);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($this->slipNumMessage . $e->getMessage());
        }

        return $this->generateResultMessage();
    }

    /**
     * 仕入情報を登録
     * @param CsvPurchaseRow|null $csvPurchaseRow
     * @param string              $slipNumber
     * @return void 登録の成功/失敗
     * @throws Exception
     */
    private function savePurchase(?CsvPurchaseRow $csvPurchaseRow, string $slipNumber): void
    {
        $storeId          = $csvPurchaseRow->getStoreId();
        $existingPurchase = PurchaseInfo::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の仕入情報がある場合は更新処理を実行
        if ($existingPurchase) {
            $this->updateExistingPurchase($existingPurchase, $csvPurchaseRow);
            return;
        }

        $this->createPurchase($csvPurchaseRow);
    }

    /**
     * 既存のPurchaseテーブルを更新
     * @param PurchaseInfo   $existingPurchase
     * @param CsvPurchaseRow $csvPurchaseRow
     * @throws Exception
     */
    private function updateExistingPurchase(PurchaseInfo $existingPurchase, CsvPurchaseRow $csvPurchaseRow): void
    {
        $purchaseData = $csvPurchaseRow->getPurchaseForRegistration();

        if (!$existingPurchase->update($purchaseData)) {
            throw new Exception('purchase_infoテーブルの更新に失敗しました。' . __FILE__ . __LINE__);
        }

        $this->updatedCount++;
    }

    /**
     * Purchaseテーブルに登録
     * @param CsvPurchaseRow $csvPurchaseRow
     * @return void
     * @throws Exception
     */
    private function createPurchase(CsvPurchaseRow $csvPurchaseRow): void
    {
        $purchaseData = $csvPurchaseRow->getPurchaseForRegistration();

        if (!PurchaseInfo::create($purchaseData)) {
            throw new Exception('purchase_infoテーブルの登録に失敗しました。' . __FILE__ . __LINE__);
        }

        $this->registeredCount++;
    }

    /**
     * 結果メッセージを生成する
     * @return string
     */
    private function generateResultMessage(): string
    {
        $resultMessage = "仕入伝票情報 登録:{$this->registeredCount}件\n";
        $resultMessage .= "仕入伝票情報 更新:{$this->updatedCount}件\n";

        // 成功時は登録件数を返す
        return $resultMessage;
    }
}
