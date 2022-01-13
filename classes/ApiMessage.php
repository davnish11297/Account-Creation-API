<?php

/**
 * This class encapsulate the error handling functions.
 */
class ApiMessage
{
    /**
     * This function returns the correct HTTP code.
     *
     * @param int $newcode
     * @return int $code
     */
    function http_response_code ($newcode = NULL) {
        $code = 200;
        if($newcode !== NULL) {
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent()) {
                $code = $newcode;
            }
        }       
        return $code;
    }

    /**
     * This function returns the API response.
     *
     * @param boolean $success
     * @param string $status
     * @param string $message
     * @param array $extra
     * @return void
     */
    function Message($success, $status, $message, $extra = []) {
        return array_merge([
            'Success' => $success,
            'Status' => $status,
            'Message' => $message
        ], $extra);
    }
}