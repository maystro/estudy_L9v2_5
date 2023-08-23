<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('visible_to'); // levels number separated string Ex 1,3,4 or 1,4
            $table->string('user_role');  // User Role key separated , -> Ex student, subscribers
            $table->string('list_type');  // enum [media,ebook] media for mp3,mp4,images ; ebook for pdf,doc,docx,images
            $table->integer('idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlists');
    }
}
