<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql-laravel')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();

            // This block is equivalent to $table->morphs('tokenable'); but with uuid
            $table->uuid('tokenable_id');
            $table->string('tokenable_type');
            $table->index(['tokenable_type', 'tokenable_id']);

            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql-laravel')->dropIfExists('personal_access_tokens');
    }
};
