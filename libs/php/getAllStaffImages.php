<?php
$executionStartTime = microtime(true);

include "config.php";
include "utils.php";

header('Content-Type: application/json; charset=UTF-8');

$query = 'SELECT Id, Staff_Id, Staff_Name, Image_FileName FROM Staff WHERE Live_Flag = "Y" AND Deleted_Flag = "N" ORDER BY Position';

$result = $conn->query($query);

if (!$result) {
	mysqli_close($conn);

	sendErrorResponse(400, "Query failed");

	exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

	array_push($data, $row);

}

http_response_code(200);
$output['status']['name'] = "success";
$output['status']['description'] = "returned data";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = $data;

mysqli_close($conn);

echo json_encode($output);
?>