<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('ticket_type', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->string('name', 64);
	        $t->timestamps();
	        $t->foreign('company_id')->references('id')->on('company');
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
	    Schema::table('ticket_type', function ($t) {
	        $t->dropForeign('ticket_type_company_id_foreign');
	    });
	    Schema::drop('ticket_type');		
	}

}
