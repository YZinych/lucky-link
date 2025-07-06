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
        Schema::create('lucky_attempts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lucky_link_id')->unsigned();
            $table->unsignedSmallInteger('number');
            $table->boolean('win');
            $table->decimal('amount', 8, 2)->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lucky_attempts');
    }
};
