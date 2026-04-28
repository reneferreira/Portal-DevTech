<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120);
            $table->string('body', 240);
            $table->string('url', 500)->default('/');
            $table->string('icon', 500)->nullable();
            $table->string('badge', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_messages');
    }
};
