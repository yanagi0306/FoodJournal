<?php

namespace Database\Seeders;

use App\Constants\Common;
use App\Constants\CommonDatabaseConstants;
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

        foreach (CommonDatabaseConstants::PARENT_EXPENSE_CATEGORIES as $category) {
            ParentExpenseCategory::create([
                                              'company_id' => $company->id,
                                              'cat_cd'     => $category['cat_cd'],
                                              'cat_name'   => $category['cat_name'],
                                          ]);
        }

        foreach (CommonDatabaseConstants::EXPENSE_CATEGORIES as $category) {

            $parentExpenseCategory = ParentExpenseCategory::where('company_id', $company->id)->where('cat_cd', $category['parent_cat_cd'])->firstOrFail();

            ExpenseCategory::create([
                                        'company_id'                 => $company->id,
                                        'parent_expense_category_id' => $parentExpenseCategory->id,
                                        'cat_cd'                     => $category['cat_cd'],
                                        'position'                   => $category['position'],
                                        'cat_name'                   => $category['cat_name'],
                                        'type_cd'                    => $category['type_cd'],
                                    ]);
        }
    }
}
