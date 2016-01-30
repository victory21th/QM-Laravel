<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Process extends Eloquent {
    
    protected $table = 'process';
    
    public function ticketType() {
        return $this->belongsTo('TicketType', 'ticket_type');
    }
    
    public function agent() {
        return $this->belongsTo('Agent', 'agent_id');
    }
}
