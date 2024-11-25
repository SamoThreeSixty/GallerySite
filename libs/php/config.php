
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

?>
