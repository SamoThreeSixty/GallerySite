<?php
	$executionStartTime = microtime(true);

	include("config.php");

	header('Content-Type: application/json; charset=UTF-8');

	$conn = new mysqli($sql_host, $sql_username, $sql_password, $sql_database, port: $sql_port);

    if (mysqli_connect_errno()) {
            
        $output['status']['code'] = "300";
        $output['status']['name'] = "failure";
        $output['status']['description'] = "database unavailable";
        $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
        $output['data'] = [];

        mysqli_close($conn);

        echo json_encode($output);

        exit;
    }

    $highestPosition = $conn->prepare('SELECT COALESCE(MAX(Position), 0) AS Position FROM Staff');

    $highestPosition->execute();

    $highestPositionResult = $highestPosition->get_result();

    $row = $highestPositionResult->fetch_assoc();

    $nextPosition = $row['Position'] + 1;

    $imageName = $_FILES['Image_Filename']['name'];

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

    $query->bind_param('ssss', $_POST['Staff_Name'],  $imageName, $nextPosition, $date);

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
	$output['data'] =  null;
	
	mysqli_close($conn);

	echo json_encode($output); 
?>