<?php

namespace App\Http\Controllers;

use App\Services\Company\FetchesCompanyInfo;
use App\Services\Files\UploadHistory;
use App\Services\Purchase\PurchaseUploaderFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


/**
 * Class PurchaseController
 * 仕入コントローラー
 * @package App\Http\Controllers
 */
class PurchaseInfoController extends Controller
{
    /**
     * 売上実績トップページを表示
     * @return Response
     */
    public function index(): Response
    {
        // 売上情報のアップロード履歴の取得
        $uploadHistory = new UploadHistory('purchase_info', $this->userInfo['company_id']);
        $purchasesHistory = $uploadHistory->getUploadHistory();

        return Inertia::render('Purchase/Index', [
            'purchasesHistory' => $purchasesHistory,
        ]);
    }

    /**
     * 仕入実績をアップロード
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
            // 会社情報に紐づく情報を取得
            $companyInfo = new FetchesCompanyInfo($this->userInfo['company_id']);

            // システム名を取得 (データベースや設定ファイルから)
            $service     = PurchaseUploaderFactory::createUploader($uploadedFile, $companyInfo);

            $resultMessage = $service->processCsv();

            $this->responseMessage = '仕入データの登録処理に成功しました。';
            $this->responseStatus  = 'success';
            Log::info($this->responseMessage . "\n" . $resultMessage);

        } catch (\Throwable $e) {
            $this->responseMessage = "仕入データの登録処理に失敗しました。\n" . $e->getMessage() . "\nfile:" .$e->getFile() . " line:" . $e->getLine();
            $this->responseStatus  = 'error';
            Log::error($this->responseMessage);
        }

        return to_route('purchases.index')
            ->with([
                       'message' => str_replace("\n", '<br>', $this->responseMessage),
                       'status'  => $this->responseStatus,
                   ]);
    }

    /**
     * アップロード履歴DL
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
     * 仕入参照
     * @return void
     */
    public function search()
    {
        // ここに仕入参照のロジックを追加します
    }

}
