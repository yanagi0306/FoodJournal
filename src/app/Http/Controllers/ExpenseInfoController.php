<?php

namespace App\Http\Controllers;

use App\Services\Company\FetchesCompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ExpenseController
 * 日次支出
 * @package App\Http\Controllers
 */
class ExpenseInfoController extends Controller
{
    /**
     * 日次支出一覧画面
     * @return Response
     */
    public function index(): Response
    {
        // 会社情報から支出カテゴリ情報を取得
        try {
            $companyInfo = new FetchesCompanyInfo($this->userInfo['company_id']);
            $expenseCategories = $companyInfo->findParentExpenseCategoriesWithCompany();

        } catch (\Exception $e) {
            Log::error("支出カテゴリ情報の取得に失敗しました。" . $e->getMessage());
            return Inertia::render('Top/Index');
        }

        return Inertia::render('ExpenseInfo/Index', [
            'expenseCategory' => $expenseCategories,
        ]);
    }

    /**
     * 日次支出登録画
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
     * @param int $expenseInfo
     * @return void
     */
    public function show($expenseInfo)
    {
        // ここに日次支出詳細画面のロジックを追加します
    }

    /**
     * 日次支出編集画面
     *
     * @param int $expenseInfo
     * @return void
     */
    public function edit(int $expenseInfo)
    {
        // ここに日次支出編集画面のロジックを追加します
    }

    /**
     * 日次支出更新
     *
     * @param int $expenseInfo
     * @return void
     */
    public function update(int $expenseInfo)
    {
        // ここに日次支出更新のロジックを追加します
    }

    /**
     * 日次支出削除
     *
     * @param int $expenseInfo
     * @return void
     */
    public function destroy(int $expenseInfo)
    {
        // ここに日次支出削除のロジックを追加します
    }
}
