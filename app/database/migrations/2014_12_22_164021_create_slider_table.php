<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('slider', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->string('url', 128);
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
	    Schema::table('slider', function ($t) {
	        $t->dropForeign('slider_company_id_foreign');
	    });
	    Schema::drop('slider');		
	}

}
