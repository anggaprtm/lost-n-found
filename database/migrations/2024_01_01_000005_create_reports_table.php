<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('validator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('item_name');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->enum('type', ['lost', 'found']);
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->date('event_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
