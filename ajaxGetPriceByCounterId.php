<?php
require 'database.php'; 
$conn = Database::connect();

$sql = "SELECT * FROM `pipcost` WHERE `counterid` = '" . $_POST['counterid'] . "'";

$results = mysqli_query($conn, $sql);
$price1 = '';
if ($results->num_rows > 0) {
	$data = $results->fetch_array();
	$price1 = $data['price1'];
}
die($price1);