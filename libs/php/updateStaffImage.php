<?php

$executionStartTime = microtime(true);

include("config.php");

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$newName = filter_input(INPUT_POST, 'newName', FILTER_SANITIZE_STRING);
$newNameLength = strlen($newName);

// Validation
// Check that the Id is provided and it is above 0
if (!$id || !is_numeric($id) || $id <= 0) {
    $output['status']['code'] = "400";
    $output['status']['name'] = "invalid params";
    $output['status']['description'] = "An invalid Id was provided";
    $output['data'] = [];

    echo json_encode($output);
    exit;
}

// Make sure the new name is set and below above 0 and 100 or below char.
if (!$newName || !is_string($newName) || !($newNameLength > 0 && $newNameLength <= 100)) {
    $output['status']['code'] = "400";
    $output['status']['name'] = "invalid params";
    $output['status']['description'] = "An invalid new name was provided";
    $output['data'] = [];

    echo json_encode($output);
    exit;
}

// SQL Query
$query = $conn->prepare('UPDATE Staff SET Staff_Name = ? WHERE Id = ?');

$query->bind_param("si", $newName, $id);

$query->execute();

// SQL error checking
if ($query === false) {

    $output['status']['code'] = "400";
    $output['status']['name'] = "executed";
    $output['status']['description'] = "query failed";
    $output['data'] = [];

    mysqli_close($conn);

    echo json_encode($output);

    exit;

}

// No errors, return success
$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = null;

mysqli_close($conn);

echo json_encode($output);

?>