<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->date('session_date');
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->boolean('is_makeup')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['class_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
