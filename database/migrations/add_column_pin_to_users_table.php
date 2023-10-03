<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table(\config('user.table_names.users'), static function (Blueprint $table): void {
            $table->string('pin');
        });
    }

    public function down(): void
    {
        Schema::table(\config('user.table_names.users'), function (Blueprint $table): void {
            $table->dropColumn(['pin']);
        });
    }
};
