<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\config('user.table_names.employees'), static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('nip', 36)->unique();

            $table->foreignUuid('branch_id');

            $table->string('name');
            $table->string('email', 150)->unique();

            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('intranet_id')->nullable();

            $table->timestamps();
        });

        Schema::create(\config('user.table_names.volunteers'), static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('employee_id');

            $table->string('name');
            $table->string('email', 150)->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('volunteers');
    }
};
