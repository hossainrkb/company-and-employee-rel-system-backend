<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\EmpAttendance;
use App\Models\EmpLeaveDetail;
use App\Models\Employee;
use Illuminate\Support\Carbon;

trait EmpDetailsTrait
{
    public function empStat($comId, $empId, $month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, identify_month($month), $year);
        $returnAbleArray = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $makeDate = $year . "-" . identify_month($month) . "-" . $i;
            $getAttendance = EmpAttendance::where('emp_id', '=', $empId)->where('com_id', '=', $comId)->where('on_date', $makeDate)->first();
            $returnAbleArray[$i] = [
                'date'           => $makeDate,
                'attendance'     => $getAttendance ?? null,
                'checkIn'        => isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_in)->format("Y-m-d: h:m:s") : null,
                'checkOut'       => isset($getAttendance->check_out) ? Carbon::parse($getAttendance->check_out)->format("Y-m-d: h:m:s") : null,
                'dayTotalHours'  => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInHours(Carbon::parse($getAttendance->check_in)) : null,
                'dayTotalMin'    => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInMinutes(Carbon::parse($getAttendance->check_in)) : null,
                'dayTotalSecond' => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInSeconds(Carbon::parse($getAttendance->check_in)) : null,
            ];
        }
        $leaveApplication = [];
        $leaveApplication['total_leave_application'] = EmpLeaveDetail::where('emp_id', '=', $empId)->where('com_id', '=', $comId)->whereMonth('from_date', identify_month($month))->whereYear('from_date', $year)->count();
        $leaveApplication['total_approved_leave_application'] = EmpLeaveDetail::where('emp_id', '=', $empId)->where('com_id', '=', $comId)->where('leave_status_track',ON_LEAVE)->whereMonth('from_date', identify_month($month))->whereYear('from_date', $year)->count();
        $leaveApplication['total_decline_leave_application'] = EmpLeaveDetail::where('emp_id', '=', $empId)->where('com_id', '=', $comId)->where('leave_status_track',AVAILABLE_LEAVE)->whereMonth('from_date', identify_month($month))->whereYear('from_date', $year)->count();
        $leaveApplication['total_pending_leave_application'] = EmpLeaveDetail::where('emp_id', '=', $empId)->where('com_id', '=', $comId)->where('leave_status_track',PENDING_LEAVE)->whereMonth('from_date', identify_month($month))->whereYear('from_date', $year)->count();
        $emp = Employee::find($empId);
        return success_response('Employee Attendance Sheet', ['leave_application'=> $leaveApplication,'employee' => $emp->load('company'), 'records' => $returnAbleArray]);
    }
}
