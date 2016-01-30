<?php namespace User\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Queue extends Eloquent {
    
    protected $table = 'queue';
    
    public function store() {
        return $this->belongsTo('Store', 'store_id');
    }    
}
