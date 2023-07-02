<?php

namespace App\Http\Requests;

use App\Constants\CommonDatabaseConstants;
use Illuminate\Foundation\Http\FormRequest;

class CreateExpenseCategoryRequest extends FormRequest
{
    /**
     * リクエストの認証ルールを決定します。
     * @return bool
     */
    public function authorize(): bool
    {
        // ここではすべてのユーザーがこのリクエストを実行できるようにします。
        // 必要に応じて認証ロジックを追加してください。
        return true;
    }

    /**
     * リクエストに適用するバリデーションルールを取得します。
     * @return array
     */
    public function rules(): array
    {
        return [
            'parent_cat_cd' => ['required', 'integer',
                                'max:' . CommonDatabaseConstants::MAX_PARENT_EXPENSE_CATEGORY_CODE,
            ],
            'cat_name'      => ['required', 'max:10'],
            'type_cd'       => ['required', 'integer'],
        ];
    }
}
