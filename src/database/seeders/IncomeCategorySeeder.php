<?php

namespace Database\Seeders;

use App\Constants\Common;
use App\Models\Company;
use App\Models\IncomeCategory;
use App\Models\ParentIncomeCategory;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach (Common::PARENT_INCOME_CATEGORIES as $category) {
            ParentIncomeCategory::create([
                                             'company_id' => $company->id,
                                             'cat_cd'     => $category['cat_cd'],
                                             'cat_name'   => $category['cat_name'],
                                         ]);
        }

        $incomeCategory = ParentIncomeCategory::where('company_id', $company->id)->where('cat_cd', Common::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'])->firstOrFail();

        foreach (Common::CHILD_INCOME_CATEGORIES as $category) {
            IncomeCategory::create([
                                       'company_id'                => $company->id,
                                       'parent_income_category_id' => $incomeCategory->id,
                                       'income_type_cd'            => $category['income_type_cd'],
                                       'cat_cd'                    => $category['cat_cd'],
                                       'cat_name'                  => $category['cat_name'],
                                   ]);
        }
    }
}
