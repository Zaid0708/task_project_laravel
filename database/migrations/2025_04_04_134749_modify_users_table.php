<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the 'name' column
            $table->dropColumn('name');

            // Add 'first_name' and 'last_name' columns
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add the 'name' column
            $table->string('name')->after('id');

            // Drop the 'first_name' and 'last_name' columns
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
}