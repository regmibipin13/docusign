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
        Schema::create('signed_documents', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // e.g., "Bipin's Signature"
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
            $table->json('signature_positions')->nullable(); // Store all signature positions for this signed document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signed_documents');
    }
};
