<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        // 既存のCompanyテーブルからランダムにレコードを取得
        $company = Company::inRandomOrder()->first();

        // 既存のCompanyテーブルからランダムにレコードを取得
        $store = Store::inRandomOrder()->first();

        User::factory()->create([
            'company_id' => $company->id,
            'store_id' => $store->id,
        ]);
    }
}
