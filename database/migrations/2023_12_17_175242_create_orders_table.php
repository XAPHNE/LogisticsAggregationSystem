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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_location')
                ->constrained('locations')
                ->cascadeOnDelete();
            $table->foreignId('destination_location')
                ->constrained('locations')
                ->cascadeOnDelete();
            $table->decimal('distance');
            $table->foreignId('fleet_id')
                ->nullable()
                ->constrained('fleets')
                ->cascadeOnDelete();
            $table->decimal('weight')
                ->nullable();
            $table->date('load_at');
            $table->decimal('price');
            $table->enum('status', ['Open', 'Accepted', 'Transit', 'Completed', 'Cancelled'])
                ->default('Open');
            $table->foreignId('order_placed_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->softDeletes();
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
