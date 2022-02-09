<?php

/* Global Variable*/
const PENDING_LEAVE = 2;
const ON_LEAVE = 1;
const AVAILABLE_LEAVE = 0;

/* Global Function */
function error_response($message = null, $data = [])
{
    return response()->json(array_merge([
        'status' => 'error',
        'message' => isset($message) ? $message : 'error occured'
    ], isset($data) ? ['data' => $data] : []));
}
function success_response($message = null, $data = [])
{
    return response()->json(array_merge([
        'status' => 'ok',
        'message' => isset($message) ? $message : 'successfull'
    ], isset($data) ? ['data' => $data] : []));
}
function month_list()
{
    return [
        'January'   => '01',
        'February'  => '02',
        'March'     => '03',
        'April'     => '04',
        'May'       => '05',
        'June'      => '06',
        'July'      => '07',
        'August'    => '08',
        'September' => '09',
        'October'   => '10',
        'November'  => '11',
        'December'  => '12',
    ];
}
function identify_month($month)
{
    return month_list()[$month];
}
function last_three_year()
{
    $data = [];
    $endDate = date("Y");
    $startDate = $endDate - 2;
    for ($startDate; $startDate <= $endDate; $startDate++) {
        array_push($data, $startDate);
    }
    return $data;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
