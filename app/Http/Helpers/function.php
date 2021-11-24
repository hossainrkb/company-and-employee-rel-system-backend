<?php

/* Global Variable*/
const PENDING_LEAVE = 2;
const ON_LEAVE = 1;
const AVAILABLE_LEAVE = 0;

/* Flobal Function */
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
