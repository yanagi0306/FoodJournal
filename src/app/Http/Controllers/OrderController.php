<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHistory;
use App\Helpers\UserHelper;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Traits\LogTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

/**
 * Class OrderController
 * 売上コントローラー
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    protected array $uploadHistory;
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct(); //
        $this->orderService = $orderService;
    }

    /**
     * 売上実績トップページを表示
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        // 売上情報のアップロード履歴の取得
        $this->uploadHistory = UploadHistory::getOrderHistory('order', $this->userInfo);

        return Inertia::render('Order/Index', [
            'uploadHistory' => $this->uploadHistory
        ]);
    }

    /**
     * 売上実績をアップロード
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function upload(Request $request)
    {
        // アップロードされたファイルデータを取得
        $uploadedFile = $request->file('order_data');

        // ここでは OrderService の uploadOrderData メソッドを呼び出します
        $result = $this->orderService->uploadOrderData($uploadedFile);

        if ($result) {
            // 成功時の処理
            return Inertia::render('Order/Index', [
                'uploadHistory' => $this->uploadHistory,
                'message' => 'アップロードに成功しました。',
            ]);
        } else {
            // 失敗時の処理
            return Inertia::render('Order/Index', [
                'uploadHistory' => $this->uploadHistory,
                'message' => 'アップロードに失敗しました。',
            ]);
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
