<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

trait EmpLeaveTrait
{
    public function leave($request, $comApi = false)
    {
        
        $empLeaveStatus = $this->checkEmpCurrentStatus($comApi ? $request->employee_id : Auth::guard('employee_api')->user()->id);
        if ($empLeaveStatus) {
            return $empLeaveStatus;
        }
        if ($comApi) {
            $com = Auth::guard('company_api')->user();
            $data = [];
            $data['emp_id'] = $request->employee_id;
            $leaveDetails = $com->leave()->create(array_merge($data, $this->parseLeaveRequest($request)));
            $emp = Employee::find($request->employee_id);
            $emp->update([
                'current_leave_status' => PENDING_LEAVE,
                'current_leave_id' => $leaveDetails->id
            ]);
            return success_response('Employee Leave Status Updated', ['employee' => $emp, 'leave_status' => $leaveDetails]);
        } else {
            $emp = Auth::guard('employee_api')->user();
            $data = [];
            $data['com_id'] = $emp->company_id;
            $leaveDetails = $emp->leave()->create(array_merge($data, $this->parseLeaveRequest($request)));
            $emp->update([
                'current_leave_status' => 2,
                'current_leave_id' => $leaveDetails->id
            ]);
            return success_response('Employee Leave Status Updated', ['employee' => $emp, 'leave_status' => $leaveDetails]);
        }
    }

    private function parseLeaveRequest($request)
    {
        $data = [];
        $data['leave_type'] = $request->leave_type;
        $data['from_date'] = isset($request->from_date)?Carbon::parse($request->from_date)->format("Y-m-d"):null;
        $data['to_date'] = isset($request->to_date)?Carbon::parse($request->to_date)->format("Y-m-d"):null;
        $data['leave_message'] = $request->leave_message;
        $data['leave_status_track'] = PENDING_LEAVE;
        return $data;
    }
    private function checkEmpCurrentStatus($empId)
    {
        $emp = Employee::find($empId);
        if ($emp->current_leave_status == ON_LEAVE && $emp->current_leave_id) {
            $empPrevLeaveStatus = $emp->leave()->orderBy('id', 'DESC')->first();
            return error_response('Already on Leave', ['employee' => $emp, 'leave_status' => $empPrevLeaveStatus]);
        } elseif ($emp->current_leave_status == PENDING_LEAVE && $emp->current_leave_id) {
            $empPrevPendingLeaveStatus = $emp->leave()->orderBy('id', 'DESC')->first();
            return error_response('Your Current Request Has already Pending, So You have to Update that', ['employee' => $emp, 'leave_status' => $empPrevPendingLeaveStatus]);
        } else {
            return null;
        }
    }
}
