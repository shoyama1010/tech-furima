<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

// return new class extends Migration
class AddTwoFactorColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->text('two_factor_secret')
        //         ->after('password')
        //         ->nullable();

        //     $table->text('two_factor_recovery_codes')
        //         ->after('two_factor_secret')
        //         ->nullable();

        //     if (Fortify::confirmsTwoFactorAuthentication()) {
        //         $table->timestamp('two_factor_confirmed_at')
        //             ->after('two_factor_recovery_codes')
        //             ->nullable();
        //     }
        // });
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn(array_merge([
        //         'two_factor_secret',
        //         'two_factor_recovery_codes',
        //     ], Fortify::confirmsTwoFactorAuthentication() ? [
        //         'two_factor_confirmed_at',
        //     ] : []));
        // });
        if (Schema::hasTable('users')) { // テーブルが存在するか確認
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']);
            });
        }
    }
};
