<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create(\config('user.table_names.branches'), static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('type', 45);
            $table->string('name');

            $table->boolean('is_active');
            $table->boolean('is_head_office');

            $table->uuid('parent_id')->nullable();

            $table->timestamps();

            if(Schema::hasTable(\config('user.table_names.branches'))){
                $table->string('intranet_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\config('user.table_names.branches'));
    }
};
