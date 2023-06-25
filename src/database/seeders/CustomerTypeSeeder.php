<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CustomerType;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $customerTypes = [
            '001' => '会社員・OL',
            '002' => '家族',
            '003' => '学生',
            '004' => '夫婦',
            '005' => 'ママ、主婦',
            '006' => '先輩',
            '007' => 'T.O,デリバリー',
        ];

        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach ($customerTypes as $typeCd => $typeName) {
            CustomerType::create([
                                     'company_id' => $company->id,
                                     'type_cd'    => $typeCd,
                                     'type_name'  => $typeName,
                                 ]);
        }
    }
}
