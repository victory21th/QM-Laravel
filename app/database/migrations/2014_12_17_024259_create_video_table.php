<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('video', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->string('name', 64);
	        $t->string('url', 256);
	        $t->integer('duration')->unsigned();
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
	    Schema::table('video', function ($t) {
	        $t->dropForeign('video_company_id_foreign');
	    });
	    Schema::drop('video');
	}

}
