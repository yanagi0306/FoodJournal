<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // config/logging.php の app チャンネル設定を更新
        config([
            'logging.channels.app.logPath' => storage_path('logs/test.log'),
        ]);
    }
}
