<?php

namespace App\Constants;

/**
 * 共通定数クラス
 *  <<制約事項>>
 *  ・すべてapp_config.phpで呼び出して使用します。
 *  ・const以外を定義することは禁止します。
 *  ・単独で読み込むことは禁止します。
 *  ・このファイル内で別ファイルを読み込むことは禁止します。
 */
class Common
{
    /**
     * food_journal ディレクトリ階層
     */
    const BASE_DIR   = '/workspace';
    const LOGS_DIR   = '/workspace/storage/logs';
    const DATA_DIR   = '/data';
    const UPLOAD_DIR = '/data/upload';

    /**
     * 共通支出タイプ定義
     */
    const FIXED_EXPENSE_TYPE =
        [
            'type_cd'   => 1,
            'type_name' => '固定支出',
        ];

    const MONTHLY_EXPENSE_TYPE =
        [
            'type_cd'   => 2,
            'type_name' => '月次支出',
        ];

    const DAILY_EXPENSE_TYPE =
        [
            'type_cd'   => 3,
            'type_name' => '日次支出',
        ];

    const OTHER_EXPENSE_TYPE =
        [
            'type_cd'   => 4,
            'type_name' => 'その他支出',
        ];

    const EXPENSE_TYPES =
        [
            COMMON::FIXED_EXPENSE_TYPE,
            COMMON::MONTHLY_EXPENSE_TYPE,
            COMMON::DAILY_EXPENSE_TYPE,
            COMMON::OTHER_EXPENSE_TYPE,
        ];

    /**
     * 共通収入タイプ定義
     */
    const FIXED_INCOME_TYPE =
        [
            'type_cd'   => 1,
            'type_name' => '固定収入',
        ];

    const MONTHLY_INCOME_TYPE =
        [
            'type_cd'   => 2,
            'type_name' => '月次収入',
        ];

    const DAILY_INCOME_TYPE =
        [
            'type_cd'   => 3,
            'type_name' => '日次収入',
        ];

    const OTHER_INCOME_TYPE =
        [
            'type_cd'   => 4,
            'type_name' => 'その他収入',
        ];

    const INCOME_TYPES =
        [
            COMMON::FIXED_INCOME_TYPE,
            COMMON::MONTHLY_INCOME_TYPE,
            COMMON::DAILY_INCOME_TYPE,
            COMMON::OTHER_INCOME_TYPE,
        ];

    /**
     * 共通親支出カテゴリコード定義
     */
    const PARENT_EXPENSE_CATEGORY_FOR_PURCHASE =
        [
            'cat_cd'   => 1,
            'cat_name' => '仕入費',
        ];

    const PARENT_EXPENSE_CATEGORIES =
        [
            Common::PARENT_EXPENSE_CATEGORY_FOR_PURCHASE,
        ];

    /**
     * 共通子支出カテゴリコード定義
     */
    const CHILD_EXPENSE_CATEGORY_FOR_FOOD =
        [
            'expense_type_cd' => COMMON::OTHER_EXPENSE_TYPE['type_cd'],
            'cat_cd'          => 1,
            'cat_name'        => '食材',
        ];

    const CHILD_EXPENSE_CATEGORY_FOR_MATERIALS =
        [
            'expense_type_cd' => COMMON::OTHER_EXPENSE_TYPE['type_cd'],
            'cat_cd'          => 2,
            'cat_name'        => '資材',
        ];

    const CHILD_EXPENSE_CATEGORIES =
        [
            Common::CHILD_EXPENSE_CATEGORY_FOR_FOOD,
            Common::CHILD_EXPENSE_CATEGORY_FOR_MATERIALS,
        ];

    /**
     * 共通親収入カテゴリコード定義
     */
    const PARENT_INCOME_CATEGORY_FOR_SALE =
        [
            'cat_cd'   => 1,
            'cat_name' => '売上',
        ];

    const PARENT_INCOME_CATEGORIES = [
        Common::PARENT_INCOME_CATEGORY_FOR_SALE,
    ];

    /**
     * 共通子収入カテゴリコード定義
     */
    const CHILD_INCOME_CATEGORY_FOR_STORE_SALE =
        [
            'income_type_cd' => COMMON::OTHER_INCOME_TYPE['type_cd'],
            'cat_cd'         => 1,
            'cat_name'       => '店舗売上',
        ];

    const CHILD_INCOME_CATEGORY_FOR_DELIVERY_SALE =
        [
            'income_type_cd' => COMMON::OTHER_INCOME_TYPE['type_cd'],
            'cat_cd'         => 2,
            'cat_name'       => 'デリバリー売上',
        ];

    const CHILD_INCOME_CATEGORIES =
        [
            Common::CHILD_INCOME_CATEGORY_FOR_STORE_SALE,
            Common::CHILD_INCOME_CATEGORY_FOR_DELIVERY_SALE,
        ];

    /**
     * 支払ステータステンプレート定義
     */
    const PAYMENT_METHODS_TEMPLATE = [
        [
            'payment_cd'      => 1,
            'property_name'   => 'cash',
            'payment_name'    => '現金',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 2,
            'property_name'   => 'creditCard',
            'payment_name'    => 'クレジット',
            'commission_rate' => 0.30,
        ],
        [
            'payment_cd'      => 3,
            'property_name'   => 'points',
            'payment_name'    => 'ポイント',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 4,
            'property_name'   => 'electronicMoney',
            'payment_name'    => '電子マネー',
            'commission_rate' => 0.3,
        ],
        [
            'payment_cd'      => 5,
            'property_name'   => 'giftCertNoChange',
            'payment_name'    => '商品券釣無',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 6,
            'property_name'   => 'giftCertWithChange',
            'payment_name'    => '商品券釣有',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 7,
            'property_name'   => 'delivery',
            'payment_name'    => 'デリバリー',
            'commission_rate' => 0.30,
        ],
    ];
}


