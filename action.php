<?php 
	session_start();
	require 'database.php'; 
	$conn = Database::connect();
	//add
	// if(isset($_POST['add'])){
	// 	$sql = "INSERT INTO counter (counterid, lotsize, buyspread) VALUES ('".$_POST['counterid']."', '".$_POST['lotsize']."', '".$_POST['buyspread']."')";
	// 	$results = mysqli_query($conn, $sql);
	// 	if($results == true){
	// 		$_SESSION['add'] = true;
	// 		header("Location: /enter-price.php");
	// 	}
	// }
	//edit
	if(isset($_POST['update-price'])){
		$sql = "SELECT * FROM `pipcost` WHERE counterid='".$_POST['counterid']."'";
		$results = mysqli_query($conn, $sql);

		if ($results->num_rows > 0) {
			$sql = "UPDATE pipcost SET costperpip='".$_POST['costperpip']."', price1='".$_POST['price']."' WHERE counterid='".$_POST['counterid']."'";
			$results = mysqli_query($conn, $sql);
			if($results == true){
				$_SESSION['edit'] = true;
				header("Location: /enter-price.php?page=".$_POST['page']);
			}
		}else{
			$sql = "INSERT INTO pipcost (counterid, costperpip, price1) VALUES ('".$_POST['counterid']."', '".$_POST['costperpip']."', '".$_POST['price']."')";
			$results = mysqli_query($conn, $sql);
			if($results == true){
				$_SESSION['edit'] = true;
				header("Location: /enter-price.php?page=".$_POST['page']);
			}
		}
	}


	//delete
	// if(isset($_POST['delete'])){

	// }

	

?>