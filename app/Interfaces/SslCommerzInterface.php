<?php

namespace App\Interfaces;

interface SslCommerzInterface
{
    /* API List */
    const SANDBOX_CREATE_SESSION_API = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
    /* Payment Create Session Variable */
    const store_id         = 'store_id';
    const store_passwd     = 'store_passwd';
    const total_amount     = 'total_amount';
    const currency         = 'currency';
    const tran_id          = 'tran_id';
    const success_url      = 'success_url';
    const ipn_url      = 'ipn_url';
    const fail_url         = 'fail_url';
    const cancel_url       = 'cancel_url';
    const cus_name         = 'cus_name';
    const cus_email        = 'cus_email';
    const cus_add1         = 'cus_add1';
    const cus_add2         = 'cus_add2';
    const cus_city         = 'cus_city';
    const cus_state        = 'cus_state';
    const cus_postcode     = 'cus_postcode';
    const cus_country      = 'cus_country';
    const cus_phone        = 'cus_phone';
    const cus_fax          = 'cus_fax';
    const shipping_method  = 'shipping_method';
    const num_of_item      = 'num_of_item ';
    const product_name     = 'product_name';
    const product_category = 'product_category';
    const product_profile  = 'product_profile';
}
