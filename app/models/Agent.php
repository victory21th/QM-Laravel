<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Agent extends Eloquent {
    
    protected $table = 'agent';
    
    public function store() {
        return $this->belongsTo('Store', 'store_id');
    }    
}
