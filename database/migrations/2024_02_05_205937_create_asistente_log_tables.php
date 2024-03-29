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
        Schema::create('asistente_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('lot', 25)->index();
            $table->string('manifiesto', 50);
            $table->longText('request_body')->nullable();
            $table->longText('response_body')->nullable();
            $table->text('response_code')->nullable();
            $table->text('response_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistente_log');
    }
};
