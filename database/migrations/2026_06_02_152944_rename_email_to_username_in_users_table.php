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
        // 1. Rename column email to username
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('email', 'username');
        });

        // 2. Clean up existing email values to look like clean usernames
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if (str_contains($user->username, '@')) {
                $newUsername = explode('@', $user->username)[0];
                // Ensure username is unique
                $check = DB::table('users')->where('username', $newUsername)->exists();
                if ($check) {
                    $newUsername = $newUsername . '_' . rand(10, 99);
                }
                DB::table('users')->where('id', $user->id)->update(['username' => $newUsername]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'email');
        });
    }
};
