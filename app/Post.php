<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

       /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'body'
    ];
    protected $appends = ['humanCreatedAt'];
    //
    public function user(){

       return $this->belongsTo('App\User');
    }

    public function getHumanCreatedAtAttribute() {
		return $this->created_at->diffForHumans();
	}
}
