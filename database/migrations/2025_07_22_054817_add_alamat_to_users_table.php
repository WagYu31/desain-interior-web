<?php

use Illuminate\database\migrations\Migration;
use Illuminate\database\schema\Blueprint;
use Illuminate\support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'alamat' setelah kolom 'password'
            $table->text('alamat')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('alamat');
        });
    }
};