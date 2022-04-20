<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\EmpLeaveTrait;
use App\Models\EmpLeaveDetail;
use Illuminate\Support\Facades\Auth;

class EmpLeaveDetailController extends CentralController
{
    use EmpLeaveTrait;
    public function empLeaveStore(Request $request)
    {
        try {
            return $this->leave($request);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function comEmpLeaveStore(Request $request)
    {
        try {
            return $this->leave($request, true);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }

    public function empLeaveStatusApprove(Request $request, $leaveId)
    {
        try {
            $leaveRow = EmpLeaveDetail::find($leaveId);
            $leaveRow->update([
                'leave_status_track' => ON_LEAVE
            ]);
            $leaveRow->empCurrentLeave()->update([
                'current_leave_status' => ON_LEAVE
            ]);
            return success_response("Leave Status Approved");
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function empLeaveStatusDecline(Request $request, $leaveId)
    {
        try {
            $leaveRow = EmpLeaveDetail::find($leaveId);
            if (!$leaveRow->empCurrentLeave) return error_response("No Cuurent Emp Found On This Leave ID");
            $leaveRow->update([
                'leave_status_track' => DECLINE
            ]);
            $leaveRow->empCurrentLeave()->update([
                'current_leave_status' => AVAILABLE_LEAVE,
                'current_leave_id' => NULL,
            ]);
            return success_response("Leave Status Decline");
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function pendingLeaveList()
    {
        try {
            $company = Auth::guard('company_api')->user();
            $data =  $company->employees()->with('currentLeave')->where('current_leave_status', PENDING_LEAVE)->get();
            return success_response("Pending Leave Application", $data);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function empLeaveApplicationLogs($employeeId)
    {
        $returnAbleArray = [];
        $leave_applications = EmpLeaveDetail::where('emp_id', $employeeId)->orderBy('id', 'DESC')->get();
        $i = 0;
        if (count($leave_applications)) {
            foreach ($leave_applications as $key => $value) {
                $status = "";
                if ($value->leave_status_track == PENDING_LEAVE) {
                    $status = "PENDING";
                } elseif ($value->leave_status_track == ON_LEAVE) {
                    $status = "ON LEAVE";
                } elseif ($value->leave_status_track == AVAILABLE_LEAVE) {
                    $status = "AVAILABLE";
                } elseif ($value->leave_status_track == DECLINE) {
                    $status = "DECLINE";
                }
                $returnAbleArray['logs'][]= [
                    'count' => ++$i,
                    'emp_id' => $value->emp_id,
                    'from_date' => isset($value->from_date) ? Carbon::parse($value->from_date)->format("Y-m-d") : "",
                    'to_date' =>  isset($value->to_date) ? Carbon::parse($value->to_date)->format("Y-m-d") : "",
                    'message' =>  isset($value->leave_message) ? $value->leave_message : "",
                    'status' =>  $status
                ];
            }
        }
        return success_response("Success", $returnAbleArray);
    }
}
