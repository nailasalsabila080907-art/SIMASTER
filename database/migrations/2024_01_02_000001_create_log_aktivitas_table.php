<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id_log_aktivitas');

            $table->unsignedBigInteger('id_user')->nullable();

            $table->string('aktivitas', 50);
            $table->string('modul', 50)->nullable();
            $table->string('deskripsi', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['id_user', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};