<?php

namespace App;

use Illuminate\Support\Str;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, softDeletes;

    public $transformer = UserTransformer::class;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';

    protected $dates = ['created_at'];


    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];


    protected $hidden = [
        'password',
        'remember_token',
        'verification_token'
    ];


    protected $casts = [
        'verified' => 'boolean',
    ];




###################################### Methods ############################################

    public function isVerified() {
        return $this->verified == User::VERIFIED_USER;
    }


    public function isAdmin() {
        return $this->admin == User::ADMIN_USER;
    }


    public static function generateVerificationCode() {
        return Str::random(40);
    }



###################################### Accessors and mutator ############################################

    public function setNameAttribute($name) {  //mutator
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name) {  //accessor
        return ucwords($name);
    }

    public function setEmailAttribute($email) {  //mutator
        $this->attributes['email'] = strTolower($email);
    }

    public function getEmailAttribute($email) {  //accessor
        return ucwords($email);
    }


}
