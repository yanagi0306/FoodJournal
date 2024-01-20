<?php

namespace App\Constants;

class CommonDatabaseConstants
{
    /**
     * 共通収支タイプ定義
     */
    const FIXED_TYPE   = '固定収支';
    const MONTHLY_TYPE = '月次収支';
    const DAILY_TYPE   = '日次収支';

    /**
     * 共通収支タイプ定義
     */

    /**
     * 共通収支カテゴリのルール
     * 親、子それぞれのカテゴリコードの最大値以上の数値は特殊値として例外処理で使用
     * それらの追加削除変更は画面からは不可とする
     */

    /**
     * 共通子カテゴリコード定義(特殊数値)
     * 以下は固定の値
     * 子カテゴリ「99:その他」は親カテゴリ作成時に自動的に作成されるものとする
     * 子カテゴリ「50:小口現金」は抽出を求められることを考慮して固定とする
     * 子カテゴリ「98:デリバリー」は仕様が特殊のため固定とする
     */
    const CATEGORY_CODE_FOR_PETTY_CASH   = 50;
    const CATEGORY_CODE_FOR_DELIVERY     = 98;
    const CATEGORY_CODE_FOR_OTHER        = 99;
    const PARENT_CATEGORY_CODE_FOR_OTHER = 99;

    /**
     * 共通子支出カテゴリコード定義(その他)
     * 以下は親カテゴリ作成時に自動的に作成されるものとする
     */
    const CATEGORY_FOR_OTHER =
        [
            'cat_cd'   => self::CATEGORY_CODE_FOR_OTHER,
            'cat_name' => 'その他',
            'type_cd'  => self::DAILY_TYPE['type_cd'],
        ];

    /**
     * 共通親支出カテゴリコード定義
     * 食材、資材、デリバリー、その他は必須項目として固定値で定義する
     */
    const PARENT_EXPENSE_CATEGORY_FOR_FOOD      =
        [
            'cat_cd'   => 1,
            'cat_name' => '食材',
        ];
    const PARENT_EXPENSE_CATEGORY_FOR_MATERIALS =
        [
            'cat_cd'   => 11,
            'cat_name' => '資材',
        ];
    const PARENT_EXPENSE_CATEGORY_FOR_DELIVERY  =
        [
            'cat_cd'   => self::CATEGORY_CODE_FOR_DELIVERY,
            'cat_name' => 'デリバリー',
        ];
    const PARENT_CATEGORY_FOR_OTHER             =
        [
            'cat_cd'   => self::PARENT_CATEGORY_CODE_FOR_OTHER,
            'cat_name' => 'その他',
        ];
    const PARENT_EXPENSE_CATEGORIES             =
        [
            self::PARENT_EXPENSE_CATEGORY_FOR_FOOD,
            self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS,
            self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY,
            self::PARENT_CATEGORY_FOR_OTHER,
        ];

    /**
     * 共通子支出カテゴリコード定義
     * 以下子カテゴリは必須項目として定義する
     * todo.削除 親カテゴリ「デリバリー」はUBER以外はリリース後削除
     */
    const EXPENSE_CATEGORY_FOR_FOOD                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => 10,
            'cat_name'      => '食材',
            'type_cd'       => self::DAILY_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_FOOD_PETTY_CASH      =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
            'cat_cd'        => self::CATEGORY_CODE_FOR_PETTY_CASH,
            'cat_name'      => '小口現金',
            'type_cd'       => self::DAILY_TYPE['type_cd'],

        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS            =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => 10,
            'cat_name'      => '資材',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_MATERIALS_PETTY_CASH =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
            'cat_cd'        => self::CATEGORY_CODE_FOR_PETTY_CASH,
            'cat_name'      => '小口現金',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_UBER                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => 10,
            'cat_name'      => 'UBER',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_DEMAE_KAN            =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => 11,
            'cat_name'      => '出前館',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_WOLT                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => 12,
            'cat_name'      => 'Wolt',
            'type_cd'       => self::MONTHLY_TYPE['type_cd'],
        ];
    const EXPENSE_CATEGORY_FOR_TGAL                 =
        [
            'parent_cat_cd' => self::PARENT_EXPENSE_CATEGORY_FOR_DELIVERY['cat_cd'],
            'cat_cd'        => 13,
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
            // todo.以下は削除予定
            self::EXPENSE_CATEGORY_FOR_DEMAE_KAN,
            self::EXPENSE_CATEGORY_FOR_WOLT,
            self::EXPENSE_CATEGORY_FOR_TGAL,
        ];


    /**
     * 共通親収入カテゴリコード定義
     * 以下親収入カテゴリは必須項目として固定で定義する
     */
    const PARENT_INCOME_CATEGORY_FOR_SALE =
        [
            'cat_cd'   => 11,
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
            'cat_cd'        => 10,
            'cat_name'      => '店舗',
            'type_cd'       => self::DAILY_TYPE['type_cd'],
        ];
    const INCOME_CATEGORY_FOR_DELIVERY_SALE =
        [
            'parent_cat_cd' => self::PARENT_INCOME_CATEGORY_FOR_SALE['cat_cd'],
            'cat_cd'        => 11,
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
            'payment_cd'      => 10,
            'property_name'   => 'cash',
            'payment_name'    => '現金',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 11,
            'property_name'   => 'creditCard',
            'payment_name'    => 'クレジット',
            'commission_rate' => 0.30,
        ],
        [
            'payment_cd'      => 12,
            'property_name'   => 'points',
            'payment_name'    => 'ポイント',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 13,
            'property_name'   => 'electronicMoney',
            'payment_name'    => '電子マネー',
            'commission_rate' => 0.3,
        ],
        [
            'payment_cd'      => 14,
            'property_name'   => 'giftCertNoChange',
            'payment_name'    => '商品券釣無',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 15,
            'property_name'   => 'giftCertWithChange',
            'payment_name'    => '商品券釣有',
            'commission_rate' => 0,
        ],
        [
            'payment_cd'      => 16,
            'property_name'   => 'delivery',
            'payment_name'    => 'デリバリー',
            'commission_rate' => 0.30,
        ],
    ];


    /**
     * テスト用収支カテゴリコード定義
     */
    const PARENT_INCOME_CATEGORY_FOR_TEST = [
        ['cat_cd' => '11', 'cat_name' => '支払利息'],
    ];

    const PARENT_EXPENSE_CATEGORIES_FOR_TEST = [
        ['cat_cd' => 12, 'cat_name' => '役員報酬'],
        ['cat_cd' => 13, 'cat_name' => '給与手当'],
        ['cat_cd' => 14, 'cat_name' => '法廷福利費'],
        ['cat_cd' => 15, 'cat_name' => '預り金'],
        ['cat_cd' => 16, 'cat_name' => '賞与'],
        ['cat_cd' => 17, 'cat_name' => '広告宣伝費'],
        ['cat_cd' => 18, 'cat_name' => '交際費'],
        ['cat_cd' => 19, 'cat_name' => '会議費'],
        ['cat_cd' => 20, 'cat_name' => '旅費交通費'],
        ['cat_cd' => 21, 'cat_name' => '通信費'],
        ['cat_cd' => 22, 'cat_name' => '損害保険料'],
        ['cat_cd' => 23, 'cat_name' => '荷造運賃'],
        ['cat_cd' => 24, 'cat_name' => '消耗品'],
        ['cat_cd' => 25, 'cat_name' => '水道光熱費'],
        ['cat_cd' => 25, 'cat_name' => '金券手数料'],
        ['cat_cd' => 25, 'cat_name' => '支払手数料'],
        ['cat_cd' => 26, 'cat_name' => '地代家賃'],
        ['cat_cd' => 27, 'cat_name' => '車両費'],
        ['cat_cd' => 28, 'cat_name' => '雑費'],
        ['cat_cd' => 29, 'cat_name' => '税金'],
        ['cat_cd' => 30, 'cat_name' => '支払手数料'],
        ['cat_cd' => 31, 'cat_name' => '顧問料'],
    ];

    const INCOME_CATEGORY_FOR_TEST = [
        ['parent_cat_cd' => 11, 'cat_name' => '栃木銀行利息', 'type_cd' => 11],
        ['parent_cat_cd' => 11, 'cat_name' => '金融公庫利息', 'type_cd' => 11],
    ];

    const EXPENSE_CATEGORY_FOR_TEST = [
        ['parent_cat_cd' => 12, 'cat_name' => '役員報酬', 'type_cd' => 10],
        ['parent_cat_cd' => 13, 'cat_name' => '社員給与手当', 'type_cd' => 10],
        ['parent_cat_cd' => 13, 'cat_name' => 'アルバイト給与手当', 'type_cd' => 12],
        ['parent_cat_cd' => 14, 'cat_name' => '厚生年金', 'type_cd' => 10],
        ['parent_cat_cd' => 14, 'cat_name' => '労働保険料', 'type_cd' => 10],
        ['parent_cat_cd' => 15, 'cat_name' => '所得税', 'type_cd' => 10],
        ['parent_cat_cd' => 15, 'cat_name' => '住民税', 'type_cd' => 10],
        ['parent_cat_cd' => 16, 'cat_name' => 'インセンティブ', 'type_cd' => 11],
        ['parent_cat_cd' => 16, 'cat_name' => '決算賞与', 'type_cd' => 11],
        ['parent_cat_cd' => 17, 'cat_name' => 'LINE公式', 'type_cd' => 11],
        ['parent_cat_cd' => 17, 'cat_name' => '印刷代', 'type_cd' => 11],
        ['parent_cat_cd' => 17, 'cat_name' => 'アイフラッグ（保守費用等）', 'type_cd' => 11],
        ['parent_cat_cd' => 18, 'cat_name' => '交際費', 'type_cd' => 12],
        ['parent_cat_cd' => 19, 'cat_name' => '会議費', 'type_cd' => 12],
        ['parent_cat_cd' => 20, 'cat_name' => '従業員定期代', 'type_cd' => 10],
        ['parent_cat_cd' => 20, 'cat_name' => '駐車場代', 'type_cd' => 12],
        ['parent_cat_cd' => 21, 'cat_name' => 'NTT ME（WLKWAK光）', 'type_cd' => 10],
        ['parent_cat_cd' => 21, 'cat_name' => 'NTT東日本（BiZiMo光)', 'type_cd' => 10],
        ['parent_cat_cd' => 21, 'cat_name' => 'アスピット', 'type_cd' => 10],
        ['parent_cat_cd' => 21, 'cat_name' => 'イデアレコード（予約システム）', 'type_cd' => 10],
        ['parent_cat_cd' => 21, 'cat_name' => 'USEN（レジ）', 'type_cd' => 10],
        ['parent_cat_cd' => 22, 'cat_name' => 'USEN（保険）', 'type_cd' => 10],
        ['parent_cat_cd' => 23, 'cat_name' => '郵送料', 'type_cd' => 12],
        ['parent_cat_cd' => 24, 'cat_name' => 'KYPC（害虫駆除）', 'type_cd' => 10],
        ['parent_cat_cd' => 24, 'cat_name' => '堀商店', 'type_cd' => 10],
        ['parent_cat_cd' => 24, 'cat_name' => 'パックマーケット', 'type_cd' => 10],
        ['parent_cat_cd' => 24, 'cat_name' => 'ダスキン', 'type_cd' => 10],
        ['parent_cat_cd' => 24, 'cat_name' => 'サンライズ', 'type_cd' => 10],
        ['parent_cat_cd' => 24, 'cat_name' => '小口現金', 'type_cd' => 12],
        ['parent_cat_cd' => 25, 'cat_name' => '水道代', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => '電力代', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => '動力代', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => 'ガス代', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => 'こしがやプレミアム商品券（紙）', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => 'こしがやプレミアム商品券（電子）', 'type_cd' => 11],
        ['parent_cat_cd' => 25, 'cat_name' => 'Go To Eat食事券', 'type_cd' => 11],
        ['parent_cat_cd' => 26, 'cat_name' => '地代家賃', 'type_cd' => 10],
        ['parent_cat_cd' => 27, 'cat_name' => '燃料費', 'type_cd' => 12],
        ['parent_cat_cd' => 28, 'cat_name' => 'SCS（ごみ処理）', 'type_cd' => 11],
        ['parent_cat_cd' => 29, 'cat_name' => '税金', 'type_cd' => 11],
        ['parent_cat_cd' => 30, 'cat_name' => '栃木銀行ダイレクト', 'type_cd' => 11],
        ['parent_cat_cd' => 30, 'cat_name' => 'ダイレクトサービス', 'type_cd' => 11],
        ['parent_cat_cd' => 30, 'cat_name' => 'クレジットカード', 'type_cd' => 11],
        ['parent_cat_cd' => 30, 'cat_name' => 'PayPay', 'type_cd' => 11],
        ['parent_cat_cd' => 31, 'cat_name' => '税理顧問料', 'type_cd' => 10],
        ['parent_cat_cd' => 31, 'cat_name' => '社労顧問料', 'type_cd' => 10],
    ];


}
