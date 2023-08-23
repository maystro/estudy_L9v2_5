<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateExportConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_config', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('column_labels')->nullable();
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
        });

        DB::table('export_config')->insert([
            'column_labels'=>'',
            'header'=>'',
            'footer'=>'',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_config');
    }
}
