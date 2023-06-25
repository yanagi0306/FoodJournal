<?php

namespace Database\Seeders;

use App\Constants\CommonDatabaseConstants;
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

        foreach (CommonDatabaseConstants::PARENT_INCOME_CATEGORIES as $category) {
            ParentIncomeCategory::create([
                                             'company_id' => $company->id,
                                             'cat_cd'     => $category['cat_cd'],
                                             'cat_name'   => $category['cat_name'],
                                         ]);
        }

        foreach (CommonDatabaseConstants::CHILD_INCOME_CATEGORIES as $category) {
            $parentCategory = ParentIncomeCategory::where('company_id', $company->id)->where('cat_cd', $category['parent_cat_cd'])->firstOrFail();

            IncomeCategory::create([
                                       'company_id'                => $company->id,
                                       'parent_income_category_id' => $parentCategory->id,
                                       'cat_cd'                    => $category['cat_cd'],
                                       'position'                  => $category['position'],
                                       'cat_name'                  => $category['cat_name'],
                                       'type_cd'                   => $category['type_cd'],
                                   ]);
        }
    }
}
