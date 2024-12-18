<?php
$executionStartTime = microtime(true);

include("config.php");
include("downloadStaffImage.php");
include("utils.php");

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$staffName = htmlspecialchars($_POST['Staff_Name'], ENT_QUOTES, 'UTF-8');
$staffNameLength = strlen($staffName);

$ipAddress = returnClientIp();

// Validate
// Make sure the new name is set and below above 0 and 100 or below char.
if (!$staffName || !is_string($staffName) || !($staffNameLength > 0 && $staffNameLength <= 100)) {
    mysqli_close($conn);

    sendErrorResponse(400, "An invalid new name was provided");

    exit;
}

// Below handles the uploading of the file
$basePath = sanitizeAndUploadFile(
    $_FILES['Image_Filename'],
    ['jpg', 'png',],
    ['image/jpeg', 'image/png'],
    uploadDir: 'media/staff/'
);

// If an error was found, then return the error message
if ($basePath instanceof Exception) {
    mysqli_close($conn);

    sendErrorResponse(500, "Error: " . $e->getMessage());

    exit;
}

// SQL query to work out the highest position and insert this image incrementally
$highestPosition = $conn->prepare('SELECT COALESCE(MAX(Position), 0) AS Position FROM Staff');
$highestPosition->execute();
$highestPositionResult = $highestPosition->get_result();

$row = $highestPositionResult->fetch_assoc();
$nextPosition = $row['Position'] + 1;

// Get the date to timestamp in the DB
$date = date("Y-m-d H:i:s");

// SQL query to store the data
$query = $conn->prepare('INSERT INTO 
                                            Staff (
                                                Staff_Name, 
                                                Image_Filename, 
                                                Position, 
                                                Added_Date_Time,
                                                Added_From_IP 
                                                ) 
                                            VALUES 
                                                (?, ?, ?, ?, ?);');

$query->bind_param(
    'ssiss',
    $staffName,
    $basePath,
    $nextPosition,
    $date,
    $ipAddress
);

$query->execute();

if ($query === false) {
    mysqli_close($conn);

    sendErrorResponse(500, "Query failed");

    exit;
}

http_response_code(200);
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = null;

mysqli_close($conn);

echo json_encode($output);
?>