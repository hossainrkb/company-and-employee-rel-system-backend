<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Company extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use HasFactory, HasApiTokens, HasApiTokens, Authenticatable, Authorizable;
    protected $fillable = ['name', 'email', 'username'];

    /*
    RELATIONSHIP
    */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id', 'id');
    }
    public function leave()
    {
        return $this->hasMany(EmpLeaveDetail::class, 'com_id', 'id');
    }
    public function empAddScheme()
    {
        return $this->belongsTo(EmpAddScheme::class, 'subcription_id', 'id');
    }
    public function empAttendance()
    {
        return $this->hasMany(EmpAttendance::class, 'com_id', 'id');
    }
}
