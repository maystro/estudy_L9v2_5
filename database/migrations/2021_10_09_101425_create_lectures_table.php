<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->Id();
            $table->timestamps();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('level_id')->nullable();
            $table->unsignedBigInteger('lecture_number')->nullable();
            $table->unsignedBigInteger('file_order')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('filename')->nullable();
            $table->string('folder')->nullable();
            $table->string('title')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('visit')->default(0);
            $table->unsignedBigInteger('download')->default(0);

            $table->foreign('subject_id')
                    ->references('id')
                    ->on('subjects')
                    ->onDelete('cascade');

            $table->foreign('level_id')
                    ->references('id')
                    ->on('levels')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lectures');
    }
}
