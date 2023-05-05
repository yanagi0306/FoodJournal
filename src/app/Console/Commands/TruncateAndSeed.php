<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TruncateAndSeed extends Command
{
    protected $signature = 'db:truncate-and-seed';

    protected $description = 'Truncate all tables and seed the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        // migrateを実行
        $this->comment('Running migrate...');
        Artisan::call('migrate');

        // テーブル名の配列を指定
        $tables = [
            'users',
            'company',
            'store',
            'payment_method',
        ];

        // PostgreSQLでは、FOREIGN_KEY_CHECKSの代わりにDISABLE TRIGGERを使用
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE \"$table\" DISABLE TRIGGER ALL;");
        }

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // PostgreSQLでは、FOREIGN_KEY_CHECKSの代わりにENABLE TRIGGERを使用
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE \"$table\" ENABLE TRIGGER ALL;");
        }

        $this->comment('Tables truncated.');

        // rollbackを実行
        $this->comment('Rolling back migrations...');
        Artisan::call('migrate:rollback');

        // migrateを実行
        $this->comment('Running migrate...');
        Artisan::call('migrate');

        // db:seedを実行
        $this->comment('Seeding database...');
        Artisan::call('db:seed');
    }
}
