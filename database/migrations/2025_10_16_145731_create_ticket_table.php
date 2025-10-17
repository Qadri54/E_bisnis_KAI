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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('kereta_id');
            $table->unsignedBigInteger('destinasi_id');
            $table->timestamps();

            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal_kereta')->onDelete('cascade');
            $table->foreign('kereta_id')->references('kereta_id')->on('kereta')->onDelete('cascade');
            $table->foreign('destinasi_id')->references('destinasi_id')->on('destinasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
