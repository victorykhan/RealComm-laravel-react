<?php

namespace App;
use App\Post;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends =['avatar'];

    public function posts(){
        return $this->hasMany('App\Post');
    }

    public function getAvatar(){
        return 'https://gravatar.com/avatar/'.md5($this->email).'/?s=45&d=mm';

    }

    public function getAvatarAttribute(){
return $this->getAvatar();
    }

    public function getRouteKeyName(){
        return 'username';
    }

    // many-to-many relationship

    public function following(){

        return $this->belongsToMany('App\User', 'follows','user_id','follower_id');

    }

    public function followers(){

        return $this->belongsToMany('App\User', 'follows','follower_id' ,'user_id');

    }

    //following rules


    public function isNotTheUser(User $user){
        return $this->id !== $user->id;

    }

       public function isFollowing(User $user){
           return (bool) $this->following->where('id', $user->id)->count();

    }

    public function canUnFollow(User $user){
        return $this->isFollowing($user);
    }

    public function canFollow(User $user){
        if(!$this->isNotTheUser($user)){
            return false;
        }

        return !$this->isFollowing($user);

    }


}
