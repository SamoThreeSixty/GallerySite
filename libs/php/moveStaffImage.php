<?php

// Check the record that has been passed
// check the next lowest
// swap the numbers
// opposite if it is being moved up
// This way it should avoid anomaly's in incrementing by just swapping the location of these

$executionStartTime = microtime(true);

include("config.php");
include "utils.php";

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$id = filter_input(INPUT_POST, 'Id', FILTER_SANITIZE_NUMBER_INT);
$action = htmlspecialchars($_POST['action'], ENT_QUOTES, 'UTF-8');

// Validation
if (!$id || !is_numeric($id) || $id <= 0) {
    mysqli_close($conn);

    sendErrorResponse(400, "An invalid id was provided");

    exit;
}

if (!$action || !is_string($action) || !in_array($action, ["MoveUp", "MoveDown"])) {
    mysqli_close($conn);

    sendErrorResponse(400, "An invalid action was provided");

    exit;
}

// SQL Query
// Get the  current record that the action has been made on
$current = $conn->prepare("SELECT Position FROM Staff WHERE Id = ?");
$current->bind_param("i", $id);
$current->execute();

$currentRecord = $current->get_result()->fetch_assoc();
$currentPosition = $currentRecord['Position'];

// Prepare statements based on the action provided from request
if ($action === "MoveUp") {
    $next = $conn->prepare(
        "SELECT Id, Position FROM Staff WHERE Position < ? AND Deleted_Flag = 'N' AND Live_Flag = 'Y' ORDER BY Position DESC LIMIT 1"
    );
} else {
    $next = $conn->prepare(
        "SELECT Id, Position FROM Staff WHERE Position > ? AND Deleted_Flag = 'N' AND Live_Flag = 'Y' ORDER BY Position ASC LIMIT 1"
    );
}

// SQL Query
// Get the next record that the current will be switched with
$next->bind_param("i", $currentPosition);
$next->execute();

$nextRecord = $next->get_result()->fetch_assoc();
$nextId = $nextRecord['Id'];
$nextPosition = $nextRecord['Position'];

// Begin a transaction because multiple (2) queries will be executed at the same time.
$conn->begin_transaction();

// Use error catch statement to catch any issues
try {
    // Swap the values of the position between the records
    $updateCurrent = $conn->prepare("UPDATE Staff SET Position = ? WHERE Id = ?");
    $updateCurrent->bind_param("ii", $nextPosition, $id);
    $updateCurrent->execute();

    $updateNext = $conn->prepare("UPDATE Staff SET Position = ? WHERE Id = ?");
    $updateNext->bind_param("ii", $currentPosition, $nextId);
    $updateNext->execute();

    $conn->commit();
} catch (Exception $e) {
    // In case of failure, the change will be rolled back and message sent to client
    $conn->rollback();

    mysqli_close($conn);

    sendErrorResponse(400, "SQL transaction failed: " . $e->getMessage());

    exit;
}

// No errors, return success
http_response_code(200);
$output['status']['name'] = "Success";
$output['status']['description'] = "Swapped positions of record id " . $id . " with " . $nextId;
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = [];

echo json_encode($output);

?>