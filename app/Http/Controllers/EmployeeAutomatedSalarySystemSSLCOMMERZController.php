<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
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
$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
        // return $post_data;
        $client = new Client([
            'verify' => false,
            'curl' => [
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true,
            ]
        ]);
      $response =  $client->request('POST', $direct_api_url, [
            'form_params' => $post_data
        ]);
        return $request->getBody();
        $response = Http::withOptions([
            'verify' => false,
            'curl' => [
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => true,
            ]
        ])->post($direct_api_url, $post_data);
        return $response->body();
        # REQUEST SEND TO SSLCOMMERZ

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url );
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1 );
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


$content = curl_exec($handle );

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($handle))) {
	curl_close( $handle);
	$sslcommerzResponse = $content;
} else {
	curl_close( $handle);
	echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
	exit;
}

# PARSE THE JSON RESPONSE
$sslcz = json_decode($sslcommerzResponse, true );
return $sslcz;
if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
        # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
        # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
	echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
	# header("Location: ". $sslcz['GatewayPageURL']);
	exit;
} else {
	echo "JSON Data parsing error!";
}
    }
}
