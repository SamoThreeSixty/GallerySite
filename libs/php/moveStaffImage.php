<?php

// Check the record that has been passed
// check the next lowest
// swap the numbers
// opposite if it is being moved up
// This way it should avoid anomaly's in incrementing by just swapping the location of these

$executionStartTime = microtime(true);

include("config.php");

header('Content-Type: application/json; charset=UTF-8');

// Validate and sanitize inputs
$id = isset($_POST['Id']) ? (int) $_POST['Id'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : null;

// Error checking
if (!$id || !in_array($action, ["MoveUp", "MoveDown"])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$current = $conn->prepare("SELECT Position FROM Staff WHERE Id = ?");
$current->bind_param("i", $id);
$current->execute();

$currentRecord = $current->get_result()->fetch_assoc();
$currentPosition = $currentRecord['Position'];

if ($action === "MoveUp") {
    $next = $conn->prepare(
        "SELECT Id, Position FROM Staff WHERE Position < ? ORDER BY Position DESC LIMIT 1"
    );
} else {
    $next = $conn->prepare(
        "SELECT Id, Position FROM Staff WHERE Position > ? ORDER BY Position ASC LIMIT 1"
    );
}

$next->bind_param("i", $currentPosition);
$next->execute();

$nextRecord = $next->get_result()->fetch_assoc();
$nextId = $nextRecord['Id'];
$nextPosition = $nextRecord['Position'];

// Swap positions
$conn->begin_transaction();

try {
    $updateCurrent = $conn->prepare("UPDATE Staff SET Position = ? WHERE Id = ?");
    $updateCurrent->bind_param("ii", $nextPosition, $id);
    $updateCurrent->execute();

    $updateNext = $conn->prepare("UPDATE Staff SET Position = ? WHERE Id = ?");
    $updateNext->bind_param("ii", $currentPosition, $nextId);
    $updateNext->execute();

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Positions swapped successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => 'Transaction failed: ' . $e->getMessage()]);
}

$executionEndTime = microtime(true);

?>