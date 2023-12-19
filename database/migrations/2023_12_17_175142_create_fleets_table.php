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
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owned_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('driven_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();
            $table->string('registration_num');
            $table->enum('permit_type', ['All India', 'All Assam']);
            $table->date('insurance_expiry');
            $table->date('pollution_expiry');
            $table->date('fitness_expiry');
            $table->foreignId('current_location')
                ->constrained('locations')
                ->cascadeOnDelete();
            $table->decimal('max_height');
            $table->decimal('max_length');
            $table->decimal('max_width');
            $table->decimal('available_height')
                ->nullable();
            $table->decimal('available_length')
                ->nullable();
            $table->decimal('available_width')
                ->nullable();
            $table->enum('status', ['Available', 'Assigned']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleets');
    }
};
