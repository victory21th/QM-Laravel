<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('store', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('company_id')->unsigned();
	        $t->string('name', 64);
	        $t->string('address', 64);
	        $t->string('postal_code', 32);
	        $t->string('email', 64);
	        $t->string('phone', 32);
	        $t->string('secure_key', 32);
	        $t->string('salt', 8);
	        $t->string('token', 8);
	        $t->boolean('is_active')->default(1);
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
	    Schema::table('store', function ($t) {
	        $t->dropForeign('store_company_id_foreign');
	    });
	    Schema::drop('store');
	}

}
