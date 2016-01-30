<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanySettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('company_setting', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->integer('waiting_time')->unsigned();
	        $t->string('logo', 64);
	        $t->string('color', 8);
	        $t->string('background', 8);
	        $t->string('start_time', 8);
	        $t->string('end_time', 8);
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
	    Schema::table('company_setting', function ($t) {
	        $t->dropForeign('company_setting_company_id_foreign');
	    });
	    Schema::drop('company_setting');		
	}

}
