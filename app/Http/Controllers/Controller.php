<?php

namespace App\Http\Controllers;

abstract class Controller
{
       protected function success($data ,$code=200, $message ="operation Successfully") {
        return response()->json(
            [
                'status'=>'success',
                'message'=>$message,
                'data'=>$data
            ] ,$code
            );
    }

    protected function error($message ="There is an error", $code=400,$error=null){
            $response=
            [
                'status'=>'error',
                'message'=>$message,
            ];
            if ($error){
                $response['errors']=$error;
            }
            return response()->json($response,$code);
    }
}
