<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fileables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->unsignedBigInteger('fileable_id');
            $table->string('fileable_type');
            $table->string('fileable_key');
            $table->integer('sort')->default(0);
            $table->timestamps();

            $table->index(['fileable_id', 'fileable_type', 'fileable_key'], 'fileables_morph_key_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fileables');
    }
};