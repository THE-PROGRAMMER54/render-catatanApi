<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class catatan extends Model
{
    protected $table = 'catatan';
    protected $hidden = [];
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
