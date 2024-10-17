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
        Schema::connection('other')->create('kyde_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Account::class);
            $table->decimal('mden',8,3);
            $table->decimal('daen',8,3);
            $table->foreignIdFor(\App\Models\Kyde::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyde_data');
    }
};
