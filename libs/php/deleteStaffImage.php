<?php

    $executionStartTime = microtime(true);

    include("config.php");

    header('Content-Type: application/json; charset=UTF-8');

    $date = date("Y-m-d h:m:s");
    $live_Flag = 'N';
    $deleted_Flag = 'Y';

	$query = $conn->prepare('UPDATE Staff SET Live_Flag = ?, Deleted_Flag = ?, Deleted_Date_Time = ? WHERE Id = ?');

	$query->bind_param("sssi", $live_Flag, $deleted_Flag, $date, $_POST['Id'] );

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
	$output['status']['description'] = "successfully deleted record " . $_POST['Id'];
	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
	$output['data'] =  null;
	
    mysqli_close($conn);

	echo json_encode( $output); 

?>