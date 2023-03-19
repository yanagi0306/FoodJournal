<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('expense_type', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('支出タイプID');
            $table->string('type_cd', 15)->comment('支出タイプコード');
            $table->string('type_name', 15)->comment('支出タイプ名');
            $table->string('description', 255)->nullable()->comment('支出タイプ説明');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique('type_cd');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE expense_type COMMENT '支出タイプテーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_type');
    }
};
