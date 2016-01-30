<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('status', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('store_id')->unsigned();
	        $t->integer('current_queue_no')->unsigned();
	        $t->integer('last_queue_no')->unsigned();
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
	    Schema::table('status', function ($t) {
	        $t->dropForeign('status_store_id_foreign');
	    });
	    Schema::drop('status');
	}

}
