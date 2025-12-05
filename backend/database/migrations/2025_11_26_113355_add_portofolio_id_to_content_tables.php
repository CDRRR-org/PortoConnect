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
        // Add portofolio_id to projects
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('portofolio_id')->nullable()->after('mahasiswa_id')->constrained('portofolios')->onDelete('cascade');
        });

        // Add portofolio_id to skills
        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('portofolio_id')->nullable()->after('mahasiswa_id')->constrained('portofolios')->onDelete('cascade');
        });

        // Add portofolio_id to certificates
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('portofolio_id')->nullable()->after('mahasiswa_id')->constrained('portofolios')->onDelete('cascade');
        });

        // Add portofolio_id to experiences
        Schema::table('experiences', function (Blueprint $table) {
            $table->foreignId('portofolio_id')->nullable()->after('mahasiswa_id')->constrained('portofolios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropForeign(['portofolio_id']);
            $table->dropColumn('portofolio_id');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['portofolio_id']);
            $table->dropColumn('portofolio_id');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['portofolio_id']);
            $table->dropColumn('portofolio_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['portofolio_id']);
            $table->dropColumn('portofolio_id');
        });
    }
};
