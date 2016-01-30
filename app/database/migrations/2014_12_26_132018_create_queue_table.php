<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('queue', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('store_id')->unsigned();
	        $t->integer('user_id')->unsigned();
	        $t->integer('queue_no')->unsigned();
	        $t->timestamps();
	        $t->foreign('store_id')->references('id')->on('store');
	        $t->foreign('user_id')->references('id')->on('user');
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
	    Schema::table('queue', function ($t) {
	        $t->dropForeign('queue_store_id_foreign');
	        $t->dropForeign('queue_user_id_foreign');
	    });
	    Schema::drop('queue');		
	}

}
