<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Company extends Eloquent {
    
    protected $table = 'company';
    
    public function category() {
        return $this->belongsTo('Category', 'category_id');
    }
    
    public function city() {
        return $this->belongsTo('City', 'city_id');
    }

    public function setting() {
        return $this->hasOne('CompanySetting', 'company_id');
    }        
}
