<?php

namespace App\Models;

use App\Models\Company;
use App\Models\EmpLeaveDetail;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
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
}
