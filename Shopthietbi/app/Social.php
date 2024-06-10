<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'provider_user_id','provider', 'user'
    ];
    protected $primaryKey = 'social_id';
    protected $table = 'tbl_social';
    public function login(){
        return $this->belongsTo('App\Customer','user');
    }
}