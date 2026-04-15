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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('youtube_id')->comment('ID do vídeo do YouTube');
            $table->string('thumbnail')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('destaque')->default(false);
            $table->enum('status', ['rascunho', 'publicado'])->default('publicado');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('publicado_em')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('destaque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
