<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('full_name')->nullable()->after('name');
            $table->string('username')->nullable()->unique()->after('full_name');
            $table->string('role')->default('User')->after('password');
        });

        DB::table('users')->orderBy('id')->get()->each(function (object $user): void {
            DB::table('users')->where('id', $user->id)->update([
                'full_name' => $user->name,
                'username' => 'user_'.$user->id,
                'role' => $user->email === 'admin@example.com' ? 'Admin' : 'User',
            ]);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->string('full_name')->nullable(false)->change();
            $table->string('username')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['username']);
            $table->dropColumn(['full_name', 'username', 'role']);
        });
    }
};
