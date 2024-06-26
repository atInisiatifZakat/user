<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create(\config('user.table_names.users'), static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->timestamp('deactivated_at')->nullable();
            $table->nullableUuidMorphs('loginable');
            $table->rememberToken();
            $table->string('pin')->nullable();
            $table->timestamp('pin_last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
