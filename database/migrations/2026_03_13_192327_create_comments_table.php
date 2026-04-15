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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('email');
            $table->text('comentario');
            $table->boolean('aprovado')->default(false);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Índices para buscas mais rápidas
            $table->index(['post_id', 'aprovado']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
