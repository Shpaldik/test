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
        Schema::create('route_selection_histores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_place_id');
            $table->unsignedBigInteger('to_place_id');
            $table->text('schedule_ids');
            $table->timestamps();

            $table->foreign('from_place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('to_place_id')->references('id')->on('places')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_selection_histores');
    }
};
