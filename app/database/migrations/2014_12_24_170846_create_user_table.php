<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('user', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('name', 64);
	        $t->string('email', 64);
	        $t->string('phone', 32);
	        $t->integer('city_id')->unsigned();
	        $t->string('address', 128);
	        $t->string('secure_key', 32);
	        $t->string('salt', 8);
	        $t->boolean('is_active')->default(1);
	        $t->timestamps();
	        $t->foreign('city_id')->references('id')->on('city');
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
	    Schema::table('user', function ($t) {
	        $t->dropForeign('user_city_id_foreign');
	    });
	    Schema::drop('user');		
	}

}
