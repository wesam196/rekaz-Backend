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
        Schema::create('blobs', function (Blueprint $table) {
            $table->id();
            $table->string('blob_id')->unique();
            $table->string('name')->nullable();
            $table->string('backend');
            $table->string('path')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('content_type')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blobs');
    }
};
