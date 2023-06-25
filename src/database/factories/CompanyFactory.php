<?php

namespace Database\Factories;

use App\Constants\Common;
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
            'company_cd'          => $this->getNextCounter('company_cd', '1001'),
            'purchase_company_cd' => '78411381',
            'purchase_system'     => Common::ASPIT_SYSTEM_NAME,
            'order_system'        => Common::USEN_SYSTEM_NAME,
            'mail'                => $this->faker->unique()->safeEmail,
        ];
    }
}
