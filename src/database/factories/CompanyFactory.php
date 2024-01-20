<?php

namespace Database\Factories;

use App\Constants\AspitConstants;
use App\Constants\UsenConstants;
use App\Models\Company;
use App\Traits\Counter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    use Counter;

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name'        => '合同会社PlusH-table',
            'company_abbr'        => 'H_table',
            'order_system_id'     => 1,
            'purchase_system_id'  => 1,
            'purchase_company_cd' => 1,
            'order_company_cd'    => AspitConstants::ASPIT_SYSTEM_NAME,
            'mail'                => $this->faker->unique()->safeEmail,
        ];
    }
}
