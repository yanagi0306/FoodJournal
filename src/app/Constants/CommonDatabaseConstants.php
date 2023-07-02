<?php

namespace App\Constants;

class CommonDatabaseConstants
{
    /**
     * 共通支出タイプ定義
     */

    const FIXED_TYPE =
        [
            'type_cd'   => '10',
            'type_name' => '固定支出',
        ];

    const MONTHLY_TYPE =
        [
            'type_cd'   => '11',
            'type_name' => '月次支出',
        ];

    const DAILY_TYPE =
        [
            'type_cd'   => '12',
            'type_name' => '日次支出',
        ];


    const BALANCE_TYPE =
        [
            self::FIXED_TYPE,
            self::MONTHLY_TYPE,
            self::DAILY_TYPE,
        ];


    /**
     * 共通カテゴリコード定義
     * 通常はカテゴリコード「10」から採番される
     * 以下は固定の値
     * 以下のカテゴリ(99:その他以外)を登録する場合は依頼を受けてのカテゴリ追加とする
     * 99:その他 は親カテゴリ作成時に自動的に子カテゴリとして追加される仕様
     */
    const PETTY_CASH_CATEGORY_CODE = '50';
    const DELIVERY_CATEGORY_CODE   = '98';
    const OTHER_CATEGORY_CODE      = '99';
    const START_CATEGORY_CODE      = '10';

    // 親支出カテゴリに紐づく支出カテゴリの最大数
    const MAX_EXPENSE_CATEGORY_CODE        = '10';
    // 会社に紐づく親支出カテゴリの最大数
    const MAX_PARENT_EXPENSE_CATEGORY_CODE = '50';

    const PARENT_CATEGORY_FOR_OTHER =
        [
            'cat_cd'   => self::OTHER_CATEGORY_CODE,
            'cat_name' => 'その他',
        ];

    const CATEGORY_FOR_OTHER =
        [
            'cat_cd'   => self::OTHER_CATEGORY_CODE,
            'cat_name' => 'その他',
            'type_cd'  => self::DAILY_TYPE['type_cd'],
        ];

    /**
     * 共通支出カテゴリコード定義
     */


    /**
     * 共通親支出カテゴリコード定義
     */
    const PARENT_EXPENSE_CATEGORY_FOR_FOOD =
        [
            'cat_cd'   => '10',
            'cat_name' => '食材',
        ];

    const PARENT_EXPENSE_CATEGORY_FOR_MATERIALS =
        [
            'cat_cd'   => '11',
            'cat_name' => '資材',
        ];

    const PARENT_EXPENSE_CATEGORY_FOR_DELIVERY =
        [
            'cat_cd'   => self::DELIVERY_CATEGORY_CODE,
            'cat_name' => 'デリバリー',
        ];

    const PARENT_EXPENSE_CATEGORIES =
        [
            self::PARENT_EXPENSE_CATEGORY_FOR_FOOD,
            self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS,
            self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY,
            self::PARENT_CATEGORY_FOR_OTHER,
        ];

    /**
     * 共通子支出カテゴリコード定義
     */
    const EXPENSE_CATEGORY_FOR_FOOD                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => '10',
            'cat_name'      => '食材',
            'type_cd'       => self::DAILY_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_FOOD_PETTY_CASH      =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => self::PETTY_CASH_CATEGORY_CODE,
            'cat_name'      => '小口現金',
            'type_cd'       => self::DAILY_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS            =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => '10',
            'cat_name'      => '資材',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS_PETTY_CASH =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => self::PETTY_CASH_CATEGORY_CODE,
            'cat_name'      => '小口現金',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_UBER                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => '10',
            'cat_name'      => 'UBER',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_DEMAE_KAN            =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => '11',
            'cat_name'      => '出前館',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_WOLT                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => '12',
            'cat_name'      => 'Wolt',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_TGAL                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => '13',
            'cat_name'      => 'TGAL',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];


    const EXPENSE_CATEGORIES =
        [
            self::EXPENSE_CATEGORY_FOR_FOOD,
            self::EXPENSE_CATEGORY_FOR_FOOD_PETTY_CASH,
            self::EXPENSE_CATEGORY_FOR_MATERIALS,
            self::EXPENSE_CATEGORY_FOR_MATERIALS_PETTY_CASH,
            self::EXPENSE_CATEGORY_FOR_UBER,
            self::EXPENSE_CATEGORY_FOR_DEMAE_KAN,
            self::EXPENSE_CATEGORY_FOR_WOLT,
            self::EXPENSE_CATEGORY_FOR_TGAL,
        ];


    /**
     * 共通親収入カテゴリコード定義
     */
    const PARENT_INCOME_CATEGORY_FOR_SALE =
        [
            'cat_cd'   => '11',
            'cat_name' => '売上',
        ];

    const PARENT_INCOME_CATEGORIES = [
        self::PARENT_INCOME_CATEGORY_FOR_SALE,
        self::PARENT_CATEGORY_FOR_OTHER,
    ];

    /**
     * 共通子収入カテゴリコード定義
     */
    const INCOME_CATEGORY_FOR_STORE_SALE    =
        [
            'parent_cat_cd' => self::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'],
            'cat_cd'        => '10',
            'cat_name'      => '店舗',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const INCOME_CATEGORY_FOR_DELIVERY_SALE =
        [
            'parent_cat_cd' => self::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'],
            'cat_cd'        => '11',
            'cat_name'      => 'デリバリー',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];

    const CHILD_INCOME_CATEGORIES =
        [
            self::INCOME_CATEGORY_FOR_STORE_SALE,
            self::INCOME_CATEGORY_FOR_DELIVERY_SALE,
        ];

    /**
     * 共通支払ステータス定義
     */
    const PAYMENT_METHODS_TEMPLATE = [
        [
            'payment_cd'      => '101',
            'property_name'   => 'cash',
            'payment_name'    => '現金',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => '102',
            'property_name'   => 'creditCard',
            'payment_name'    => 'クレジット',
            'commission_rate' => 0.30,
        ],
        [
            'payment_cd'      => '103',
            'property_name'   => 'points',
            'payment_name'    => 'ポイント',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => '104',
            'property_name'   => 'electronicMoney',
            'payment_name'    => '電子マネー',
            'commission_rate' => 0.3,
        ],
        [
            'payment_cd'      => '105',
            'property_name'   => 'giftCertNoChange',
            'payment_name'    => '商品券釣無',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => '106',
            'property_name'   => 'giftCertWithChange',
            'payment_name'    => '商品券釣有',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => '107',
            'property_name'   => 'delivery',
            'payment_name'    => 'デリバリー',
            'commission_rate' => 0.30,
        ],
    ];


    /**
     * テスト用収支カテゴリコード定義
     * ['parent_cat_cd' => '10', 'cat_cd' => '10', 'cat_name' => '店舗売上', 'type_cd' => '12'],
     */
}

