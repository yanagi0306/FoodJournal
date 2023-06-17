<?php

namespace Database\Seeders;

use App\Constants\Common;
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
        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach (Common::PAYMENT_METHODS_TEMPLATE as $paymentMethod) {
            PaymentMethod::create([
                                      'company_id'      => $company->id,
                                      'payment_cd'      => $paymentMethod['payment_cd'],
                                      'payment_name'    => $paymentMethod['payment_name'],
                                      'property_name'   => $paymentMethod['property_name'],
                                      'commission_rate' => $paymentMethod['commission_rate'],
                                  ]);
        }
    }
}
