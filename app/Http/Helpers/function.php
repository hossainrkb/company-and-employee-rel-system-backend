<?php
function error_response($message = null){
    return response()->json([
        'status' => 'error',
        'message' => isset($message)?$message:'error occured'
    ]);
}
function success_response($message = null,$data = []){
    return response()->json(array_merge([
        'status' => 'ok',
        'message' => isset($message)?$message:'successfull'
    ],isset($data)?['data'=>$data]:[]));
}