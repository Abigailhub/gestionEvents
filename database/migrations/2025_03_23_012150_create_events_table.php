<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->date('date');
        $table->time('time');
        $table->string('location');
        $table->unsignedBigInteger('user_id'); // Le créateur de l'événement
        $table->timestamps();

        // Clé étrangère
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    public function down(): void {
        Schema::dropIfExists('events');
    }
};
