<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('client_subscriptions', function (Blueprint $table) {
            $table->id();

            // Связь с пользователем (клиентом)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Связь с абонементом
            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->onDelete('cascade');

            // Основные даты
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Данные по посещениям
            $table->integer('remaining_visits')->nullable();

            // Связь с тренером (если есть)
            $table->foreignId('trainer_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Статус
            $table->boolean('is_active')->default(true);

            // Временные метки
            $table->timestamps();

            // Индексы
            $table->index(['user_id', 'is_active']);
            $table->index(['subscription_id']);
            $table->index(['trainer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_subscriptions');
    }
};
