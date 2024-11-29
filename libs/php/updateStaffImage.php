<?php

$executionStartTime = microtime(true);

include("config.php");

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$newName = htmlspecialchars($_POST['newName'], ENT_QUOTES, 'UTF-8');
$newNameLength = strlen($newName);

// Validation
// Check that the Id is provided and it is above 0
if (!$id || !is_numeric($id) || $id <= 0) {
    mysqli_close($conn);

    sendErrorResponse(400, "An invalid Id was provided");

    exit;
}

// Make sure the new name is set and below above 0 and 100 or below char.
if (!$newName || !is_string($newName) || !($newNameLength > 0 && $newNameLength <= 100)) {
    mysqli_close($conn);

    sendErrorResponse(400, "An invalid new name was provided");

    exit;
}

// SQL Query
$query = $conn->prepare('UPDATE Staff SET Staff_Name = ? WHERE Id = ?');

$query->bind_param("si", $newName, $id);

$query->execute();

// SQL error checking
if ($query === false) {
    mysqli_close($conn);

    sendErrorResponse(500, "Query failed");

    exit;
}

// No errors, return success
http_response_code(200);
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = null;

mysqli_close($conn);

echo json_encode($output);

?>