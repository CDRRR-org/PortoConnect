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
        // Add indexes for frequently queried columns
        Schema::table('portofolios', function (Blueprint $table) {
            $table->index('is_public');
            $table->index('bidang');
            $table->index('mahasiswa_id');
            $table->index('public_link');
            $table->index(['is_public', 'bidang']);
            $table->index(['is_public', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            // Email already has unique index, but add composite index for common queries
            $table->index(['email', 'role']);
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('jurusan');
            $table->index('universitas');
            $table->index('is_verified');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('portofolio_id');
            $table->index('mahasiswa_id');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->index('portofolio_id');
            $table->index('mahasiswa_id');
            $table->index('nama');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->index('portofolio_id');
            $table->index('mahasiswa_id');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->index('portofolio_id');
            $table->index('mahasiswa_id');
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->index('perusahaan_id');
            $table->index('created_at');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('admin_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropIndex(['is_public']);
            $table->dropIndex(['bidang']);
            $table->dropIndex(['mahasiswa_id']);
            $table->dropIndex(['public_link']);
            $table->dropIndex(['is_public', 'bidang']);
            $table->dropIndex(['is_public', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['email']);
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['jurusan']);
            $table->dropIndex(['universitas']);
            $table->dropIndex(['is_verified']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['portofolio_id']);
            $table->dropIndex(['mahasiswa_id']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropIndex(['portofolio_id']);
            $table->dropIndex(['mahasiswa_id']);
            $table->dropIndex(['nama']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex(['portofolio_id']);
            $table->dropIndex(['mahasiswa_id']);
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropIndex(['portofolio_id']);
            $table->dropIndex(['mahasiswa_id']);
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropIndex(['perusahaan_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['admin_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
