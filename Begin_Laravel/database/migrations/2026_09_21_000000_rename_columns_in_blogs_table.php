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
        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('content', 'description');
            $table->renameColumn('image_path', 'thumbnail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('description', 'content');
            $table->renameColumn('thumbnail', 'image_path');
        });
    }
};
