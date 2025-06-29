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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('driver_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('fare', 8, 2);
            $table->string('status')->default('pending');
            $table->decimal('tip', 6, 2)->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('trip_requests')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
