<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $storeNames = ['BENCIA越谷店', 'BENCIA戸田公園前店'];

        // 既存のCompanyテーブルからランダムにレコードを取得
        $company = Company::inRandomOrder()->first();

        foreach ($storeNames as $storeName) {
            Store::factory()->create([
                'company_id' => $company->id,
                'store_name' => $storeName,
            ]);
        }
    }
}
