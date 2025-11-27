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
        Schema::connection('mysql')->create('events', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->string('training_id')->index();
            $table->string('location_id')->index();
            $table->string('organizer_id')->index()->nullable();
            $table->string('trainer_id')->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
