<?php
$executionStartTime = microtime(true);

include("config.php");
include("downloadStaffImage.php");

header('Content-Type: application/json; charset=UTF-8');

// Sanitise inputs
$staffName = htmlspecialchars($_POST['Staff_Name'], ENT_QUOTES, 'UTF-8');
$staffNameLength = strlen($staffName);

// Validate
// Make sure the new name is set and below above 0 and 100 or below char.
if (!$staffName || !is_string($staffName) || !($staffNameLength > 0 && $staffNameLength <= 100)) {
    $output['status']['code'] = "400";
    $output['status']['name'] = "invalid params";
    $output['status']['description'] = "An invalid new name was provided";
    $output['data'] = [];

    echo json_encode($output);
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
    $output['status']['code'] = "400";
    $output['status']['name'] = "Download error";
    $output['status']['description'] = "Error: " . $e->getMessage();
    $output['data'] = [];

    mysqli_close($conn);

    echo json_encode($output);

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
                                                -- Live_Flag, 
                                                Added_Date_Time
                                                -- Added_From_IP, 
                                                -- Deleted_Flag, 
                                                -- Deleted_Date_Time, 
                                                -- Deleted_From_IP
                                                ) 
                                            VALUES 
                                                (?, ?, ?, ?);');

$query->bind_param('ssss', $_POST['Staff_Name'], $basePath, $nextPosition, $date);

$query->execute();

if ($query === false) {

    $output['status']['code'] = "400";
    $output['status']['name'] = "executed";
    $output['status']['description'] = "query failed";
    $output['data'] = [];

    mysqli_close($conn);

    echo json_encode($output);

    exit;

}

$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = null;

mysqli_close($conn);

echo json_encode($output);
?>