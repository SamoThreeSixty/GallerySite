<?php

// Here i will contain util functions that are used throughout this project.

/**
 * Simple function to return the Ip address of the client.
 * 
 * @return string The Ip address of the client making the request.
 */
function returnClientIp()
{
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    return $ipAddress;
}

/**
 * Function to return an error code to client when occurs in the back end.
 * Will handle a set amount of error codes to be expanded when required.
 * 
 * @param int Error code.
 * @param string Error message.
 * 
 * @return void This function will end the code by returning the error to the client.
 */
function sendErrorResponse($code, $message)
{
    // Name of the error to be defined by the error code
    $errorName = '';

    // https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
    switch ($code) {
        case 400:
            $errorName = 'Bad Request';
            break;
        case 401:
            $errorName = 'Unauthorised';
            break;
        case 403:
            $errorName = 'Unauthorised';
            break;
        case 404:
            $errorName = 'Unauthorised';
            break;
        case 500:
            $errorName = 'Internal Server Error';
        default:
            // If not found then default to error 500
            // Basic message returned however could be improved
            // with sending information to an error table in the DB
            $code = 500;
            $errorName = 'Internal Server Error';
            $message = 'Unexpected error on server';
    }

    http_response_code($code);

    $output['status']['name'] = $errorName;
    $output['status']['description'] = $message;
    $output['data'] = [];

    echo json_encode($output);
}