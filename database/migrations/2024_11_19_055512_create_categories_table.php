<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Category name
            $table->text('description')->nullable(); // Description
            $table->foreignId('parent_id')->nullable()->constrained('categories'); // Foreign key to parent category
            $table->foreignId('created_by')->constrained('users'); // Foreign key to User table (creator)
            $table->timestamps(); // Created_at and Updated_at timestamps

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
