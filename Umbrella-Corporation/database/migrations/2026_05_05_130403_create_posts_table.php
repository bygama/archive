<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('security');
            $table->string('security_key')->index();
            $table->string('author');
            $table->string('document_id')->unique();
            $table->string('icon');
            $table->string('image')->nullable();
            $table->string('excerpt');
            $table->text('body');
            $table->string('facility');
            $table->date('published_at')->index();
            $table->date('last_revision');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
