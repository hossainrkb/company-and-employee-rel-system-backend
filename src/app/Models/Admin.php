<?php

namespace App\Models;

use App\Models\OauthAccessToken;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Admin extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    /* rest of the model */

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class,'user_id','id');
    }
}