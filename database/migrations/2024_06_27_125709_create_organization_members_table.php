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
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parentId')->nullable()->unsigned();
            $table->string('name');
            $table->string('lastname');
            $table->string('position');
            $table->string('image');
            $table->timestamps();
        
            // Adding the foreign key constraint
            $table->foreign('parentId')->references('id')->on('organization_members')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
