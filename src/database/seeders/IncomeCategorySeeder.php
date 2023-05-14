<?php

namespace Database\Seeders;

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
        $company = Company::inRandomOrder()->first();

        $parentCategories = [
            1 => '売上',
        ];

        $childCategories = [
            1 => '店舗売上',
            2 => 'Uber',
            3 => '出前館',
            4 => 'Wolt',
        ];

        foreach ($parentCategories as $catCd => $catName) {
            ParentIncomeCategory::create([
                                             'company_id' => $company->id,
                                             'cat_cd'     => $catCd,
                                             'cat_name'   => $catName,
                                         ]);
        }

        foreach ($childCategories as $catCd => $catName) {
            IncomeCategory::create([
                                       'company_id'                => $company->id,
                                       'parent_income_category_id' => 1,
                                       'income_type_id'            => 4,
                                       'cat_cd'                    => $catCd,
                                       'cat_name'                  => $catName,
                                   ]);
        }
    }
}
