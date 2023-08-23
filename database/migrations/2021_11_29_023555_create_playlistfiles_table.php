<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlistfiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('idx');
            $table->foreignId('playlist_id');
            $table->foreignId('lecture_id');
            $table->foreign('playlist_id')
                    ->references('id')
                    ->on('playlists')
                    ->onDelete('cascade');
            $table->foreign('lecture_id')
                ->references('id')
                ->on('lectures')
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
        Schema::dropIfExists('playlistfiles');
    }
}
