<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45); // IPv6 может быть длинным
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->timestamp('login_time')->useCurrent();
            $table->timestamp('logout_time')->nullable();
            $table->timestamps();
        });

        // Индексы для быстрого поиска
        Schema::table('auth_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('login_time');
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_logs');
    }
};
