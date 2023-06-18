<?php

namespace App\Services\Usen\Order;

use App\Services\Base\BaseUploader;
use App\Services\Order\OrderUploaderInterface;
use App\Traits\CsvTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Throwable;

/**
 * Class CsvOrderUploader
 * USENシステム用の売上アップローダー
 * @package App\Services\Usen\Order
 */
class CsvOrderUploader extends BaseUploader implements OrderUploaderInterface
{
    use CsvTrait;

    protected UploadedFile $uploadFile;
    protected int          $companyId;
    protected array        $storeIds;
    protected string       $type = 'order_info';

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     * @throws Exception|Throwable
     */
    public function processCsv(): string
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($this->uploadFile, null, 'CP932');

        // 配列から注文商品マスタCollectionを作成
        $productMasterCollection = new CsvOrderProductMasterCollection($csvArray, $this->companyId, $this->storeIds);
        // 注文商品マスタの登録処理を実行
        $csvOrderProductMasterRegistration = new CsvOrderProductMasterRegistration($productMasterCollection, $this->companyId);
        $recordCounts                      = $csvOrderProductMasterRegistration->saveCsvCollection();

        // 注文商品マスタを更新した商品情報を保持
        $orderProducts = $csvOrderProductMasterRegistration->getOrderProducts();

        // 配列から注文情報Collectionを作成
        $orderCollection = new CsvOrderCollection($csvArray, $this->companyId, $orderProducts);

        // 注文情報の登録処理実行
        $csvOrderRegistration = new CsvOrderRegistration($orderCollection);
        $recordCounts         .= $csvOrderRegistration->saveCsvCollection();

        // 成功した場合はファイルをアップロードディレクトリに移動
        $this->moveUploadedFile();

        return $recordCounts;
    }
}
