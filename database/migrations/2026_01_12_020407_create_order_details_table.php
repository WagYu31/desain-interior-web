<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Data Progres yang Dinamis
            $table->string('status');
            $table->text('progress_description')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->json('photos')->nullable(); // Menyimpan array path foto
            $table->json('team_member_ids')->nullable(); // Menyimpan array ID anggota tim

            $table->timestamps(); // Tanggal update
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};