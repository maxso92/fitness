<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['time', 'visits']);
            $table->integer('duration_days')->nullable()->comment('Для временных абонементов');
            $table->integer('visits_count')->nullable()->comment('Для абонементов на посещения');
            $table->boolean('has_trainer_service')->default(false);
            $table->foreignId('trainer_id')->nullable()->constrained('users')->comment('Тренер, если включена услуга');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
