<?php

namespace App\Models;

use App\Models\Company;
use App\Models\EmpLeaveDetail;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
class Employee extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use HasFactory, HasApiTokens, HasApiTokens, Authenticatable, Authorizable;
    protected $fillable = ['name','email','phone_number','current_leave_status','current_leave_id','acc_no'];
    
    public function leave(){
        return $this->hasMany(EmpLeaveDetail::class,'emp_id','id');
    }
    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }
    public function currentLeave(){
        return $this->belongsTo(EmpLeaveDetail::class,'current_leave_id','id');
    }
    public function attendance(){
        return $this->hasMany(EmpAttendance::class,'emp_id','id');
    }
    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class,'user_id','id');
    }
}
