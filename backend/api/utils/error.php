<?php
function error_response($code, $msg, $details = [], $status = 400) {
    json_response([
        "code" => $code,
        "message" => $msg,
        "details" => $details
    ], $status);
}