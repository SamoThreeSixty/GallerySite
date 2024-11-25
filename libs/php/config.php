
<?php
	require '../../vendor/autoload.php';

	use Dotenv\Dotenv;

	$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
	$dotenv->safeLoad();

	// connection details for MySQL database

	$sql_host = $_ENV['HOST'];
	$sql_port = $_ENV['PORT'];

	// database name, username and password

	$sql_database = $_ENV['DATABASE'];
	$sql_username = $_ENV['USERNAME'];
	$sql_password = $_ENV['PASSWORD'];

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

?>
