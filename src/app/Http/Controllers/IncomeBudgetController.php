<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class IncomeBudgetController
 * 収入予算コントローラー
 * @package App\Http\Controllers
 */
class IncomeBudgetController extends Controller
{
    /**
     * 店舗月次収入検索
     *
     * @param int $storeId
     * @param string $date
     * @return void
     */
    public function search($storeId, $date)
    {
        // ここに店舗月次収入一覧のロジックを追加します
    }

    /**
     * 店舗月次収入確定
     *
     * @param int $id
     * @return void
     */
    public function confirm($id)
    {
        // ここに月次収入確定のロジックを追加します
    }

    /**
     * 収入予算一覧画面
     *
     * @param int $storeId
     * @param string $date
     * @return void
     */
    public function index($storeId, $date)
    {
        // ここに収入予算一覧画面のロジックを追加します
    }

    /**
     * 収入予算登録&更新
     *
     * @param int $storeId
     * @return void
     */
    public function save($storeId)
    {
        // ここに収入予算登録&更新のロジックを追加します
    }

    /**
     * 収入予算削除
     *
     * @param int $incomeBudgetId
     * @return void
     */
    public function destroy($incomeBudgetId)
    {
        // ここに収入予算削除のロジックを追加します
    }

    /**
     * 収入予算一括登録
     *
     * @return void
     */
    public function csv()
    {
        // ここに収入予算一括登録のロジックを追加します
    }

    /**
     * 収入予算フォーマットDL
     *
     * @return void
     */
    public function download()
    {
        // ここに収入予算フォーマットDLのロジックを追加します
    }

    /**
     * 収入予算アップロード
     *
     * @return void
     */
    public function upload()
    {
        // ここに収入予算アップロードのロジックを追加します
    }
}
