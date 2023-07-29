<?php

namespace App\Constants;

class AspitConstants
{
    const ASPIT_SYSTEM_NAME = 'ASPIT';

    const ASPIT_CSV_SKIP_ROW  = 1;
    const ASPIT_CSV_ROW_COUNT = 61;
    const ASPIT_CSV_ENCODING  = 'CP932';

    const CATEGORY_MAPS_FROM_ASPIT_TO_DB   = [
        self::FOOD_CATEGORY_MAP_FROM_ASPIT,
        self::MATERIAL_CATEGORY_MAP_FROM_ASPIT,
    ];
    const FOOD_CATEGORY_MAP_FROM_ASPIT     = [
        'aspit_category_cd'          => '1000',
        'parent_expense_category_cd' => CommonDatabaseConstants::EXPENSE_CATEGORY_FOR_FOOD['parent_cat_cd'],
        'expense_category_cd'        => CommonDatabaseConstants::EXPENSE_CATEGORY_FOR_FOOD['cat_cd'],
    ];
    const MATERIAL_CATEGORY_MAP_FROM_ASPIT = [
        'aspit_category_cd'          => '2000',
        'parent_expense_category_cd' => CommonDatabaseConstants::EXPENSE_CATEGORY_FOR_MATERIALS['parent_cat_cd'],
        'expense_category_cd'        => CommonDatabaseConstants::EXPENSE_CATEGORY_FOR_MATERIALS['cat_cd'],
    ];
}
