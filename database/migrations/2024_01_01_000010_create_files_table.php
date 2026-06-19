<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('slug');
            $table->string('ext');
            $table->string('file');
            $table->string('domain', 1000)->nullable();
            $table->integer('size');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('description')->nullable();
            $table->integer('sort')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};