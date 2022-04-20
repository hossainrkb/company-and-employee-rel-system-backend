<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpAttendance;
use App\Traits\EmpLeaveAttendanceTrait;

class EmpAttendanceController extends CentralController
{
    use EmpLeaveAttendanceTrait;
    public function checkInStore(Request $request, $employeeId)
    {
        try {
            return $this->checkIn($request, $employeeId);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function checkOutStore(Request $request, $employeeId)
    {
        try {
            return $this->checkOut($request, $employeeId);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function attendanceDetaiilsCurrentMonth($companyId)
    {
        try {
            return $this->currentMonthAttendance($companyId);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
    public function empAttendanceDetails()
    {
        try {
            return success_response(null, ['month' => month_list(), 'year' => last_three_year()]);
        } catch (\Throwable $th) {
            return error_response($th->getMessage());
        }
    }
}
