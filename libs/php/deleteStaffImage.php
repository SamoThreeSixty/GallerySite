<?php

$executionStartTime = microtime(true);

include "config.php";
include "utils.php";

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$id = filter_input(INPUT_POST, 'Id', FILTER_SANITIZE_NUMBER_INT);

$ipAddress = returnClientIp();
$date = date("Y-m-d H:i:s");
$live_Flag = 'N';
$deleted_Flag = 'Y';

// Validation
// Check that the Id is provided and it is above 0
if (!$id || !is_numeric($id) || $id <= 0) {
	mysqli_close($conn);

	sendErrorResponse(400, "Invalid params");

	exit;
}

// SQL Query
$query = $conn->prepare('UPDATE Staff 
								SET Live_Flag = ?, 
									Deleted_Flag = ?, 
									Deleted_Date_Time = ?,
									Deleted_From_IP = ?
								WHERE Id = ?');

$query->bind_param("ssssi", $live_Flag, $deleted_Flag, $date, $ipAddress, $id);

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
$output['status']['description'] = "successfully deleted record " . $id;
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = null;

mysqli_close($conn);

echo json_encode($output);

?>