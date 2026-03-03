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
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('theme_primary_color')->default('#8B4513');
            $table->string('theme_secondary_color')->default('#D2691E');
            $table->string('theme_accent_color')->default('#F4A460');
            $table->string('theme_font_family')->default('Inter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['theme_primary_color', 'theme_secondary_color', 'theme_accent_color', 'theme_font_family']);
        });
    }
};
