<?php

namespace Database\Seeders;

use App\Constants\CommonDatabaseConstants;
use App\Models\Company;
use app\Services\Base\ExpenseCategoryService;
use DB;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExpenseCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        /** @var Company $company */
        $companyId = Company::find(1)->firstOrFail()->id;
        // 共通カテゴリ登録
        try {
            DB::transaction(function() use ($companyId) {

                foreach (CommonDatabaseConstants::PARENT_EXPENSE_CATEGORIES as $category) {
                    $expenseCategoryService = new ExpenseCategoryService($companyId);
                    $expenseCategoryService->registerParentExpenseCategory($category['cat_name'], $category['cat_cd']);
                }

                foreach (CommonDatabaseConstants::EXPENSE_CATEGORIES as $category) {
                    $expenseCategoryService  = new ExpenseCategoryService($companyId);
                    $parentExpenseCategoryId = $expenseCategoryService->getParentExpenseCategory($category['parent_cat_cd'])->id;
                    $expenseCategoryService->registerExpenseCategory($category['cat_name'], $parentExpenseCategoryId, $category['type_cd'], $category['cat_cd']);
                }
            });
        } catch (Throwable $e) {
            Log::error("支出カテゴリの登録処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            throw new Exception('支出カテゴリの登録処理に失敗しました');
        }

        // テスト用カテゴリ登録
        try {
            DB::transaction(function() use ($companyId) {

                foreach (CommonDatabaseConstants::PARENT_EXPENSE_CATEGORIES_FOR_TEST as $category) {
                    $expenseCategoryService = new ExpenseCategoryService($companyId);
                    $expenseCategoryService->registerParentExpenseCategory($category['cat_name'], $category['cat_cd']);
                }

                foreach (CommonDatabaseConstants::EXPENSE_CATEGORY_FOR_TEST as $category) {
                    $expenseCategoryService  = new ExpenseCategoryService($companyId);
                    $parentExpenseCategoryId = $expenseCategoryService->getParentExpenseCategory($category['parent_cat_cd'])->id;
                    $expenseCategoryService->registerExpenseCategory($category['cat_name'], $parentExpenseCategoryId, $category['type_cd']);
                }
            });
        } catch (Throwable $e) {
            Log::error("テスト用支出カテゴリの登録処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            throw new Exception('テスト用支出カテゴリの登録処理に失敗しました');
        }
    }
}
