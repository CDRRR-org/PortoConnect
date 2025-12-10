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
        Schema::table('portofolios', function (Blueprint $table) {
            $table->string('bidang')->nullable()->after('nama'); // backend, frontend, fullstack, QATester
            $table->text('education')->nullable()->after('bidang'); // JSON atau text untuk education
            $table->text('language')->nullable()->after('education'); // JSON atau text untuk languages
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropColumn(['bidang', 'education', 'language']);
        });
    }
};
