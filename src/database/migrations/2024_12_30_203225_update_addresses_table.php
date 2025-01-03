<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            // カラム削除
            $table->dropColumn(['address_line1', 'address_line2', 'city', 'state', 'country']);

            // 新しいカラム追加
            $table->string('address')->nullable()->after('postal_code'); // 郵便番号の後にaddressを追加
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'address_line1')) {
                $table->string('address_line1')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'address_line2')) {
                $table->string('address_line2')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'country')) {
                $table->string('country')->nullable();
            }

            // 削除された address カラムの削除も確認
            if (Schema::hasColumn('addresses', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
}
