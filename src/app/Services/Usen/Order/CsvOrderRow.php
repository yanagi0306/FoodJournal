<?php

namespace App\Services\Usen\Order;

use App\Models\CustomerType;
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
    private Payment $payment;
    private Product $product;
    private SkipDecision $skipDecision;
    private Slip $slip;
    private int $companyId;

    /**
     * @throws SkipImportException|Exception
     */
    public function __construct(array $row, $companyId)
    {
        $this->companyId = $companyId;

        $this->skipDecision = new SkipDecision([
            'aggregateFlag' => $row[0],
            'orderStatus'   => $row[90],
            'paymentStatus' => $row[11],
        ]);

        $this->slip = new Slip([
            'storeCd'        => $row[1],
            'slipNumber'     => $row[2],
            'orderDate'      => $row[12],
            'paymentDate'    => $row[13],
            'menCount'       => $row[19],
            'womenCount'     => $row[20],
            'customerTypeCd' => $row[64],
            'salesType'      => $row[91],
        ]);

        $this->payment = new Payment([
            'cash'                     => $row[39],
            'creditCard'               => $row[40],
            'points'                   => $row[41],
            'electronicMoney'          => $row[42],
            'giftCertNoChangeAmount'   => $row[43],
            'giftCertNoChangeDiff'     => $row[44],
            'giftCertWithChangeAmount' => $row[45],
            'giftCertWithChangeDiff'   => $row[46],
            'otherPayment'             => $row[47],
        ]);

        $this->product = new Product([
            'category1'    => $row[66],
            'category2'    => $row[67],
            'category3'    => $row[68],
            'category4'    => $row[69],
            'category5'    => $row[70],
            'product'      => $row[72],
            'orderOptions' => $row[73],
            'unitPrice'    => $row[76],
            'unitCost'     => $row[78],
            'quantity'     => $row[81],
        ]);

    }

    /**
     * Orderに関するデータを取得する
     * @return array
     * @throws Exception
     */
    public function getOrderForRegistration(): array
    {
        $slip           = $this->slip->getValues();
        $storeId        = $this->getStoreIdByStoreCd($slip['storeCd']);
        $customerTypeId = $this->getTypeIdByCustomerTypeCd($slip['customerTypeCd']);

        return [
            'store_id'           => $storeId,
            'income_category_id' => 1,
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
            $orderPayments[$property] = [
                'order_id'          => $orderId,
                'payment_method_id' => $this->getPaymentMethodIdByProperty($property),
                'amount'            => $value['amount'],
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
        Log::info(print_r($orderProduct, true));

        return [
            'order_id'      => $orderId,
            'product_cd'    => $orderProduct['productCd'],
            'product_name'  => $orderProduct['productName'],
            'quantity'      => $orderProduct['quantity'],
            'unit_cost'     => $orderProduct['unitCost'],
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
     * モデルを参照して、store_idを取得する(外部処理)
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
     * モデルを参照して、customerTypeCdからcustomer_type_idに変換する
     * @param string|null $customerTypeCd
     * @return int|null
     * @throws Exception
     */
    private function getTypeIdByCustomerTypeCd(?string $customerTypeCd): ?int
    {
        $customerType = CustomerType::where('company_id', $this->companyId)->where('type_cd', $customerTypeCd)->first();
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
     * @return int
     * @throws Exception
     */
    private function getPaymentMethodIdByProperty(string $property): int
    {
        $payMethod = Store::where('company_id', $this->companyId)->where('property_name', $property)->first();
        if (!$payMethod) {
            throw new Exception("pay_methodが存在しません companyId:({$this->companyId}) property:({$property})");
        }
        return $payMethod->id;
    }
}



