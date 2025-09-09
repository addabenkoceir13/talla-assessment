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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('capital')->nullable();
            $table->decimal('area', 15, 2)->nullable();
            $table->json('currencies')->nullable();
            $table->json('languages')->nullable();
            $table->string('flag')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->unsignedBigInteger('population')->nullable();
            $table->foreignId('added_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('source', ['api', 'user_added'])->default('api');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'source']);
            $table->index('added_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
