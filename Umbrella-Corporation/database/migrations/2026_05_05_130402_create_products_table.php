<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->string('id_code')->unique();
            $table->unsignedBigInteger('price');
            $table->string('stock');
            $table->string('status');
            $table->string('status_key')->index();
            $table->string('clearance');
            $table->string('clearance_key')->index();
            $table->string('facility');
            $table->string('risk_index');
            $table->string('containment_class');
            $table->string('format');
            $table->string('color_visual')->nullable();
            $table->string('image');
            $table->string('icon');
            $table->text('description');
            $table->text('body');
            $table->json('dossier');
            $table->date('last_revision');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
