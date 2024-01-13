<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Services\Company\FetchesCompanyInfo;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ExpenseBudgetController
 * 支出予算コントローラー
 * @package App\Http\Controllers
 */
class ExpenseBudgetController extends Controller
{

    /**
     * 店舗支出予算画面
     * @return Response
     */
    public function index(): Response
    {
        try {
            $companyInfo = $this->setCompanyInfo();

            return Inertia::render('ExpenseBudget/Index', [
                'stores'                  => $companyInfo->getTableInfo('stores'),
                'parentExpenseCategories' => $companyInfo->getTableInfo('parentExpenseCategories'),
            ]);

        } catch (Exception $e) {
            Log::error('システムエラーが発生しました。' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return Inertia::render('Top/Index');
        }
    }

    /**
     * 支出予算登録&更新
     * @param int $storeId
     * @return void
     */
    public
    function save(int $storeId)
    {
        // ここに支出予算登録&更新のロジックを追加します
    }

    /**
     * 支出予算削除
     * @param int $expenseBudgetId
     * @return void
     */
    public
    function destroy(int $expenseBudgetId)
    {
        // ここに支出予算削除のロジックを追加します
    }

    /**
     * 店舗月次支出検索
     * @param int    $storeId
     * @param string $date
     * @return void
     */
    public
    function search($storeId, $date)
    {
        // ここに店舗月次支出一覧のロジックを追加します
    }

    /**
     * 店舗月次支出確定
     * @param int $id
     * @return void
     */
    public
    function confirm($id)
    {
        // ここに月次支出確定のロジックを追加します
    }

    /**
     * 支出予算一括登録
     * @return void
     */
    public
    function csv()
    {
        // ここに支出予算一括登録のロジックを追加します
    }

    /**
     * 支出予算フォーマットDL
     * @return void
     */
    public
    function download()
    {
        // ここに支出予算フォーマットDLのロジックを追加します
    }

    /**
     * 支出予算アップロード
     * @return void
     */
    public
    function upload()
    {
        // ここに支出予算アップロードのロジックを追加します
    }

    /**
     * 会社に関連する情報を定義する
     * @return FetchesCompanyInfo
     * @throws Exception
     */
    private function setCompanyInfo(): FetchesCompanyInfo
    {
        try {
            // 会社に紐づく情報を定義
            $companyInfo = new FetchesCompanyInfo($this->userInfo['company_id']);
            $companyInfo->setStoresWithCompany();
            $companyInfo->setParentExpenseCategoriesWithCompany();
        } catch (\Exception $e) {
            throw new Exception('システムエラーが発生しました。' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
        return $companyInfo;
    }

}
