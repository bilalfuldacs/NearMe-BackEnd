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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('Name')->nullable();
            $table->string('Type')->nullable();
            $table->string('Location')->nullable();
            $table->string('Gender')->nullable();
            $table->string('TotalPeople')->nullable();
            $table->date('FromDate')->nullable();
            $table->date('ToDate')->nullable();
            $table->string('AgeGroup')->nullable();
            $table->string('Country')->nullable();
            $table->string('Street')->nullable();
            $table->string('City')->nullable();
            $table->string('Hausnumber')->nullable();
            $table->string('PostalCode')->nullable();
            $table->text('EventDescription')->nullable(); // Changed to text from date
            $table->string('Email')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Whatsapp')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
     
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