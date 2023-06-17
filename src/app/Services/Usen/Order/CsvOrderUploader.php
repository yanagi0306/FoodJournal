<?php

namespace App\Services\Usen\Order;

use App\Constants\Common;
use App\Helpers\FileHelper;
use App\Services\Base\BaseUploader;
use App\Services\Order\OrderUploaderInterface;
use App\Traits\CsvTrait;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
    protected int $companyId;
    protected string $type = 'order_info';

    /**
     * CSVデータを取り込み、データベースに登録するメソッド
     * @throws Exception|Throwable
     */
    public function processCsv(): array
    {
        // CSVデータを配列に変換
        $csvArray = $this->convertCsvToArray($this->uploadFile, 15, 'CP932');

        // 配列からCsvCollectionを作成
        $csvCollection = new CsvOrderCollection($csvArray, $this->companyId);
        $skipMessage = $csvCollection->getSkipMessage();

        // 登録処理実行
        $csvOrderRegistration = new CsvOrderRegistration();
        $recordCounts = $csvOrderRegistration->saveCsvCollection($csvCollection);

        // 成功した場合はファイルをアップロードディレクトリに移動
        $this->moveUploadedFile();

        return [$recordCounts, $skipMessage];
    }
}
