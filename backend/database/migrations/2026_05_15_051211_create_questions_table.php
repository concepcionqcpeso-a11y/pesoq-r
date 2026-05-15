<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('role_key');           // e.g. "bpo"
            $table->string('role_label');         // e.g. "Customer Service / BPO"
            $table->text('question_en');          // English question
            $table->text('question_tl');          // Tagalog question
            $table->text('tip_en');               // English tip
            $table->text('tip_tl');               // Tagalog tip
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('questions');
    }
};