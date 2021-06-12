<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{

    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('category_id')->unsigned();
            $table->integer('media_id')->unsigned();
            $table->integer('state_id')->unsigned();
            $table->integer('assigned_id')->nullable()->unsigned();
            $table->integer('status_id')->nullable()->unsigned();
            $table->integer('modulo_id')->nullable()->unsigned();
            $table->integer('solution_id')->nullable()->unsigned();
            $table->text('description');
            $table->integer('status')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
