<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableRemovePhoneAddPostalCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone'); // 「phone」カラムを削除
            $table->string('postal_code', 10)->nullable()->after('email'); // 新たに「postal_code」を追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email'); // 「phone」を復元
            $table->dropColumn('postal_code'); // 「postal_code」を削除
        });
    }
}
