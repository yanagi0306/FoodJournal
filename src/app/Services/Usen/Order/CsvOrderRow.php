<?php

namespace App\Services\Usen\Order;

use App\Constants\Common;
use App\Models\CustomerType;
use App\Models\IncomeCategory;
use App\Models\PaymentMethod;
use App\Models\Store;
use Exception;
use App\Exceptions\SkipImportException;
use App\Services\Usen\Order\Wrappers\Product;
use App\Services\Usen\Order\Wrappers\SkipDecision;
use App\Services\Usen\Order\Wrappers\Slip;
use App\Services\Usen\Order\Wrappers\Payment;
use Illuminate\Support\Facades\Log;

class CsvOrderRow
{
    private Payment      $payment;
    private Product      $product;
    private SkipDecision $skipDecision;
    private Slip         $slip;
    private int          $companyId;

    /**
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row, $companyId, $rowCount = 95)
    {
        $this->companyId = $companyId;
        if (count($row) !== $rowCount) {
            Log::info(print_r($row, true));
            throw new Exception("不正な列数を持つ連携ファイルが検出されました。正しい桁列数:{$rowCount} 検出された列数:" . count($row));
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
                                         'category1'    => $row[68],
                                         'category2'    => $row[69],
                                         'category3'    => $row[70],
                                         'category4'    => $row[71],
                                         'category5'    => $row[72],
                                         'product'      => $row[74],
                                         'orderOptions' => $row[75],
                                         'unitPrice'    => $row[78],
                                         'unitCost'     => $row[80],
                                         'quantity'     => $row[83],
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
     * @param int $orderId
     * @return array
     */
    public function getOrderProductForRegistration(int $orderId): array
    {
        $orderProduct = $this->product->getValues();

        return [
            'order_info_id' => $orderId,
            'product_cd'    => $orderProduct['productCd'],
            'product_name'  => $orderProduct['productName'],
            'quantity'      => $orderProduct['quantity'],
            'unit_cost'     => floor($orderProduct['unitCost']),
            'unit_price'    => $orderProduct['unitPrice'],
            'order_options' => $orderProduct['orderOptions'],
            'category1'     => $orderProduct['category1'],
            'category2'     => $orderProduct['category2'],
            'category3'     => $orderProduct['category3'],
            'category4'     => $orderProduct['category4'],
            'category5'     => $orderProduct['category5'],
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
     * モデルを参照して、customerTypeNameからcustomer_type_idに変換する
     * @param string|null $customerTypeName
     * @return int|null
     */
    private function getTypeIdByCustomerTypeName(?string $customerTypeName): ?int
    {
        $customerType = CustomerType::where('company_id', $this->companyId)->where('type_name', $customerTypeName)->first();
        if (!$customerType) {
            return null;
        }
        return $customerType?->id;
    }

    /**
     * モデルを参照して、storeCdからstore_idに変換する
     * @param string $storeCd
     * @return int|null
     * @throws Exception
     */
    private function getStoreIdByStoreCd(string $storeCd): ?int
    {
        $store = Store::where('company_id', $this->companyId)->where('order_store_cd', $storeCd)->first();
        if (!$store) {
            throw new Exception("storeが存在しません companyId:{$this->companyId} storeCd:({$storeCd})");
        }
        return $store->id;
    }

    /**
     * モデルを参照して、propertyからpay_method_idに変換する
     * @param string $property
     * @return PaymentMethod
     * @throws Exception
     */
    private function getPaymentMethodIdByProperty(string $property): PaymentMethod
    {
        $payMethod = PaymentMethod::where('company_id', $this->companyId)->where('property_name', $property)->first();
        if (!$payMethod) {
            throw new Exception("pay_methodが存在しません companyId:({$this->companyId}) property:({$property})");
        }
        return $payMethod;
    }

    /**
     * モデルを参照して、companyに収入カテゴリを取得する
     * @return int
     * @throws Exception
     */
    private function getIncomeCategory(): int
    {
        $incomeCategory = IncomeCategory::where('company_id', $this->companyId)->where('cat_cd', Common::CHILD_INCOME_CATEGORY_FOR_STORE_SALE['cat_cd'])->first();
        if (!$incomeCategory) {
            throw new Exception("IncomeCategoryが存在しません companyId:({$this->companyId})");
        }
        return $incomeCategory->id;

    }
}



