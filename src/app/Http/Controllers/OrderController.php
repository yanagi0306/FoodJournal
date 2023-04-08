<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
    /**
     * 売上実績トップページを表示する
     *
     * @return void
     */
    public function index(): void
    {
        // ここに支出予算一覧画面のロジックを追加します
    }

    /**
     * 売上実績をアップロードする
     *
     * @return void
     */
    public function upload(): void
    {
        // ここにアップロード処理のロジックを追加します
    }

    /**
     * アップロード履歴をダウンロードする
     *
     * @return void
     */
    public function download_upload_history(): void
    {
        // ここにダウンロード処理のロジックを追加します
    }

    /**
     * 売上参照画面を表示する
     *
     * @return void
     */
    public function search(): void
    {
        // ここに売上参照画面のロジックを追加します
    }
}
