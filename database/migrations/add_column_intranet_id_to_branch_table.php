<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table(\config('user.table_names.branches'), static function (Blueprint $table): void {
            $table->string('intranet_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(\config('user.table_names.branches'), function (Blueprint $table): void {
            $table->dropColumn(['intranet_id']);
        });
    }
};
