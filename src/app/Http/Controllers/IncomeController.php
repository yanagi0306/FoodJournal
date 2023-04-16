<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class IncomeController
 * 収入コントローラー
 * @package App\Http\Controllers
 */
class IncomeController extends Controller
{
    /**
     * 日次収入一覧画面
     *
     * @param int $storeId
     * @param string $date
     * @return void
     */
    public function index(int $storeId, string $date)
    {
        // ここに日次収入一覧画面のロジックを追加します
    }

    /**
     * 日次収入登録画面
     *
     * @return void
     */
    public function create()
    {
        // ここに日次収入登録画面のロジックを追加します
    }

    /**
     * 日次収入登録
     *
     * @return void
     */
    public function store()
    {
        // ここに日次収入登録のロジックを追加します
    }

    /**
     * 日次収入詳細画面
     *
     * @param int $income
     * @return void
     */
    public function show(int $income)
    {
        // ここに日次収入詳細画面のロジックを追加します
    }

    /**
     * 日次収入編集画面
     *
     * @param int $income
     * @return void
     */
    public function edit(int $income)
    {
        // ここに日次収入編集画面のロジックを追加します
    }

    /**
     * 日次収入更新
     *
     * @param int $income
     * @return void
     */
    public function update(int $income)
    {
        // ここに日次収入更新のロジックを追加します
    }

    /**
     * 日次収入削除
     *
     * @param int $income
     * @return void
     */
    public function destroy(int $income)
    {
        // ここに日次収入削除のロジックを追加します
    }
}
