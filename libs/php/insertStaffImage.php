<?php
$executionStartTime = microtime(true);

include("config.php");

header('Content-Type: application/json; charset=UTF-8');

// Save file to the images
$fileTmpPath = $_FILES['Image_Filename']['tmp_name'];
$fileName = $_FILES['Image_Filename']['name'];

// Set the target directory for uploaded files
$uploadDir = 'media/staff/';

// Generate a unique file name to avoid overwriting
$newFileName = uniqid() . '-' . basename($fileName);
$uploadPath = '../../' . $uploadDir . $newFileName;
$basePath = $uploadDir . $newFileName;

if (move_uploaded_file($fileTmpPath, $uploadPath)) {
    // Store the file URL in the database
    $fileUrl = $uploadPath;  // Save the relative path to the database
}

$highestPosition = $conn->prepare('SELECT COALESCE(MAX(Position), 0) AS Position FROM Staff');

$highestPosition->execute();

$highestPositionResult = $highestPosition->get_result();

$row = $highestPositionResult->fetch_assoc();

$nextPosition = $row['Position'] + 1;

$date = date("Y-m-d h:m:s");

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