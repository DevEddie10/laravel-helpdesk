<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedCommentariesTable extends Migration
{
    public function up()
    {
        Schema::create('assigned_commentaries', function (Blueprint $table) {
            $table->id();
            $table->integer('assgment_id')->unsigned();
            $table->integer('commentary_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assigned_commentaries');
    }
}
