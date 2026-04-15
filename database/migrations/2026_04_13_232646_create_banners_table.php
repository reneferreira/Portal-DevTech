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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('imagem');
            $table->string('link')->nullable();
            $table->enum('posicao', ['topo', 'sidebar', 'entre_posts', 'footer'])->default('sidebar');
            $table->enum('tipo', ['imagem', 'video', 'html'])->default('imagem');
            $table->text('html_code')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->integer('clicks')->default(0);
            $table->integer('visualizacoes')->default(0);
            $table->timestamps();
            
            $table->index('posicao');
            $table->index('ativo');
            $table->index('ordem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
