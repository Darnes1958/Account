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
        Schema::connection('other')->create('accounts', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('num');
            $table->string('name');
            $table->integer('level');
            $table->string('grand_id', 36)->nullable();
            $table->string('father_id', 36)->nullable();
            $table->string('son_id', 36)->nullable();
            $table->integer('is_active');
            $table->timestamps();
        });
        Schema::connection('other')->table('accounts',function (Blueprint $table){
            $table->foreign('grand_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('father_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('son_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
