<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('birthday')->nullable();
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic')->nullable();
            $table->text('information')->nullable();
            $table->string('role')->default('user');
            $table->unsignedBigInteger('gym_id')->default(0);
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('isDeleted')->default(false);
            $table->string('status');
            $table->string('avatar')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        // Индексы для ускорения запросов
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('status');
            $table->index('last_seen_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
