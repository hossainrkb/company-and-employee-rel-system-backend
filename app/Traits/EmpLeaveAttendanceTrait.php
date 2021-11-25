<?php

namespace App\Traits;

use App\Models\EmpAttendance;
use App\Models\Employee;
use Illuminate\Support\Carbon;

trait EmpLeaveAttendanceTrait
{
    public function checkIn($request, $empId)
    {
        $emp = Employee::find($empId);
        $currentDate = date("Y-m-d");
        $checkAlreadCheckInOrNot = EmpAttendance::where(['emp_id' => $empId, 'com_id' => $emp->company_id, 'on_date' => $currentDate])->first();
        if ($checkAlreadCheckInOrNot) return error_response('Already Checked In', ['employee' => $emp, 'data' => $checkAlreadCheckInOrNot]);
        $data = [];
        $data['com_id'] = $emp->company_id;
        $attendanceDetails = $emp->attendance()->create(array_merge($data, $this->parseCheckInRequest($request)));
        return success_response('Employee Successfully CheckedIn', ['employee' => $emp, 'status' => $attendanceDetails]);
    }
    public function checkOut($request, $empId, $attendanceId)
    {
        $emp                     = Employee::find($empId);
        $checkAlreadCheckInOrNot = EmpAttendance::find($attendanceId);
        if (!$checkAlreadCheckInOrNot) return error_response('Not Checked In Today', ['employee' => $emp]);
        $checkAlreadCheckInOrNot->update(['check_out' => $request->check_out]);
        return success_response('Employee Successfully CheckedOut', ['employee' => $emp, 'status' => $checkAlreadCheckInOrNot]);
    }
    public function empMonthAttendance($comId,$empId, $month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, identify_month($month), $year);
        $returnAbleArray = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $makeDate = $year."-".identify_month($month)."-".$i;
            $getAttendance = EmpAttendance::where('emp_id','=',$empId)->where('com_id','=',$comId)->where('on_date',$makeDate)->first();
            $returnAbleArray[$i] = [
                'date'           => $makeDate,
                'attendance'     => $getAttendance??null,
                'checkIn'        => isset($getAttendance->check_in)?Carbon::parse($getAttendance->check_in)->format("Y-m-d: h:m:s"):null,
                'checkOut'       => isset($getAttendance->check_out)?Carbon::parse($getAttendance->check_out)->format("Y-m-d: h:m:s"):null,
                'dayTotalHours'  => isset($getAttendance->check_out) && isset($getAttendance->check_in)?Carbon::parse($getAttendance->check_out)->diffInHours(Carbon::parse($getAttendance->check_in)):null,
                'dayTotalMin'    => isset($getAttendance->check_out) && isset($getAttendance->check_in)?Carbon::parse($getAttendance->check_out)->diffInMinutes(Carbon::parse($getAttendance->check_in)):null,
                'dayTotalSecond' => isset($getAttendance->check_out) && isset($getAttendance->check_in)?Carbon::parse($getAttendance->check_out)->diffInSeconds(Carbon::parse($getAttendance->check_in)):null,
            ];
        }
        $emp = Employee::find($empId);
        return success_response('Employee Attendance Sheet', ['employee' => $emp, 'records' => $returnAbleArray]);
    }

    private function parseCheckInRequest($request)
    {
        $data = [];
        $data['on_date'] = date("Y-m-d");
        $data['check_in'] = $request->check_in;
        return $data;
    }
}