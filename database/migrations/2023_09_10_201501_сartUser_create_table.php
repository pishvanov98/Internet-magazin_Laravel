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
        Schema::create('сartUser', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('session_id',128)->nullable();
            $table->text('Cart');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('сartUser');
    }
};
