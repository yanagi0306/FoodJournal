<?php

namespace Database\Seeders;

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
        $company = Company::inRandomOrder()->first();

        $parentCategories = [
            1 => '仕入費',
        ];

        $childCategories = [
            1 => '食材',
            2 => '資材',
        ];

        foreach ($parentCategories as $catCd => $catName) {
            ParentExpenseCategory::create([
                                              'company_id' => $company->id,
                                              'cat_cd'     => $catCd,
                                              'cat_name'   => $catName,
                                          ]);
        }

        foreach ($childCategories as $catCd => $catName) {
            ExpenseCategory::create([
                                        'company_id'                 => $company->id,
                                        'parent_expense_category_id' => 1,
                                        'expense_type_id'            => 4,
                                        'cat_cd'                     => $catCd,
                                        'cat_name'                   => $catName,
                                    ]);
        }
    }
}
