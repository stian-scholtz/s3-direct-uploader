<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        if(!Schema::hasTable('files')){
            Schema::create('files', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('extension', 10);
                $table->unsignedInteger('size');
                $table->string('path');
                $table->string('thumbnail_path')->nullable();
                $table->string('url');
                $table->string('thumbnail_url')->nullable();
                $table->string('mime_type', 20)->nullable();
                $table->timestamps();
            });
        }
    }
};
