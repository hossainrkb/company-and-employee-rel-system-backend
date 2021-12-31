<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmployeeAutomatedSalarySystemSSLCOMMERZController extends CentralController
{
    public function createSession(Request $request, $companyId)
    {
        $company   = Company::find($companyId);
        $post_data = array();
        $trx       = "SSLCZ_TEST_" . generateRandomString();
        $post_data['store_id']     = env('SANDBOX_STORE_ID');
        $post_data['store_passwd'] = env('SANDBOX_STORE_PASSWORD');
        $post_data['total_amount'] = "103";
        $post_data['currency']     = "BDT";
        $post_data['tran_id']      = $trx;
        $post_data['success_url']  = env('BASE_URL') . "api/company/$companyId/sslcommerz/success-path";
        $post_data['fail_url']     = env('BASE_URL') . "api/company/$companyId/sslcommerz/fail-path";
        $post_data['cancel_url']   = env('BASE_URL') . "api/company/$companyId/sslcommerz/cancel-path";
        # CUSTOMER INFORMATION
        $post_data['cus_name']     = "Test Customer";
        $post_data['cus_email']    = "test@test.com";
        $post_data['cus_add1']     = "Dhaka";
        $post_data['cus_add2']     = "Dhaka";
        $post_data['cus_city']     = "Dhaka";
        $post_data['cus_state']    = "Dhaka";
        $post_data['cus_postcode'] = "1000";
        $post_data['cus_country']  = "Bangladesh";
        $post_data['cus_phone']    = "01711111111";
        $post_data['cus_fax']      = "01711111111";
        # SHIPMENT INFORMATION
        $post_data['shipping_method']     = "NO";
        $post_data['num_of_item ']    = 5;
       
      
      
        $post_data['product_name'] = "hola";
$post_data['product_category'] = "salary";
$post_data['product_profile'] = "general";
        // return $post_data;
        $response = Http::asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $post_data);
        return $response->body();
    }
}
