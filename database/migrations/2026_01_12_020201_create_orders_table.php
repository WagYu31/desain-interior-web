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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('user_order_id')->nullable();

            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->string('full_address');
            $table->string('district');
            $table->string('city');
            $table->string('province');

            // HANYA DATA AWAL YANG TIDAK BERUBAH
            $table->string('client_type');
            $table->string('property_type');
            $table->json('design_type')->nullable();
            $table->string('room_count')->nullable();
            $table->string('business_needs')->nullable();
            $table->string('company_name')->nullable();
            $table->string('project_value')->nullable();
            $table->string('area_size')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable(); // Alasan batal tetap di sini untuk akses cepat

            $table->timestamp('order_date')->useCurrent();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};