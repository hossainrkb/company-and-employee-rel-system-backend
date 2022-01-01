<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait SslCommerzTrait
{
    public function sslcommerzcreateSession(Request $request, $companyId)
    {
                   $post_data              = array();
                   $trx                    = "SSLCZ_TEST_" . generateRandomString();
        $post_data[self::store_id]         = env('SANDBOX_STORE_ID');
        $post_data[self::store_passwd]     = env('SANDBOX_STORE_PASSWORD');
        $post_data[self::total_amount]     = "103";
        $post_data[self::currency]         = "BDT";
        $post_data[self::tran_id]          = $trx;
        $post_data[self::success_url]      = env('BASE_URL') . "api/sslcommerz/success-path";
        $post_data[self::fail_url]         = env('BASE_URL') . "api/sslcommerz/fail-path";
        $post_data[self::cancel_url]       = env('BASE_URL') . "api/sslcommerz/cancel-path";
        $post_data[self::cus_name]         = "Test Customer";
        $post_data[self::cus_email]        = "test@test.com";
        $post_data[self::cus_add1]         = "Dhaka";
        $post_data[self::cus_add2]         = "Dhaka";
        $post_data[self::cus_city]         = "Dhaka";
        $post_data[self::cus_state]        = "Dhaka";
        $post_data[self::cus_postcode]     = "1000";
        $post_data[self::cus_country]      = "Bangladesh";
        $post_data[self::cus_phone]        = "01711111111";
        $post_data[self::cus_fax]          = "01711111111";
        $post_data[self::shipping_method]  = "NO";
        $post_data[self::num_of_item]      = 5;
        $post_data[self::product_name]     = "hola";
        $post_data[self::product_category] = "salary";
        $post_data[self::product_profile]  = "general";
        $response = Http::asForm()->post(self::SANDBOX_CREATE_SESSION_API, $post_data);
        return $response->body();
    }
}
