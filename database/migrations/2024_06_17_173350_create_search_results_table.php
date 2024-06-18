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
        Schema::create('search_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('search_query_id');
            $table->foreign('search_query_id')->references('id')->on('search_queries');
            $table->string('title');
            $table->string('url');
            $table->text('beschreibung');
            $table->string('bild')->nullable();
            $table->string('kilometerstand')->nullable();
            $table->dateTime('baujahr')->nullable();
            $table->string('preis')->nullable();
            $table->string('kraftstoff')->nullable();
            $table->string('leistung')->nullable();
            $table->string('getriebe')->nullable();
            $table->string('antrieb')->nullable();
            $table->string('türen')->nullable();
            $table->dateTime('hu_bis')->nullable();
            $table->timestamp('inseriert_am')->nullable();
            $table->string('standort')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_results');
    }
};
