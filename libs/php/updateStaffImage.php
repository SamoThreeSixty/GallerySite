<?php

$executionStartTime = microtime(true);

include("config.php");

header('Content-Type: application/json; charset=UTF-8');

$id = $_POST['id'];
$newName = $_POST['newName'];


$query = $conn->prepare('UPDATE Staff SET Staff_Name = ? WHERE Id = ?');

$query->bind_param("si", $newName, $id);

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