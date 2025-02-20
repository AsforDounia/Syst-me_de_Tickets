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
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');;
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('SET NULL');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('closed_at')->nullable();
            $table->integer('resolution_time')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
