<?php

namespace App\Constants;

class CommonDatabaseConstants
{
    /**
     * 共通支出タイプ定義
     */
    const FIXED_EXPENSE_TYPE =
        [
            'type_cd'   => '101',
            'type_name' => '固定支出',
        ];

    const MONTHLY_EXPENSE_TYPE =
        [
            'type_cd'   => '102',
            'type_name' => '月次支出',
        ];

    const DAILY_EXPENSE_TYPE =
        [
            'type_cd'   => '103',
            'type_name' => '日次支出',
        ];

    const OTHER_EXPENSE_TYPE =
        [
            'type_cd'   => '104',
            'type_name' => 'その他支出',
        ];

    const EXPENSE_TYPES =
        [
            self::FIXED_EXPENSE_TYPE,
            self::MONTHLY_EXPENSE_TYPE,
            self::DAILY_EXPENSE_TYPE,
            self::OTHER_EXPENSE_TYPE,
        ];

    /**
     * 共通収入タイプ定義
     */
    const FIXED_INCOME_TYPE =
        [
            'type_cd'   => '101',
            'type_name' => '固定収入',
        ];

    const MONTHLY_INCOME_TYPE =
        [
            'type_cd'   => '102',
            'type_name' => '月次収入',
        ];

    const DAILY_INCOME_TYPE =
        [
            'type_cd'   => '103',
            'type_name' => '日次収入',
        ];

    const OTHER_INCOME_TYPE =
        [
            'type_cd'   => '104',
            'type_name' => 'その他収入',
        ];

    const INCOME_TYPES =
        [
            self::FIXED_INCOME_TYPE,
            self::MONTHLY_INCOME_TYPE,
            self::DAILY_INCOME_TYPE,
            self::OTHER_INCOME_TYPE,
        ];


    /**
     * 共通親支出カテゴリコード定義
     */
    const PARENT_EXPENSE_CATEGORY_FOR_FOOD =
        [
            'cat_cd'   => '11',
            'cat_name' => '食材',
        ];

    const PARENT_EXPENSE_CATEGORY_FOR_MATERIALS =
        [
            'cat_cd'   => '12',
            'cat_name' => '資材',
        ];

    const PARENT_EXPENSE_CATEGORIES =
        [
            self::PARENT_EXPENSE_CATEGORY_FOR_FOOD,
            self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS,
        ];

    /**
     * 共通子支出カテゴリコード定義
     */
    const EXPENSE_CATEGORY_FOR_FOOD                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => '1101',
            'position'      => 1,
            'cat_name'      => '食材',
            'type_cd'       => self::OTHER_EXPENSE_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_FOOD_PETTY_CASH      =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => '1102',
            'position'      => 2,
            'cat_name'      => '食材小口現金',
            'type_cd'       => self::DAILY_EXPENSE_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS            =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => '1201',
            'position'      => 3,
            'cat_name'      => '資材',
            'type_cd'       => self::OTHER_EXPENSE_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS_PETTY_CASH =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => '1202',
            'position'      => 4,
            'cat_name'      => '資材小口現金',
            'type_cd'       => self::DAILY_EXPENSE_TYPE['type_cd'],
        ];

    const EXPENSE_CATEGORIES =
        [
            self::EXPENSE_CATEGORY_FOR_FOOD,
            self::EXPENSE_CATEGORY_FOR_FOOD_PETTY_CASH,
            self::EXPENSE_CATEGORY_FOR_MATERIALS,
            self::EXPENSE_CATEGORY_FOR_MATERIALS_PETTY_CASH,
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
    ];

    /**
     * 共通子収入カテゴリコード定義
     */
    const CHILD_INCOME_CATEGORY_FOR_STORE_SALE =
        [
            'parent_cat_cd' => self::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'],
            'cat_cd'        => '1101',
            'position'      => 1,
            'cat_name'      => '店舗売上',
            'type_cd'       => self::OTHER_INCOME_TYPE['type_cd'],
        ];

    const CHILD_INCOME_CATEGORY_FOR_DELIVERY_SALE =
        [
            'parent_cat_cd' => self::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'],
            'cat_cd'        => '1102',
            'position'      => 2,
            'cat_name'      => 'デリバリー売上',
            'type_cd'       => self::OTHER_INCOME_TYPE['type_cd'],
        ];

    const CHILD_INCOME_CATEGORIES =
        [
            self::CHILD_INCOME_CATEGORY_FOR_STORE_SALE,
            self::CHILD_INCOME_CATEGORY_FOR_DELIVERY_SALE,
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
}

