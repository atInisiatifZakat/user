<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = \config('user.table_names.users', 'users');

        Schema::create($tableName, function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->timestamp('deactivated_at')->nullable();
            $table->nullableUuidMorphs('loginable');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            \config('user.table_names.users', 'users')
        );
    }
};
