<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->json('data');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->boolean('is_spam')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['form_id', 'created_at']);
            $table->index(['form_id', 'is_spam']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
