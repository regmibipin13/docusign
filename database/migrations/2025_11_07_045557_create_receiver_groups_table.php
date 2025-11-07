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
        Schema::create('receiver_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('receiver_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_group_id')->constrained()->onDelete('cascade');
            $table->enum('recipient_type', ['email', 'registered_user']);
            $table->string('recipient_value'); // email address or user_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiver_group_members');
        Schema::dropIfExists('receiver_groups');
    }
};
