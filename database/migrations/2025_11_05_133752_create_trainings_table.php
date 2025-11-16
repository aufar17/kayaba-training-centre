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
        Schema::connection('mysql')->create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->text('purpose')->nullable();
            $table->string('golongan')->nullable();
            $table->text('day_duration')->nullable();
            $table->text('time_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
