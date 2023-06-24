<?php

namespace App\Services\Purchase\Aspit;

use App\Services\Base\BaseUploader;
use App\Services\Purchase\PurchaseUploaderInterface;
use App\Traits\CsvTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Throwable;

class CsvPurchaseUploader extends BaseUploader implements PurchaseUploaderInterface
{
    use CsvTrait;

    protected UploadedFile $uploadFile;
    protected int          $companyId;
    protected array        $storeIds;
    protected string       $type = 'purchase_info';

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     * @throws Exception|Throwable
     */
    public function processCsv(): string
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($this->uploadFile);

        // 配列から注文情報Collectionを作成
        $purchaseCollection = new CsvPurchaseCollection($csvArray, $this->companyId, $this->storeIds);

        // 仕入情報の登録処理実行
        $csvPurchaseRegistration = new CsvPurchaseRegistration($purchaseCollection->getPurchases(), $this->companyId);
        $recordCounts            = $csvPurchaseRegistration->saveCsvCollection();

        // 成功した場合はファイルをアップロードディレクトリに移動
        $this->moveUploadedFile();

        return $recordCounts;
    }
}
