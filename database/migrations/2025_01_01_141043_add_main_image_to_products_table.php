<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('main_image')->nullable()->after('images');
        });

        // Migrer l'image principale existante
        DB::table('products')->get()->each(function ($product) {
            $images = json_decode($product->images);
            if ($images && count($images) > 0) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['main_image' => $images[0]]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('main_image');
        });
    }
};
