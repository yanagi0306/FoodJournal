<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ExpenseBudget;

/**
 * Class ExpenseBudgetController
 * 支出予算コントローラー
 * @package App\Http\Controllers
 */
class ExpenseBudgetController extends Controller
{

    /**
     * 店舗月次支出検索
     *
     * @param int $storeId
     * @param string $date
     * @return void
     */
    public function search($storeId, $date)
    {
        // ここに店舗月次支出一覧のロジックを追加します
    }

    /**
     * 店舗月次支出確定
     *
     * @param int $id
     * @return void
     */
    public function confirm($id)
    {
        // ここに月次支出確定のロジックを追加します
    }

    /**
     * 支出予算一覧画面
     * @param int    $storeId
     * @param string $date
     * @return void
     */
    public function index(int $storeId, string $date)
    {
        // ここに支出予算一覧画面のロジックを追加します
    }

    /**
     * 支出予算登録&更新
     * @param int $storeId
     * @return void
     */
    public function save(int $storeId)
    {
        // ここに支出予算登録&更新のロジックを追加します
    }

    /**
     * 支出予算削除
     * @param int $expenseBudgetId
     * @return void
     */
    public function destroy(int $expenseBudgetId)
    {
        // ここに支出予算削除のロジックを追加します
    }

    /**
     * 支出予算一括登録
     * @return void
     */
    public function csv()
    {
        // ここに支出予算一括登録のロジックを追加します
    }

    /**
     * 支出予算フォーマットDL
     *
     * @return void
     */
    public function download()
    {
        // ここに支出予算フォーマットDLのロジックを追加します
    }

    /**
     * 支出予算アップロード
     *
     * @return void
     */
    public function upload()
    {
        // ここに支出予算アップロードのロジックを追加します
    }


}
