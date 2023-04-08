<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class ExpenseController
 * 日次支出
 * @package App\Http\Controllers
 */
class ExpenseController extends Controller
{
    /**
     * 日次支出一覧画面
     *
     * @param int $storeId
     * @param string $date
     * @return void
     */
    public function index(int $storeId, string $date)
    {
        // ここに日次支出一覧画面のロジックを追加します
    }

    /**
     * 日次支出登録画面
     *
     * @return void
     */
    public function create()
    {
        // ここに日次支出登録画面のロジックを追加します
    }

    /**
     * 日次支出登録
     *
     * @return void
     */
    public function store()
    {
        // ここに日次支出登録のロジックを追加します
    }

    /**
     * 日次支出詳細画面
     *
     * @param int $expense
     * @return void
     */
    public function show(int $expense)
    {
        // ここに日次支出詳細画面のロジックを追加します
    }

    /**
     * 日次支出編集画面
     *
     * @param int $expense
     * @return void
     */
    public function edit(int $expense)
    {
        // ここに日次支出編集画面のロジックを追加します
    }

    /**
     * 日次支出更新
     *
     * @param int $expense
     * @return void
     */
    public function update(int $expense)
    {
        // ここに日次支出更新のロジックを追加します
    }

    /**
     * 日次支出削除
     *
     * @param int $expense
     * @return void
     */
    public function destroy(int $expense)
    {
        // ここに日次支出削除のロジックを追加します
    }
}
