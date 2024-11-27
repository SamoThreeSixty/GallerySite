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