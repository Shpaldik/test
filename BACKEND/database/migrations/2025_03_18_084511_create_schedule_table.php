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
    Schema::create('schedule', function (Blueprint $table) {
        $table->id();
        $table->string('line');
        $table->string('from_place');  // Use unsignedBigInteger for foreign key
        $table->string('to_place');    // Use unsignedBigInteger for foreign key
        $table->time('departure_time');
        $table->time('arrival_time');
        $table->decimal('distance', 8, 2);
        $table->decimal('speed', 5, 2);
        $table->string('status');
        $table->timestamps();

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
