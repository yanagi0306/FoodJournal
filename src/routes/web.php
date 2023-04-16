<?php

use App\Http\Controllers\DailyController;
use App\Http\Controllers\ExpenseBudgetController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeBudgetController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MonthlyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductMasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ShareAuthenticatedUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once __DIR__ . '/auth.php';


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['auth', 'verified',  ShareAuthenticatedUser::class])->group(function () {
    // トップページ
    Route::get('/', TopController::class . '@index')
        ->name('top.index');

    // 実績管理
    // 売上実績画面
    Route::get('/orders', OrderController::class . '@index')
        ->name('orders.index');
    Route::post('/orders/upload', OrderController::class . '@upload')
        ->name('orders.upload');
    Route::get('/orders/download_upload_history', OrderController::class . '@download_upload_history')
        ->name('orders.download_upload_history');

    // 仕入実績画面
    Route::get('/purchases', PurchaseController::class . '@index')
        ->name('purchases.index');
    Route::post('/purchases/upload', PurchaseController::class . '@upload')
        ->name('purchases.store');
    Route::get('/purchases/download_upload_history', PurchaseController::class . '@download_upload_history')
        ->name('purchases.download_upload_history');

    // 月次収入画面
    Route::get('/income_budgets/search/{store_id}/{date}', [IncomeBudgetController::class, '@search'])
        ->name('income_budgets.search');
    Route::patch('/income_budgets/confirm/{id}', [IncomeBudgetController::class, '@confirm'])
        ->name('income_budgets.confirm');

    // 月次支出画面
    Route::get('/expense_budgets/search/{store_id}/{date}', [ExpenseBudgetController::class, '@search'])
        ->name('expense_budgets.search');
    Route::patch('/expense_budgets/confirm/{id}', [ExpenseBudgetController::class, '@confirm'])
        ->name('expense_budgets.confirm');

    // 日次収入画面
    Route::get('/incomes/{store_id}/{date}', [IncomeController::class, '@index'])
        ->name('incomes.index');
    Route::get('/incomes/create', [IncomeController::class, '@create'])
        ->name('incomes.create');
    Route::post('/incomes', [IncomeController::class, '@store'])
        ->name('incomes.store');
    Route::get('/incomes/{income}', [IncomeController::class, '@show'])
        ->name('incomes.show');
    Route::get('/incomes/{income}/edit', [IncomeController::class, '@edit'])
        ->name('incomes.edit');
    Route::patch('/incomes/{income}', [IncomeController::class, '@update'])
        ->name('incomes.update');
    Route::delete('/incomes/{income}', [IncomeController::class, '@destroy'])
        ->name('incomes.destroy');

    // 日次支出画面
    Route::get('/expenses/{store_id}/{date}', [ExpenseController::class, '@index'])
        ->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, '@create'])
        ->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, '@store'])
        ->name('expenses.store');
    Route::get('/expenses/{expense}', [ExpenseController::class, '@show'])
        ->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, '@edit'])
        ->name('expenses.edit');
    Route::patch('/expenses/{expense}', [ExpenseController::class, '@update'])
        ->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, '@destroy'])
        ->name('expenses.destroy');

    // 予算管理
    // 収入予算画面
    Route::get('/income_budgets/{store_id}/{date}', [IncomeBudgetController::class, '@index'])
        ->name('income_budgets.index');
    Route::post('/income_budgets/{store_id}', [IncomeBudgetController::class, '@save'])
        ->name('income_budgets.save');
    Route::delete('/income_budgets/{income_budget}', [IncomeBudgetController::class, '@destroy'])
        ->name('income_budgets.destroy');

    // 支出予算画面
    Route::get('/expense_budgets/{store_id}/{date}', [ExpenseBudgetController::class, '@index'])
        ->name('expense_budgets.index');
    Route::post('/expense_budgets/{store_id}', [ExpenseBudgetController::class, '@save'])
        ->name('expense_budgets.save');
    Route::delete('/expense_budgets/{expense_budget}', [ExpenseBudgetController::class, '@destroy'])
        ->name('expense_budgets.destroy');

    // 収入予算一括登録画面
    Route::get('/income_budgets/csv', [IncomeBudgetController::class, '@csv'])
        ->name('income_budgets.csv');
    Route::post('/income_budgets/download', [IncomeBudgetController::class, '@download'])
        ->name('income_budgets.download');
    Route::get('/income_budgets/csv/upload', [IncomeBudgetController::class, '@upload'])
        ->name('income_budgets.upload');

    // 支出予算一括登録画面
    Route::get('/expense_budgets/csv', [ExpenseBudgetController::class, '@csv'])
        ->name('expense_budgets.csv');
    Route::post('/expense_budgets/download', [ExpenseBudgetController::class, '@download'])
        ->name('expense_budgets.download');
    Route::get('/expense_budgets/csv/upload', [ExpenseBudgetController::class, '@upload'])
        ->name('expense_budgets.upload');

    // 帳簿管理
    // 月次情報画面
    Route::get('/monthlies', [MonthlyController::class, '@index'])
        ->name('monthly.index');

    // 日次情報画面
    Route::get('/dailies', [DailyController::class, '@index'])
        ->name('daily.index');

    // 売上参照画面
    Route::get('/orders/search', [OrderController::class, '@search'])
        ->name('order.search');

    // 仕入参照画面
    Route::get('/purchases/search', [PurchaseController::class, '@search'])
        ->name('purchase.search');

    // カテゴリ管理
    // 収入カテゴリ画面
    Route::get('/income_categories', [IncomeCategoryController::class, '@index'])
        ->name('income_categories.index');
    Route::get('/income_categories/download', [IncomeCategoryController::class, '@download'])
        ->name('income_categories.download');
    Route::post('/income_categories/csv/upload', [IncomeCategoryController::class, '@upload'])
        ->name('income_categories.upload');

    // 支出カテゴリ画面
    Route::get('/expense_categories', [ExpenseCategoryController::class, '@index'])
        ->name('expense_categories.index');
    Route::get('/expense_categories/download', [ExpenseCategoryController::class, '@download'])
        ->name('expense_categories.download');
    Route::post('/expense_categories/csv/upload', [ExpenseCategoryController::class, '@upload'])
        ->name('expense_categories.upload');

    // 商品マスタ管理
    // 商品マスタカテゴリ画面
    Route::get('/product_categories', [ProductCategoryController::class, '@index'])
        ->name('product_categories.index');
    Route::get('/product_categories/csv/download', [ProductCategoryController::class, '@download'])
        ->name('product_categories.download');
    Route::post('/product_categories/csv/upload', [ProductCategoryController::class, '@upload'])
        ->name('product_categories.upload');

    // 商品マスタ画面
    Route::get('/product_product_masters', [ProductMasterController::class, '@index'])
        ->name('product_masters.index');
    Route::get('/product_product_masters/csv/download', [ProductMasterController::class, '@download'])
        ->name('product_masters.download');
    Route::post('/product_product_masters/csv/upload', [ProductMasterController::class, '@upload'])
        ->name('product_masters.upload');

});


