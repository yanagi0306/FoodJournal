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
     * @param     $purchaseCollection
     */
    public function __construct($purchaseCollection, $companyId)
    {
        $this->purchaseCollection = $purchaseCollection;
        $this->companyId          = $companyId;
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
            foreach ($this->purchaseCollection as $slipNumber => $csvPurchaseRow) {
                $this->slipNumMessage = "伝票番号:({$slipNumber}) ";
                // 各伝票番号に対応するPurchaseCollectionをデータベースに保存
                $this->savePurchase($csvPurchaseRow, $slipNumber);
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
     * 仕入情報を登録
     * @param CsvPurchaseRow $csvPurchaseRow
     * @param string         $slipNumber
     * @return void 登録の成功/失敗
     * @throws Exception
     */
    private function savePurchase(CsvPurchaseRow $csvPurchaseRow, string $slipNumber): void
    {
        $storeId       = $csvPurchaseRow->getStoreId($this->companyId);
        $existingPurchase = PurchaseInfo::where('store_id', $storeId)->where('slip_number', $slipNumber)->first();

        // 既存の仕入情報がある場合は更新処理を実行
        if ($existingPurchase) {
            $this->updateExistingPurchase($existingPurchase, $csvPurchaseRow);
        }

        $this->createPruchase($csvPurchaseRow);
    }

    /**
     * 既存の仕入情報を更新
     * @param PurchaseInfo   $existingPurchase
     * @param CsvPurchaseRow $csvPurchaseRow
     * @throws Exception
     */
    private function updateExistingPurchase(PurchaseInfo $existingPurchase, CsvPurchaseRow $csvPurchaseRow): void
    {
        $purchaseData = $existingPurchase->getPurchaseForRegistration();
        $existingPurchase->update($purchaseData);

        $this->updatedOrderCount++;
    }
}
