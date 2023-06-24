<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserHelper
{

    public static function getUser($user): ?array
    {

        // ログインチェック
        if (!Auth::check()) {
            return null;
        }

        // 初期化
        $userArray = [];

        if ($user) {
            // 所属会社情報取得
            $company = Company::find($user->company_id);

            // 所属店舗情報取得
            $store = Store::find($user->store_id);

            $userArray                 = $user->toArray();
            $userArray['store_name']   = $store?->store_name;
            $userArray['company_name'] = $company?->company_name;

        }

        return $userArray;
    }
}
