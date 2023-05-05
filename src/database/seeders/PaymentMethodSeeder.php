<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $paymentMethods = [
            'cash'               => '現金',
            'creditCard'         => 'クレジット',
            'points'             => 'ポイント',
            'electronicMoney'    => '電子マネー',
            'giftCertNoChange'   => '商品券釣無',
            'giftCertWithChange' => '商品券釣有',
            'otherPayment'       => 'その他',
        ];

        // 既存のCompanyテーブルからランダムにレコードを取得
        $company = Company::inRandomOrder()->first();

        $paymentMethodCdCount = 0;

        foreach ($paymentMethods as $property => $paymentMethod) {
            PaymentMethod::create([
                'company_id'      => $company->id,
                'payment_cd'      => $paymentMethodCdCount,
                'payment_name'    => $paymentMethod,
                'property_name'   => $property,
                'commission_rate' => 0.3,
            ]);
            $paymentMethodCdCount++;
        }
    }
}
