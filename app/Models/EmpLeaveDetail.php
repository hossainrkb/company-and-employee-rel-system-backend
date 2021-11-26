<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpLeaveDetail extends Model
{
  protected $fillable = ['emp_id', 'com_id', 'leave_type', 'from_date', 'to_date', 'leave_message','leave_status_track'];
  
  public function empCurrentLeave()
  {
    return  $this->hasOne(Employee::class, 'current_leave_id', 'id');
  }
}
