<?php

namespace app\Services\Order\Usen\Usen;

use App\Constants\Common;
use App\Constants\UsenConstants;
use App\Services\Base\BaseUploader;
use app\Services\Order\Usen\OrderUploaderInterface;
use App\Traits\CsvTrait;
use Exception;
use Throwable;

/**
 * Class CsvOrderUploader
 * USENシステム用の売上アップローダー
 * @package App\Services\Usen\Order
 */
class CsvOrderUploader extends BaseUploader implements OrderUploaderInterface
{
    use CsvTrait;

    protected string $type = 'order_info';

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     * @throws Exception|Throwable
     */
    public function processCsv(): string
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($this->uploadFile, UsenConstants::USEN_CSV_ENCODING);

        // 配列から注文情報Collectionを作成
        $orderCollection = new CsvOrderCollection($csvArray, $this->companyInfo);

        // 注文商品マスタの登録処理を実行
        $csvOrderProductMasterRegistration = new CsvOrderProductMasterRegistration($orderCollection->getOrderProducts(), $this->companyInfo->getCompanyValue('id'));
        $recordCounts                      = $csvOrderProductMasterRegistration->saveCsvCollection();

        // 注文商品マスタを更新した商品情報を保持
        $orderProducts = $csvOrderProductMasterRegistration->getOrderProducts();

        // 注文情報の登録処理実行
        $csvOrderRegistration = new CsvOrderRegistration($orderCollection->getOrders(), $orderProducts);
        $recordCounts         .= $csvOrderRegistration->saveCsvCollection();

        // 成功した場合はファイルをアップロードディレクトリに移動
        $this->moveUploadedFile();

        return $recordCounts;
    }
}
