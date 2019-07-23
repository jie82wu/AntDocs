<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('space', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('namespace')->unique();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->nullableTimestamps();
        });
    
        Schema::create('Space_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('organization_id')->unsigned();
        
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organization')
                ->onUpdate('cascade')->onDelete('cascade');
        
            $table->primary(['user_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('space');
        Schema::dropIfExists('space_user');
    }
}
