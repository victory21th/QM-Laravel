<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('process', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('agent_id')->unsigned();
	        $t->integer('ticket_type')->unsigned()->nullable();
	        $t->integer('queue_no')->unsigned();
	        $t->string('start_time', 8);
	        $t->string('end_time', 8)->nullable();
	        $t->timestamps();
	        $t->foreign('agent_id')->references('id')->on('agent');
	        $t->foreign('ticket_type')->references('id')->on('ticket_type');
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
	    Schema::table('process', function ($t) {
	        $t->dropForeign('ticket_type_ticket_type_foreign');
	    });
	    Schema::drop('process');
	}

}
