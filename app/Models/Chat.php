<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $timestamps = false;
    protected $table = 'chat'; 
    protected $fillable = ['message', 'image', 'status']; // optional: for mass assignment
    protected $dates = ['read_at']; 

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }




}
