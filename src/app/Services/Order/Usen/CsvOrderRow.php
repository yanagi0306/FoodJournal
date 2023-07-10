<?php

namespace app\Services\Order\Usen\Usen;

use App\Constants\UsenConstants;
use app\Services\Company\FetchesCompanyInfo;
use app\Services\Order\Usen\Usen\Wrappers\Payment;
use app\Services\Order\Usen\Usen\Wrappers\Product;
use app\Services\Order\Usen\Usen\Wrappers\SkipDecision;
use app\Services\Order\Usen\Usen\Wrappers\Slip;
use Exception;
use Illuminate\Support\Facades\Log;

class CsvOrderRow
{
    private Payment            $payment;
    private Product            $product;
    private SkipDecision       $skipDecision;
    private Slip               $slip;
    private FetchesCompanyInfo $companyInfo;
    private int                $storeId;
    private array              $storeCds;

    /**
     * @throws Exception
     */
    public function __construct(array $row, FetchesCompanyInfo $companyInfo)
    {
        $this->companyInfo = $companyInfo;
        $this->storeCds    = $companyInfo->getValueArrayFromColumn(FetchesCompanyInfo::TABLE_STORE, 'order_store_cd');

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
                                         'product'   => $row[74],
                                         'unitPrice' => $row[78],
                                         'quantity'  => $row[83],
                                     ]);

        $storeCd       = $this->slip->getStoreCd();
        $this->storeId = $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_STORE, 'order_store_cd', $storeCd)['id'];
    }

    /**
     * Orderに関するデータを取得する
     * @return array
     * @throws Exception
     */
    public function getOrderForRegistration(): array
    {
        $slip           = $this->slip->getValues();
        $customerTypeId = $this->getTypeIdByCustomerTypeName($slip['customerTypeName']);

        return [
            'store_id'         => $this->storeId,
            'customer_type_id' => $customerTypeId,
            'slip_number'      => $slip['slipNumber'],
            'order_date'       => $slip['orderDate'],
            'payment_date'     => $slip['paymentDate'],
            'men_count'        => $slip['menCount'],
            'women_count'      => $slip['womenCount'],
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

            $paymentMethod = $this->getPaymentMethodByProperty($property);
            $paymentFee = bcmul((string)$value, (string)$paymentMethod['commission_rate'], 2);

            $orderPayments[$property] = [
                'order_info_id'     => $orderId,
                'payment_method_id' => $paymentMethod['id'],
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
            'sell_price'              => $orderProduct['unitPrice'],
            'quantity'                => $orderProduct['quantity'],
        ];
    }

    /**
     * 伝票番号を取得する
     * @return string
     */
    public function getSlipNumber(): string
    {
        return $this->slip->getSlipNumber();
    }

    /**
     * store_idを取得する
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->storeId;
    }

    /**
     * companyInfoインスタンスを参照して、customerTypeNameからcustomer_type_idに変換する
     * @param string|null $customerTypeName
     * @return int|null
     * @throws Exception
     */
    private function getTypeIdByCustomerTypeName(?string $customerTypeName): ?int
    {
        return $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_CUSTOMER_TYPE, 'type_name', $customerTypeName)['id'];
    }

    /**
     * companyInfoインスタンスを参照して、propertyからpay_methodに取得する
     * @param string $property
     * @return array
     * @throws Exception
     */
    private function getPaymentMethodByProperty(string $property): array
    {
        return $this->companyInfo->getRecordFromColumnValue(FetchesCompanyInfo::TABLE_PAYMENT_METHOD, 'property_name', $property);
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



