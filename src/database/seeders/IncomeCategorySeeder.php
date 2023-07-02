<?php

namespace Database\Seeders;

use App\Constants\CommonDatabaseConstants;
use App\Models\Company;
use App\Models\IncomeCategory;
use App\Models\ParentIncomeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
            // 親カテゴリ登録
            $parentCategory = ParentIncomeCategory::create([
                                                               'company_id' => $company->id,
                                                               'cat_cd'     => $category['cat_cd'],
                                                               'cat_name'   => $category['cat_name'],
                                                           ]);

            // 子カテゴリ(その他)登録
            IncomeCategory::create([
                                       'company_id'                => $company->id,
                                       'parent_income_category_id' => $parentCategory->id,
                                       'cat_cd'                    => CommonDatabaseConstants::CATEGORY_FOR_OTHER['cat_cd'],
                                       'cat_name'                  => CommonDatabaseConstants::CATEGORY_FOR_OTHER['cat_name'],
                                       'type_cd'                   => CommonDatabaseConstants::CATEGORY_FOR_OTHER['type_cd'],
                                   ]);
        }

        foreach (CommonDatabaseConstants::CHILD_INCOME_CATEGORIES as $category) {
            $parentCategory = ParentIncomeCategory::where('company_id', $company->id)->where('cat_cd', $category['parent_cat_cd'])->firstOrFail();

            IncomeCategory::create([
                                       'company_id'                => $company->id,
                                       'parent_income_category_id' => $parentCategory->id,
                                       'cat_cd'                    => $category['cat_cd'],
                                       'cat_name'                  => $category['cat_name'],
                                       'type_cd'                   => $category['type_cd'],
                                   ]);
        }
    }
}
