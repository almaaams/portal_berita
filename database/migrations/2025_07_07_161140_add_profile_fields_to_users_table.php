<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('id');
            }

            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->after('username');
            }

            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['Visitor', 'Admin'])->default('Visitor')->after('password');
            }
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'first_name', 'last_name', 'role']);
        });
    }
};
