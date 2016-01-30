<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('company', function($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('name', 64);
	        $t->string('vat_number', 64);
	        $t->string('address', 64);
	        $t->string('postal_code', 32);
	        $t->string('email', 64);
	        $t->string('phone', 32);
	        $t->integer('category_id')->unsigned();
	        $t->integer('city_id')->unsigned();
	        $t->string('secure_key', 32);
	        $t->string('salt', 8);
	        $t->string('token', 8);
	        $t->boolean('is_active')->default(1);
	        $t->timestamps();
	        $t->foreign('category_id')->references('id')->on('category');
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
	    Schema::table('company', function ($t) {
	        $t->dropForeign('company_category_id_foreign');
	        $t->dropForeign('company_city_id_foreign');
	    });
	    Schema::drop('company');
	}

}
