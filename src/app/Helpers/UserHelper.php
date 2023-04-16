<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class UserHelper
{

    public static function getUser($user): array
    {
        // 初期化
        $userArray = array();

        if ($user) {
            // 所属会社情報取得
            $company = Company::find($user->company_id);

            // 所属店舗情報取得
            $store = Store::find($user->store_id);

            $userArray = $user->toArray();
            $userArray['store_name'] = $store?->store_name;
            $userArray['company_name'] = $company?->company_name;

            // 所属会社に関連するストアIDを取得
            if ($company) {
                $userArray['store_ids'] = $company->stores->map(function ($store) {
                    return $store->id;
                })->toArray();
            } else {
                $userArray['store_ids'] = array();
            }
        }

        return $userArray;
    }
}
