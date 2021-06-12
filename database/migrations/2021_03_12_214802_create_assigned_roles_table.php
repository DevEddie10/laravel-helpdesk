<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedRolesTable extends Migration
{

    public function up()
    {
        Schema::create('assigned_roles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assigned_roles');
    }
}
