<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('agent', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('store_id')->unsigned();
	        $t->string('name', 64);
	        $t->string('email', 64);
	        $t->string('phone', 32);
	        $t->string('secure_key', 32);
	        $t->string('salt', 8);
	        $t->string('token', 8);
	        $t->boolean('is_active')->default(0);
	        $t->timestamps();
	        $t->foreign('store_id')->references('id')->on('store');
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
	    Schema::table('agent', function ($t) {
	        $t->dropForeign('agent_store_id_foreign');
	    });
	    Schema::drop('agent');
	}

}
