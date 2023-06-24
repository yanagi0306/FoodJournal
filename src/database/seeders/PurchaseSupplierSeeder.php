<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\PurchaseSupplier;
use Illuminate\Database\Seeder;

class PurchaseSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $purchaseSuppliers = [
            '25106815' => '㈱ユタカ',
            '13107394' => 'ミルトス',
            '13106271' => '㈱ミズサワ',
            '20100001' => 'トーホーフードサービス',
            '111217'   => 'マルヤス',
            '5100511'  => '海老正',
            '11100342' => '菅野製麺所',
            '3600450'  => 'カクヤス',
            '19107411' => 'サンライズ(株)',
            '14102985' => 'ｻﾝﾄﾘｰﾋﾞﾊﾞﾚｯｼﾞｿﾘｭｰｼｮﾝ',
            '99999108' => '陽子ファーム',
            '13100717' => '三国コカ･コーラボトリング',
            '99999106' => '株式会社明昇',
            '8106854'  => '(株)堀商店',
            '99999111' => '天草梅肉ポーク',
        ];

        /** @var Company $company */
        $company = Company::find(1)->firstOrFail();

        foreach ($purchaseSuppliers as $supplierCd => $supplierName) {

            $isNoSlipNum = null;

            if (str_starts_with($supplierCd, '99999')) {
                $isNoSlipNum = 1;
            }

            PurchaseSupplier::create([
                                         'company_id'     => $company->id,
                                         'supplier_cd'    => $supplierCd,
                                         'supplier_name'  => $supplierName,
                                         'is_no_slip_num' => $isNoSlipNum,
                                     ]);
        }
    }
}
