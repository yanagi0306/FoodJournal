<?php

namespace App\Services\Order\Usen;

use App\Constants\CommonDatabaseConstants;
use App\Constants\UsenConstants;
use App\Models\CustomerType;
use App\Models\IncomeCategory;
use App\Models\PaymentMethod;
use App\Models\Store;
use App\Services\Company\FetchesCompanyInfo;
use App\Services\Order\Usen\Wrappers\Payment;
use App\Services\Order\Usen\Wrappers\Product;
use App\Services\Order\Usen\Wrappers\SkipDecision;
use App\Services\Order\Usen\Wrappers\Slip;
use Exception;
use Illuminate\Support\Facades\Log;

class CsvOrderRow
{
    private Payment            $payment;
    private Product            $product;
    private SkipDecision       $skipDecision;
    private Slip               $slip;
    private FetchesCompanyInfo $companyInfo;
    private int                $companyId;
    private array              $storeCds;

    /**
     * @throws Exception
     */
    public function __construct(array $row, FetchesCompanyInfo $companyInfo)
    {
        $this->companyInfo = $companyInfo;
        $this->companyId   = $companyInfo->getCompanyValue('id');
        $this->storeCds    = array_column($companyInfo->stores, 'order_store_cd');

        if (count($row) !== UsenConstants::USEN_CSV_ROW_COUNT) {
            Log::info(print_r($row, true));
            throw new Exception('不正な列数を持つ連携ファイルが検出されました。正しい桁列数:' . UsenConstants::USEN_CSV_ROW_COUNT . ' 検出された列数:' . count($row));
        }

        $this->skipDecision = new SkipDecision([
                                                   'aggregateFlag' => $row[0],
                                                   'orderStatus'   => $row[92],
                                                   'paymentStatus' => $row[11],
                                               ]);

        $this->slip = new Slip([
                                   'storeCd'          => $row[1],
                                   'slipNumber'       => $row[2],
                                   'orderDate'        => $row[12],
                                   'paymentDate'      => $row[13],
                                   'menCount'         => $row[19],
                                   'womenCount'       => $row[20],
                                   'customerTypeName' => $row[66],
                                   'salesType'        => $row[93],
                                   'storeCds'         => $this->storeCds,
                               ]);

        $this->payment = new Payment([
                                         'cash'                     => $row[41],
                                         'creditCard'               => $row[42],
                                         'points'                   => $row[43],
                                         'electronicMoney'          => $row[44],
                                         'giftCertNoChangeAmount'   => $row[45],
                                         'giftCertNoChangeDiff'     => $row[46],
                                         'giftCertWithChangeAmount' => $row[47],
                                         'giftCertWithChangeDiff'   => $row[48],
                                         'delivery'                 => $row[49],
                                     ]);

        $this->product = new Product([
                                         'product'  => $row[74],
                                         'quantity' => $row[83],
                                     ]);
    }

    /**
     * Orderに関するデータを取得する
     * @return array
     * @throws Exception
     */
    public function getOrderForRegistration(): array
    {
        $slip             = $this->slip->getValues();
        $storeId          = $this->getStoreIdByStoreCd($slip['storeCd']);
        $customerTypeId   = $this->getTypeIdByCustomerTypeName($slip['customerTypeName']);
        // todo.カテゴリはorderテーブルではなく、orderPaymentにしなければならない。 金額はすべてorderPaymentなので。
        $incomeCategoryId = $this->getIncomeCategory();

        return [
            'store_id'           => $storeId,
            'income_category_id' => $incomeCategoryId,
            'customer_type_id'   => $customerTypeId,
            'slip_number'        => $slip['slipNumber'],
            'order_date'         => $slip['orderDate'],
            'payment_date'       => $slip['paymentDate'],
            'men_count'          => $slip['menCount'],
            'women_count'        => $slip['womenCount'],
        ];
    }

    /**
     * OrderPaymentに関するデータを取得する
     * @param int $orderId
     * @return array
     * @throws Exception
     */
    public function getOrderPaymentsForRegistration(int $orderId): array
    {
        $orderPayments = [];
        $payment       = $this->payment->getValues();

        foreach ($payment as $property => $value) {
            if ($value === 0) {
                continue;
            }

            $paymentMethod = $this->getPaymentMethodIdByProperty($property);

            $paymentFee = bcmul((string)$value, (string)$paymentMethod->commission_rate, 2);

            $orderPayments[$property] = [
                'order_info_id'     => $orderId,
                'payment_method_id' => $paymentMethod->id,
                'amount'            => $value,
                'payment_fee'       => floor((float)$paymentFee),
            ];
        }

        return $orderPayments;
    }

    /**
     * OrderProductに関するデータを取得する
     * @param int   $orderId
     * @param array $orderProducts
     * @return array
     */
    public function getOrderProductForRegistration(int $orderId, array $orderProducts): array
    {
        $orderProduct = $this->product->getValues();

        return [
            'order_info_id'           => $orderId,
            'order_product_master_id' => $this->getOrderProductMasterId($orderProduct['productCd'], $orderProducts),
            'quantity'                => $orderProduct['quantity'],
        ];
    }

    /**
     * 伝票番号を取得する
     * @return string
     */
    public function getSlipNumber(): string
    {
        $slip = $this->slip->getValues();
        return $slip['slipNumber'];
    }

    /**
     * モデルを参照して、store_idを取得する
     * @return int
     * @throws Exception
     */
    public function getStoreId(): int
    {
        $slip    = $this->slip->getValues();
        $storeCd = $slip['storeCd'];
        $store   = Store::where('company_id', $this->companyId)->where('order_store_cd', $storeCd)->first();
        if (!$store) {
            throw new Exception("storeが存在しません companyId:{$this->companyId} storeCd:({$storeCd})");
        }
        return $store->id;
    }

    /**
     * companyInfoインスタンスを参照して、customerTypeNameからcustomer_type_idに変換する
     * @param string|null $customerTypeName
     * @return int|null
     * @throws Exception
     */
    private function getTypeIdByCustomerTypeName(?string $customerTypeName): ?int
    {
        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_CUSTOMER_TYPE, 'type_name', $customerTypeName);
    }

    /**
     * companyInfoインスタンスを参照して、storeCdからstore_idに変換する
     * @param string $storeCd
     * @return int|null
     * @throws Exception
     */
    private function getStoreIdByStoreCd(string $storeCd): ?int
    {
        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'order_store_cd', $storeCd);
    }

    /**
     * companyInfoインスタンスを参照して、propertyからpay_method_idに変換する
     * @param string $property
     * @return int|null
     * @throws Exception
     */
    private function getPaymentMethodIdByProperty(string $property): ?int
    {
        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_PAYMENT_METHOD, 'property_name', $property);
    }

    /**
     * モデルを参照して、収入カテゴリを取得する
     * @return int
     * @throws Exception
     */
    private function getIncomeCategory(): int
    {
        $categoryCd = CommonDatabaseConstants::CHILD_INCOME_CATEGORY_FOR_STORE_SALE['cat_cd'];
        return $this->companyInfo->getIdFromColumnValue(FetchesCompanyInfo::TABLE_INCOME_CATEGORY, 'cat_cd', $categoryCd);
    }

    /**
     * 注文商品マスタ一覧を参照して、OrderProductMasterIdを取得する
     * $orderProducts => key注文番号 value注文商品マスタIDの配列
     * @param string $productCd
     * @param array  $orderProducts
     * @return int
     */
    public function getOrderProductMasterId(string $productCd, array $orderProducts): int
    {
        return $orderProducts[$productCd];
    }
}



