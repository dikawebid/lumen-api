<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'email_verified_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function findForPassport($username) {
        $user = $this->where('username', $username)->first();
        if (empty($user)) {
            abort(400, "USER_BELUM_TERDAFTAR");
        } 

        return $user;
    }

    public function validateForPassportPasswordGrant($password) {
        if (empty($this->email_verified_at)) {
            abort(400, "USER_BELUM_VERIFIKASI");
        }

        return app('hash')->check($password, $this->getAuthPassword());
    }
}
