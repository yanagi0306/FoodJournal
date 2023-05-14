<?php

namespace App\Http\Controllers;

use App\Services\Order\OrderUploaderFactory;
use App\Services\Usen\Order\CsvOrderUploader;
use App\Services\Files\UploadHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class OrderController
 * 売上コントローラー
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    protected UploadHistory $uploadHistory;
    protected array $ordersHistory;

    /**
     * 売上実績トップページを表示
     * @return Response
     */
    public function index(): Response
    {
        // 売上情報のアップロード履歴の取得
        $this->uploadHistory = new UploadHistory('order', $this->userInfo['company_id']);
        $this->ordersHistory = $this->uploadHistory->getUploadHistory();

        return Inertia::render('Order/Index', [
            'ordersHistory' => $this->ordersHistory,
        ]);
    }

    /**
     * 売上実績をアップロード
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {

        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('file');

        // システム名を取得 (データベースや設定ファイルから)
        $orderSystem = 'usen';

        $service = OrderUploaderFactory::createUploader($orderSystem, $uploadedFile, $this->userInfo['company_id']);

        try {
            $service->processCsv();

            // 成功時の処理
            return response()->json([
                'message' => '売上データの登録に成功しました。',
            ]);

        } catch (\Throwable $e) {
            Log::error("注文データの登録に失敗しました\n Exception:" . $e->getMessage() . "\n");
            // 失敗時の処理
            return response()->json([
                'message' => '注文データの登録に失敗しました',
            ], 400);
        }
    }

    /**
     * 売上参照画面を表示
     * @return void
     */
    public function search(): void
    {
        // ここに売上参照画面のロジックを追加します
    }
}
