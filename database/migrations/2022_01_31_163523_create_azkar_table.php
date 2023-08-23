<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAzkarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azkar_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
        });

        Schema::create('azkar', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('group_id');
            $table->text('content');
            $table->string('note');
            $table->integer('max_count');
            $table->integer('idx');
            $table->foreign('group_id')
                ->references('id')
                ->on('azkar_groups');
        });

        \App\Models\AzkarGroup::upsert(
            [
                'title'=>'عام',
            ],
            ['title']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('azkar');
    }
}
