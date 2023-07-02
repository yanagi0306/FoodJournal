<?php

namespace App\Services\ExpenseCategory;

use App\Constants\CommonDatabaseConstants;
use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\CreateParentExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Models\ParentExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use DB;
use Exception;
use Throwable;

/**
 * ParentExpenseCategory
 * ExpenseCategory
 * のデータ操作クラス
 */
class ExpenseCategoryService
{

    private int $companyId;

    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * 親支出カテゴリを登録する
     * todo.ここはあとで上位に移行する
     * @param CreateParentExpenseCategoryRequest $request
     * @return ParentExpenseCategory|null
     * @throws Throwable
     */
    public function createParentExpenseCategory(CreateParentExpenseCategoryRequest $request): ?ParentExpenseCategory
    {
        try {
            return DB::transaction(function() use ($request) {
                $validateData = $request->validated();
                return $this->registerParentExpenseCategory($validateData->cat_name);
            });
        } catch (Throwable $e) {
            Log::error("親支出カテゴリの登録処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            return null;
        }
    }

    /**
     * 新規親支出カテゴリーを保存処理
     * 共通カテゴリ「その他」の登録
     * @param string   $catName
     * @param int|null $catCd
     * @return ParentExpenseCategory
     * @throws Throwable
     */
    public function registerParentExpenseCategory(string $catName, int $catCd = null): ParentExpenseCategory
    {
        if ($catCd === null) {
            $catCd = $this->generateNextParentCategoryCd();
        }

        $parentExpenseCategory = new ParentExpenseCategory([
                                                               'company_id' => $this->companyId,
                                                               'cat_name'   => $catName,
                                                               'cat_cd'     => $catCd,
                                                           ]);

        $parentExpenseCategory->saveOrFail();

        // 共通カテゴリその他の登録
        $expenseCategory = new ExpenseCategory([
                                                   'company_id'                 => $this->companyId,
                                                   'parent_expense_category_id' => $parentExpenseCategory->id,
                                                   'cat_cd'                     => CommonDatabaseConstants::CATEGORY_FOR_OTHER['cat_cd'],
                                                   'cat_name'                   => CommonDatabaseConstants::CATEGORY_FOR_OTHER['cat_name'],
                                                   'type_cd'                    => CommonDatabaseConstants::CATEGORY_FOR_OTHER['type_cd'],
                                               ]);

        $expenseCategory->saveOrFail();

        return $parentExpenseCategory;
    }

    /**
     * 支出カテゴリーを登録します
     * todo.ここはあとで上位に移行する
     * @param CreateExpenseCategoryRequest $request
     * @return ExpenseCategory|null
     */
    public function createNewExpenseCategory(CreateExpenseCategoryRequest $request): ?ExpenseCategory
    {
        try {
            return DB::transaction(function() use ($request) {
                $validateData            = $request->validated();
                $parentExpenseCategoryId = $this->getParentExpenseCategory($validateData->parent_cat_cd)->id;
                return $this->registerExpenseCategory($validateData->cat_name, $parentExpenseCategoryId, $validateData->type_cd);
            });
        } catch (Throwable $e) {
            Log::error("支出カテゴリの登録処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            return null;
        }
    }

    /**
     * 新規支出カテゴリーを保存処理
     * @param string   $catName
     * @param int      $parentExpenseCategoryId
     * @param int      $typeCd
     * @param int|null $catCd
     * @return ExpenseCategory
     * @throws Throwable
     */
    public function registerExpenseCategory(string $catName, int $parentExpenseCategoryId, int $typeCd, int $catCd = null): ExpenseCategory
    {
        if ($catCd === null) {
            $catCd = $this->generateNextCategoryCd($parentExpenseCategoryId);
        }

        $expenseCategory = new ExpenseCategory([
                                                   'company_id'                 => $this->companyId,
                                                   'parent_expense_category_id' => $parentExpenseCategoryId,
                                                   'cat_name'                   => $catName,
                                                   'cat_cd'                     => $catCd,
                                                   'type_cd'                    => $typeCd,
                                               ]);

        $expenseCategory->saveOrFail();

        return $expenseCategory;
    }

    /**
     * 支出カテゴリを削除する。
     * 親カテゴリに紐づく全てのカテゴリを採番し直す
     * @param ExpenseCategory $expenseCategory
     * @return bool
     * @throws Throwable
     */
    public function deleteExpenseCategory(ExpenseCategory $expenseCategory): bool
    {
        // todo.トランザクションの箇所は上位に移行予定
        // 上位にはFormRequestを使用したバリデーションクラスを導入予定
        try {
            return DB::transaction(function() use ($expenseCategory) {
                $parentExpenseCategoryId = $expenseCategory->parent_expense_category_id;

                // 削除可能かチェック
                $this->validateExpenseCategoryDeletion($expenseCategory);

                // 削除
                $expenseCategory->delete();

                // 紐づく全てのカテゴリを取得(特殊数値カテゴリは除く)
                /** @var Builder|ExpenseCategory $categories */
                $categories = $this->getExpenseCategories($parentExpenseCategoryId)
                                   ->where('cat_cd', '<=', CommonDatabaseConstants::MAX_EXPENSE_CATEGORY_CODE);

                // カテゴリコードを再採番(存在しない場合は採番不要)
                if ($categories->first()) {
                    $this->reassignCatCd($categories);
                }

                return true;
            });
        } catch (Throwable $e) {
            Log::error("支出カテゴリの削除処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            return false;
        }
    }

    /**
     * 親支出カテゴリを削除する
     * 会社IDに紐づく全ての親支出カテゴリを採番し直す
     * @param ParentExpenseCategory $parentExpenseCategory
     * @return bool
     */
    public function deleteParentExpenseCategory(ParentExpenseCategory $parentExpenseCategory): bool
    {
        // todo.トランザクションの箇所は上位に移行予定
        // 上位にはFormRequestを使用したバリデーションクラスを導入予定
        try {
            return DB::transaction(function() use ($parentExpenseCategory) {
                // 削除可能かチェック
                $this->validateParentExpenseCategoryDeletion($parentExpenseCategory);

                // 親カテゴリを削除(子カテゴリもonDelete付与済みのため削除される)
                $parentExpenseCategory->delete();

                // 会社に紐づく全ての親カテゴリを取得
                /** @var Builder|ParentExpenseCategory $parentCategories */
                $parentCategories = $this->getParentExpenseCategories()
                                         ->where('cat_cd', '<=', CommonDatabaseConstants::MAX_PARENT_EXPENSE_CATEGORY_CODE);

                // カテゴリコードを再採番(存在しない場合は採番不要)
                if ($parentCategories->first()) {
                    $this->reassignParentCatCd($parentCategories);
                }

                return true;
            });
        } catch (Throwable $e) {
            Log::error("親支出カテゴリの削除処理に失敗しました。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            return false;
        }
    }

    /**
     * カテゴリコード(expense_category.cat_cd)を採番する
     * 99:その他は除外する
     * 親カテゴリコードに紐づいたカテゴリコードが未登録の場合は先頭番号'10'を採番する。
     * @param int $parentExpenseCategoryId
     * @return string
     * @throws Exception
     */
    private function generateNextCategoryCd(int $parentExpenseCategoryId): string
    {
        /** @var Builder|ExpenseCategory $categories */
        $categories = $this->getExpenseCategories($parentExpenseCategoryId)
                           ->where('cat_cd', '<=', CommonDatabaseConstants::MAX_EXPENSE_CATEGORY_CODE);
        Log::info('parentExpenseCategoryId:' . $parentExpenseCategoryId);
        Log::info('categories:' . $categories->first());

        if (!$categories->first()) {
            return CommonDatabaseConstants::START_CATEGORY_CODE;
        }

        $maxCatCd = $categories->max('cat_cd');

        if ($maxCatCd >= CommonDatabaseConstants::MAX_EXPENSE_CATEGORY_CODE) {
            throw new Exception('支出カテゴリに紐づく支出カテゴリの登録上限数に達しました。不要なカテゴリを削除してください。カテゴリの登録上限数:' . CommonDatabaseConstants::MAX_EXPENSE_CATEGORY_CODE - CommonDatabaseConstants::START_CATEGORY_CODE);
        }

        return $maxCatCd + 1;
    }

    /**
     * 親支出カテゴリを採番する
     * 親カテゴリコード未登録の場合は先頭番号'10'を採番する。
     * @return string
     * @throws Exception
     */
    private function generateNextParentCategoryCd(): string
    {
        /** @var Builder|ParentExpenseCategory $parentCategories */
        $parentCategories = $this->getParentExpenseCategories()
                                 ->where('cat_cd', '<=', CommonDatabaseConstants::MAX_PARENT_EXPENSE_CATEGORY_CODE);

        if (!$parentCategories->first()) {
            return CommonDatabaseConstants::START_CATEGORY_CODE;
        }

        $maxCatCd = $parentCategories->max('cat_cd');

        if ($maxCatCd >= CommonDatabaseConstants::MAX_PARENT_EXPENSE_CATEGORY_CODE) {
            throw new Exception('支出カテゴリの登録上限数に達しました。不要なカテゴリを削除してください。カテゴリの登録上限数:' . CommonDatabaseConstants::MAX_PARENT_EXPENSE_CATEGORY_CODE - CommonDatabaseConstants::START_CATEGORY_CODE);
        }

        return $maxCatCd + 1;
    }

    /**
     * 親支出カテゴリ削除のバリデーションを行う
     * @param ParentExpenseCategory $parentExpenseCategory
     * @throws Exception
     */
    private
    function validateParentExpenseCategoryDeletion(ParentExpenseCategory $parentExpenseCategory): void
    {
        // 共通のカテゴリの削除は許可しない
        foreach (CommonDatabaseConstants::PARENT_EXPENSE_CATEGORIES as $commonCategory) {
            if ($commonCategory['cat_cd'] === $parentExpenseCategory->cat_cd) {
                throw new Exception('対象の支出カテゴリは共通カテゴリの為、削除できません。');
            }
        }

        $expenseCategories = $this->getExpenseCategories($parentExpenseCategory->id);

        foreach ($expenseCategories as $category) {
            // カテゴリに紐づく支出情報が存在する場合は削除を許可しない
            if ($category->expenseInfos()->exists()) {
                throw new Exception('対象の支出カテゴリには支出情報が紐付いているため、削除できません。');
            }

            // カテゴリに紐づく支出予算情報が存在する場合は削除を許可しない
            if ($category->expenseBudgets()->exists()) {
                throw new Exception('対象の支出カテゴリには支出予算情報が紐付いているため、削除できません。');
            }
        }
    }

    /**
     * 支出カテゴリ削除のバリデーションを行う
     * @param ExpenseCategory $expenseCategory
     * @throws Exception
     */
    private
    function validateExpenseCategoryDeletion(ExpenseCategory $expenseCategory): void
    {
        $parentExpenseCategoryCd = $expenseCategory->parentExpenseCategory->cat_cd;

        // 共通のカテゴリの削除は許可しない
        foreach (CommonDatabaseConstants::EXPENSE_CATEGORIES as $commonCategory) {
            if ($commonCategory['cat_cd'] === $expenseCategory->cat_cd &&
                $commonCategory['parent_cat_cd'] === $parentExpenseCategoryCd) {
                throw new Exception('対象の支出カテゴリは共通カテゴリの為、削除できません。');
            }
        }

        // カテゴリに紐づく支出情報が存在する場合は削除を許可しない
        if ($expenseCategory->expenseInfos()->exists()) {
            throw new Exception('対象の支出カテゴリには支出情報が紐付いているため、削除できません。');
        }

        // カテゴリに紐づく予算支出情報が存在する場合は削除を許可しない
        if ($expenseCategory->expenseBudgets()->exists()) {
            throw new Exception('対象の支出カテゴリには支出予算情報が紐付いているため、削除できません。');
        }
    }

    /**
     * 親カテゴリコードを採番し直す
     * @param ParentExpenseCategory $categories
     * @throws Exception
     */
    private
    function reassignParentCatCd(ParentExpenseCategory $categories): void
    {
        $catCd = CommonDatabaseConstants::START_CATEGORY_CODE;
        foreach ($categories as $category) {

            try {
                $category->cat_cd = $catCd;
                $catCd++;
                $category->saveOrFail();
            } catch (Throwable $e) {
                throw new Exception("親カテゴリコードの再採番に失敗しました。\n" . $e->getMessage());
            }
        }
    }

    /**
     * カテゴリコードを採番し直す
     * @param ExpenseCategory $categories
     * @throws Exception
     */
    private function reassignCatCd(ExpenseCategory $categories): void
    {
        $catCd = CommonDatabaseConstants::START_CATEGORY_CODE;

        foreach ($categories as $category) {

            try {
                $category->cat_cd = $catCd + 1;
                $category->saveOrFail();
            } catch (Throwable $e) {
                throw new Exception("カテゴリコードの再採番に失敗しました。\n" . $e->getMessage());
            }
        }
    }

    /**
     * ParentExpenseCategoryモデルを参照して会社IDに紐づくParentExpenseCategoryを返す
     * @return Builder|null
     */
    private function getParentExpenseCategories(): ?Builder
    {
        return ParentExpenseCategory::where('company_id', $this->companyId)
                                    ->orderBy('cat_cd');
    }

    /**
     * ParentExpenseCategoryモデルを参照してParentExpenseCategoryを取得する
     * @param $catCd
     * @return ParentExpenseCategory
     * @throws Exception
     */
    public function getParentExpenseCategory($catCd): ParentExpenseCategory
    {
        try {
            /** @var Builder|ParentExpenseCategory $parentExpenseCategories */
            $parentExpenseCategories = $this->getParentExpenseCategories();

            // 存在しない場合はエラーにする
            return $parentExpenseCategories
                ->where('cat_cd', $catCd)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error("親支出カテゴリが見つかりませんでした。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            throw new Exception('親支出カテゴリが見つかりませんでした。');
        }
    }

    /**
     * ExpenseCategoryモデルを参照してExpenseCategoryを取得する
     * @param $catCd
     * @param $parentCatCd
     * @return ExpenseCategory
     * @throws Exception
     */
    private function getExpenseCategory($catCd, $parentCatCd): ExpenseCategory
    {
        try {
            $parentCatId = $this->getParentExpenseCategory($parentCatCd)->id;
            /** @var Builder|ExpenseCategory $expenseCategories */
            $expenseCategories = $this->getExpenseCategories($parentCatId);
            // 存在しない場合はエラーにする
            return $expenseCategories
                ->where('cat_cd', $catCd)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error("支出カテゴリが見つかりませんでした。\n" . $e->getMessage() . "\nfile:" . $e->getFile() . ' line:' . $e->getLine());
            throw new Exception('支出カテゴリが見つかりませんでした。');
        }
    }

    /**
     * ExpenseCategoryモデルを参照してParentExpenseCategoryに紐づくExpenseCategoryを返す
     * @param int $parentExpenseCategoryId
     * @return Builder|null
     */
    private function getExpenseCategories(int $parentExpenseCategoryId): ?Builder
    {
        return ExpenseCategory::where('company_id', $this->companyId)
                              ->where('parent_expense_category_id', $parentExpenseCategoryId)
                              ->orderBy('cat_cd');
    }
}
