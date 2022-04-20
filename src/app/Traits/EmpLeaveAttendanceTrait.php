<?php

namespace App\Traits;

use App\Models\Company;
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
    public function checkOut($request, $empId)
    {
        $emp                     = Employee::find($empId);
        $currentDate = date("Y-m-d");
        $attendance = EmpAttendance::where(['emp_id' => $empId, 'com_id' => $emp->company_id, 'on_date' => $currentDate])->first();
        if(is_null($attendance)) return error_response('Today"s Check In Not Given Yet ..');
        if(!is_null($attendance->check_out)) return error_response('You Already Checked Out..');
        $checkAlreadCheckInOrNot = EmpAttendance::find($attendance->id);
        if (!$checkAlreadCheckInOrNot) return error_response('Not Checked In Today', ['employee' => $emp]);
        $checkAlreadCheckInOrNot->update(['check_out' => Carbon::now()->toDateTImeString()]);
        return success_response('Employee Successfully CheckedOut', ['employee' => $emp, 'status' => $checkAlreadCheckInOrNot]);
    }
    public function currentMonthAttendance($comId)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
        $returnAbleArray = [];
        $company = Company::find($comId);
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $makeDate = date("Y") . "-" . date("m") . "-" . $i;
            foreach ($company->employees as $key => $employee) {
                $getAttendance = $employee->attendance()->whereDate('on_date', $makeDate)->first();
                $returnAbleArray[$i][$key] = [
                    'date'           => $makeDate,
                    'employee'           => $employee,
                    'attendance'     => $getAttendance ?? null,
                    'checkIn'        => isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_in)->format("Y-m-d: h:i:s") : null,
                    'checkOut'       => isset($getAttendance->check_out) ? Carbon::parse($getAttendance->check_out)->format("Y-m-d: h:i:s") : null,
                    'dayTotalHours'  => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInHours(Carbon::parse($getAttendance->check_in)) : null,
                    'dayTotalMin'    => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInMinutes(Carbon::parse($getAttendance->check_in)) : null,
                    'dayTotalSecond' => isset($getAttendance->check_out) && isset($getAttendance->check_in) ? Carbon::parse($getAttendance->check_out)->diffInSeconds(Carbon::parse($getAttendance->check_in)) : null,
                ];
            }
        }
        return success_response('Employee Attendance Sheet', ['day'=>date("d"),'month'=>date("F"),'year'=>date("Y"),'records' => $returnAbleArray]);
    }

    private function parseCheckInRequest($request)
    {
        $data = [];
        $data['on_date'] = date("Y-m-d");
        $data['check_in'] = Carbon::now()->toDateTImeString();
        return $data;
    }
}
