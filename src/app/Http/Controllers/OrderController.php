<?php

namespace App\Http\Controllers;

use app\Services\Usen\CsvOrderUploadService;
use App\Services\Files\UploadHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     *
     * @return Response
     */
    public function index(): Response
    {
        // 売上情報のアップロード履歴の取得
        $this->uploadHistory = new UploadHistory('order', $this->userInfo['company_id']);
        $this->ordersHistory = $this->uploadHistory->getUploadHistory();

        return Inertia::render('Order/Index', [
            'ordersHistory' => $this->ordersHistory
        ]);
    }

    /**
     * 売上実績をアップロード
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {

        // アップロードされたファイルデータを取得
        $uploadedFile = $request->file('order_data');

        $service = new CsvOrderUploadService();

        try {
            $service->uploadOrder($uploadedFile);
            // 成功時の処理
            return response()->json([
                'message' => '売上データの登録に成功しました。',
            ]);

        } catch (\Exception $e) {
            // 失敗時の処理
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * アップロード履歴をダウンロード
     *
     * @return void
     */
    public function download_upload_history(): void
    {
        // ここにダウンロード処理のロジックを追加します
    }

    /**
     * 売上参照画面を表示
     *
     * @return void
     */
    public function search(): void
    {
        // ここに売上参照画面のロジックを追加します
    }
}
