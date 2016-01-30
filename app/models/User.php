<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {
    
    protected $table = 'user';
    
    public function city() {
        return $this->belongsTo('City', 'city_id');
    }    
}
