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
          Schema::create('posts', function (Blueprint $table) {
            
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('resumo')->nullable();
            $table->longText('conteudo');
            $table->string('imagem')->nullable();
            $table->string('imagem_thumbnail')->nullable(); // Thumbnail para listagens
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('views')->default(0);
            $table->timestamp('publicado_em')->nullable();
            $table->enum('status', ['rascunho', 'publicado'])->default('rascunho');
            $table->boolean('destaque')->default(false); // Post em destaque
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
