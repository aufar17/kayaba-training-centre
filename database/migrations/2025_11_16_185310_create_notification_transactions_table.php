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
        Schema::create('notification_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id')->index();
            $table->string('target')->index();
            $table->tinyInteger('is_read')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_transactions');
    }
};
