<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();

            $table->enum('role', ['owner', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();

            $table->index(['user_id', 'left_at']);

            $table->timestamps();

            $table->unique(['user_id', 'colocation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
