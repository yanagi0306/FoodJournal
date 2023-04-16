<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Traits\LogTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, LogTrait;

    public ?string $progName = null;
    public ?string $methodName = null;
    public ?array $userInfo = null;

    public function __construct()
    {
        // 共通の定数ファイルを読み込む
        require_once __DIR__ . '/../../Constants/Constants.php';

        $this->middleware(function ($request, $next) {
            // ユーザー情報を定義
            $user = Auth::user();
            if ($user) {
                $this->userInfo = UserHelper::getUser($user);
                $this->methodName = $request->route()->getActionMethod();
                $this->progName = class_basename($request->route()->getController());

                // logPathを設定
                $this->setLogPath(
                    LOGS_DIR . "/{$this->userInfo['company_id']}/" . $this->progName . '_' . date('Ymd') . '.log'
                );

                $this->setLog("[START/{$this->methodName}/user_id:{$this->userInfo['id']}]>>>>>>>>");
            }
            return $next($request);
        });
    }

    public function __destruct()
    {
        $this->setLog("[END  /{$this->methodName}/user_id:{$this->userInfo['id']}]<<<<<<<<");
    }
}
