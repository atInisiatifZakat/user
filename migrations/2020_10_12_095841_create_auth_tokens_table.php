<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

final class CreateAuthTokensTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_tokens', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->text('token');
            $table->dateTime('expired_at')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('auth_token_blacklists', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->json('values');
            $table->dateTime('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_tokens');
        Schema::dropIfExists('auth_token_blacklists');
    }
}
