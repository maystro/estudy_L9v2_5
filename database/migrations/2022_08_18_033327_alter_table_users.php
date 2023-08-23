<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
        $table->string('social_id')->nullable()->default(null);
        $table->string('social_email')->nullable()->default(null);
        $table->string('social_avatar')->nullable()->default(null);
        $table->string('social_provider')->nullable()->default(null);
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_id');
            $table->dropColumn('social_email');
            $table->dropColumn('social_avatar');
            $table->dropColumn('social_provider');
        });
    }
}
