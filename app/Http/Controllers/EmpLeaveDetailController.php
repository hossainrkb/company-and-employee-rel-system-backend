<?php

namespace App\Http\Controllers;

use App\Models\EmpLeaveDetail;
use App\Traits\EmpLeaveTrait;
use Illuminate\Http\Request;
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
                'leave_status_track' => AVAILABLE_LEAVE
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
}
