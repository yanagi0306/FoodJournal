<?php

namespace App\Http\Controllers;

use App\Constants\Common;
use App\Helpers\UserHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ベースコントローラークラス
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected ?string $progName = null;
    protected ?string $methodName = null;
    protected ?array $userInfo = null;
    protected string $logPath;


    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->applyMiddleware();
    }

    /**
     * ミドルウェアを適用する
     */
    private function applyMiddleware(): void
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $this->setUserInfo($user ? UserHelper::getUser($user) : null);
            $this->setMethodName($request->route()->getActionMethod());
            $this->setProgName(class_basename($request->route()->getController()));
            $this->logPath = $this->getLogPath();
            config([
                'logging.channels.app.logPath' => $this->logPath,
                'logging.channels.app.userId' => $this->userInfo['id'] ?? null,
                'logging.channels.app.progName' => $this->progName ?? null,
            ]);
            Log::info('[START]>>>>>>>>');

            return $next($request);
        });
    }


    /**
     * ユーザー情報を設定する
     *
     * @param ?array $userInfo ユーザー情報
     */
    private function setUserInfo(?array $userInfo): void
    {
        $this->userInfo = $userInfo;
    }

    /**
     *
     * @param string $methodName メソッド名
     */
    private function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     *
     * @param string $progName プログラム名
     */
    private function setProgName(string $progName): void
    {
        $this->progName = $progName;
    }

    protected function getLogPath(): string
    {
        $date = date('Ymd');

        if ($this->userInfo !== null) {
            $logDir = Common::LOGS_DIR . "/{$this->userInfo['company_id']}";

            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }

            return $logDir . "/{$this->progName}_{$date}.log";
        }

        return "logs/app_{$date}.log";
    }

    /**
     * デストラクタ
     */
    public function __destruct()
    {
        Log::info("[END]<<<<<<<<<<");
    }
}
