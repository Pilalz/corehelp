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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();            
            $table->foreignId('assigned_to')->nullable()->constrained('users');            
            $table->foreignId('category_id')->nullable()->constrained();

            // Konten Utama (Masalah)
            $table->string('subject'); // Judul
            $table->text('content');   // Deskripsi Masalah Lengkap
            
            // Multi Attachments (Support JSON array)
            $table->json('attachments')->nullable(); 

            // Status & Metadata
            $table->string('status')->default('open'); // open, in_progress, resolved
            $table->string('priority')->default('medium'); // low, medium, high
            
            // BAGIAN PENTING UNTUK AI (Diisi saat status = resolved)
            $table->text('resolution_summary')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
