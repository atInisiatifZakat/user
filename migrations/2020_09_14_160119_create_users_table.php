<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Inisiatif\Package\User\Models\AbstractUser;
use Inisiatif\Package\User\Providers\UserServiceProvider;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (UserServiceProvider::$isRunMigration === false) {
            return;
        }

        Schema::create('users', function (Blueprint $table): void {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
