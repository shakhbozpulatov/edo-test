<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_journal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_number')->nullable();
            $table->text('short_description')->nullable();
            $table->string('status')->default('draft'); // draft, signed, deleted
            $table->string('main_file_path')->nullable();
            $table->string('main_file_name')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('document_templates')->nullOnDelete();
            $table->string('qr_code_path')->nullable();
            $table->jsonb('qr_position')->nullable(); // {page, x, y}
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
