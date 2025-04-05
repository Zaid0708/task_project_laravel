<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role_id column (foreign key to roles table)
            $table->unsignedBigInteger('role_id')->nullable()->after('last_name');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');

            // Add position_id column (foreign key to positions table)
            $table->unsignedBigInteger('position_id')->nullable()->after('role_id');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign keys and columns
            $table->dropForeign(['role_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['role_id', 'position_id']);
        });
    }
}