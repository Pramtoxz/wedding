<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_guests', function (Blueprint $table) {
            $table->boolean('status_kirim')->default(false)->after('partner_name');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_guests', function (Blueprint $table) {
            $table->dropColumn('status_kirim');
        });
    }
};
