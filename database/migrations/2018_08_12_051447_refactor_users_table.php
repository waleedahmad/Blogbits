<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('facebook_id');
            $table->dropColumn('google_id');
            $table->dropColumn('avatar');
            $table->string('access_token')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('facebook_id');
            $table->string('google_id');
            $table->string('avatar');
            $table->dropColumn('access_token');
        });
    }
}
