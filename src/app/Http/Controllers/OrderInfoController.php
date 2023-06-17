<?php

namespace App\Http\Controllers;

use App\Services\Order\OrderUploaderFactory;
use App\Services\Files\UploadHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class OrderController
 * 売上コントローラー
 * @package App\Http\Controllers
 */
class OrderInfoController extends Controller
{

    /**
     * 売上実績トップページを表示
     * @return Response
     */
    public function index(): Response
    {
        // 売上情報のアップロード履歴の取得
        $uploadHistory = new UploadHistory('order_info', $this->userInfo['company_id']);
        $ordersHistory = $uploadHistory->getUploadHistory();

        return Inertia::render('Order/Index', [
            'ordersHistory' => $ordersHistory,
        ]);
    }

    /**
     * 売上実績をアップロード
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function upload(Request $request): Response|RedirectResponse
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $request->file('file');

        try {
            // システム名を取得 (データベースや設定ファイルから)
            $orderSystem = 'usen';
            $service     = OrderUploaderFactory::createUploader($orderSystem, $uploadedFile, $this->userInfo['company_id']);

            [$resultMessage,
             $skipMessage] = $service->processCsv();

            $this->responseMessage = '注文データの登録処理に成功しました。';
            $this->responseStatus  = 'success';
            Log::info($this->responseMessage . $resultMessage);

        } catch (\Throwable $e) {
            $this->responseMessage = "注文データの登録処理に失敗しました。\n" . $e->getMessage();
            $this->responseStatus  = 'error';
            Log::error($this->responseMessage);
        }

        return to_route('orders.index')
            ->with([
                       'message' => str_replace("\n", '<br>', $this->responseMessage),
                       'status'  => $this->responseStatus,
                   ]);
    }

    /**
     * 売上実績アップロード履歴をダウンロード
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function download(Request $request): BinaryFileResponse
    {
        // ファイルの名前をリクエストから取得
        $filename = $request->get('filename');

        // ファイルの存在を確認
        if (!file_exists($filename)) {
            abort(404);
        }

        // ダウンロード処理
        return response()->download($filename);
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
