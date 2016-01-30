<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Store extends Eloquent {
    
    protected $table = 'store';
    
    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }
    
    public function agents() {
        return $this->hasMany('Agent', 'store_id');
    }
    
    public function status() {
        return $this->hasOne('Status', 'store_id');
    }
    
    public function activeAgent() {
        return $this->hasMany('Agent', 'store_id')->where('is_active', true);
    }
    
}
