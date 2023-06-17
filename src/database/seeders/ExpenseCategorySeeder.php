<?php

namespace Database\Seeders;

use App\Constants\Common;
use App\Models\Company;
use App\Models\ExpenseCategory;
use App\Models\ParentExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach (Common::PARENT_EXPENSE_CATEGORIES as $category) {
            ParentExpenseCategory::create([
                                              'company_id' => $company->id,
                                              'cat_cd'     => $category['cat_cd'],
                                              'cat_name'   => $category['cat_name'],
                                          ]);
        }

        $expenseCategory = ParentExpenseCategory::where('company_id', $company->id)->where('cat_cd', Common::PARENT_EXPENSE_CATEGORY_FOR_PURCHASE['cat_cd'])->firstOrFail();

        foreach (Common::CHILD_EXPENSE_CATEGORIES as $category) {
            ExpenseCategory::create([
                                        'company_id'                 => $company->id,
                                        'parent_expense_category_id' => $expenseCategory->id,
                                        'expense_type_cd'            => $category['expense_type_cd'],
                                        'cat_cd'                     => $category['cat_cd'],
                                        'cat_name'                   => $category['cat_name'],
                                    ]);
        }
    }
}
