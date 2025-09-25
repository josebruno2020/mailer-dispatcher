<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('webhook_url')->nullable();
            $table->jsonb('webhook_headers')->nullable();
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->string('webhook_url')->nullable();
            $table->jsonb('webhook_headers')->nullable();
        });

        Schema::table('emails', function (Blueprint $table) {
            $table->string('webhook_status')->nullable();
            $table->jsonb('webhook_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('accounts', ['webhook_url', 'webhook_headers']);
        Schema::dropColumns('templates', ['webhook_url', 'webhook_headers']);
        Schema::dropColumns('emails', ['webhook_status', 'webhook_data']);
    }
};
