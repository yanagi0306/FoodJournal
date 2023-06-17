<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $stores = [
            [
                'name'           => 'BENCIA越谷店',
                'order_store_cd' => '001',
            ],
            [
                'name'           => 'BENCIA戸田公園前店',
                'order_store_cd' => '002',
            ],
        ];

        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach ($stores as $store) {
            Store::factory()->create([
                'company_id'     => $company->id,
                'store_name'     => $store['name'],
                'order_store_cd' => $store['order_store_cd'],
            ]);
        }
    }
}
