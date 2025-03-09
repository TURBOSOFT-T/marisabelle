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
        Schema::table('configs', function (Blueprint $table) {
            $table->text('titre_promo')->nullable();
            $table->text('description_promo')->nullable();
            $table->string('image_promo')->nullable();


            $table->text('titre_promo1')->nullable();
            $table->text('description_promo1')->nullable();
            $table->string('image_promo1')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('titre_promo');
            $table->dropColumn('description_promo');
            $table->dropColumn('image_promo');
            $table->dropColumn('titre_promo1');
            $table->dropColumn('description_promo1');
            $table->dropColumn('image_promo1');

              });
    }
};
