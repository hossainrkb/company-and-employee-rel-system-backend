<?php

namespace App\Traits;

use App\Models\EmpSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait SslCommerzTrait
{
    public function sslcommerzcreateSession($request, $employee)
    {
        $post_data          = array();
        $post_data[self::store_id]     = env('SANDBOX_STORE_ID');
        $post_data[self::store_passwd] = env('SANDBOX_STORE_PASSWORD');
        $post_data[self::total_amount] = $request['salary_amount'];
        $post_data[self::currency]     = "BDT";
        $post_data[self::tran_id]      = $request['trx'];

        $response = Http::asForm()->post(
            self::SANDBOX_CREATE_SESSION_API,
            array_merge(
                $post_data,
                $this->parseEmpReqForSession($employee),
                $this->parseShipmentReqForSession(),
                $this->parseProductReqForSession(),
                $this->parseReqUrlForSession(),
            )
        );
        return $response->body();
    }

    private function parseEmpReqForSession($employee)
    {
        return [
            self::cus_name     => $employee->name ?? "Test",
            self::cus_email    => $employee->email ?? "Test@email.com",
            self::cus_add1     => 'Add One',
            self::cus_city     => 'Chittagong',
            self::cus_postcode => '4226',
            self::cus_country  => 'Bangladesh',
            self::cus_phone    => '01790507933',
        ];
    }
    private function parseShipmentReqForSession()
    {
        return [
            self::shipping_method => 'NO',
            self::num_of_item     => 1,
        ];
    }
    private function parseProductReqForSession()
    {
        return [
            self::product_name     => 'Employee Salary',
            self::product_category => 'Maney',
            self::product_profile  => 'general',
        ];
    }
    private function parseReqUrlForSession()
    {
        return [
            self::success_url      => env('BASE_URL') . "api/sslcommerz/success-path",
            self::fail_url         => env('BASE_URL') . "api/sslcommerz/fail-path",
            self::cancel_url       => env('BASE_URL') . "api/sslcommerz/cancel-path",
            self::ipn_url          => env('BASE_URL') . "api/sslcommerz/ipn-path",
        ];
    }

    private function validateSuccess($request)
    {
        if ($this->verifySign($request)) {
            $post_data = [
                'val_id' => $request->val_id,
                'store_id' => env('SANDBOX_STORE_ID'),
                'store_passwd' => env('SANDBOX_STORE_PASSWORD'),
                'format' => 'json',
                'v' => 1
            ];
            //  dd($post_data);
            $response = Http::get(
                self::SANDBOX_PAYMENT_VALIDATE_API,
                $post_data,
            );
            $response_obj = json_decode($response->body());
            $empSalary = EmpSalary::where('trx_key', $response_obj->tran_id)->first();
            $empSalary->update(['salary_status' => 'PROCESSED']);
            return true;
        } else {
            return error_response('Verification Sign doesn"t match');
        }
    }
    private function verifySign($request)
    {
        $explode_verify_key = explode(",", $request->verify_key);
        $data = [];
        foreach ($explode_verify_key as $key => $value) {
            $data[$value] = $request->$value;
        }
        $data['store_passwd'] = md5(env('SANDBOX_STORE_PASSWORD'));
        ksort($data);
        $hash_string = "";
        foreach ($data as $key => $value) {
            $hash_string .= $key . '=' . ($value) . '&';
        }
        if (md5(trim($hash_string, "&")) == $request->verify_sign) {
            return true;
        } else {
            return false;
        }
    }
}
