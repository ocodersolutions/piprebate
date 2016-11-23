<?php require('includes/header.php');?>
<style>
	ul.pagination {
	    display: inline-block;
	    padding: 0;
	    margin: 0;
	}

	ul.pagination li {display: inline;}

	ul.pagination li a {
	    color: black;
	    float: left;
	    padding: 8px 16px;
	    text-decoration: none;
	}
	input[name='price']{
		margin-right: 10%;
	}
	ul.pagination li a.active {
	    background-color: #5bc0de;
	    color: white;
	}

	ul.pagination li a:hover:not(.active) {background-color: #ddd;}
	.price2{display: none}
	@media only screen and (max-width: 768px){
		.price2{display: none}
	}
	@media only screen and (max-width: 480px){
		body, .btn, .form-control {font-size: 11px;}
		 .table>tbody>tr>td{padding: 2px;width: 100%;}
		 input[name='price'] {margin-right: 4%;}
	}


</style>

<?php 
	if(!isset($_GET['page'])){ 
		$page = 1; 
	}elseif(isset($_GET['page'])){
		$page = $_GET['page'];
	}
  	require 'database.php'; 
	$conn = Database::connect();
	$item_per_page = 15;

	$page_limit = ($page-1)*$item_per_page;
	$sql = "SELECT * FROM `counter`, `pipcost` WHERE `counter`.`counterid` = `pipcost`.`counterid` ORDER BY length(`counter`.`counterid`) DESC LIMIT $page_limit,$item_per_page";

	$results = mysqli_query($conn, $sql);
?>
<p class="bg-success" style="padding-left: 20px; font-weight: bold;">
	<?php 
	if(isset($_SESSION['add'])){ 
		echo 'Add success !';
		unset($_SESSION['add']); 
	} 

	if(isset($_SESSION['edit'])){ 
		echo 'Edit success !';
		unset($_SESSION['edit']); 
	} 

	if(isset($_SESSION['delete'])){ 
		echo 'Delete success !';
		unset($_SESSION['delete']); 
	} 
	?>

</p>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Counterid</th>
            <th>Price 1</th>
            <th class="price2">Price 2</th>
            <th>Costperpip</th>
        </tr>
    </thead>
    <tbody>
		<?php if ($results->num_rows > 0) {

		    while($row = $results->fetch_assoc()) { 
		    	$decimal = $row["decimal"];
            		switch ($row["decimal"]) {
            			case 0:
					        $decimal_value = 1;
					        break;
					    case 2:
						    $decimal_value = 0.01;
						    break;
					    case 3:
						    $decimal_value = 0.001;
						    break;
					    case 4:
						    $decimal_value = 0.0001;
						    break;
            		}
		    	?>
		        <tr>
		            <th scope="row"><?php echo $row["counterid"]; ?></th>
		            <?php //if (in_array($row['counterid'], $array_pipcost)){ 
            		// $sql_pipcost2 = 'SELECT * FROM pipcost WHERE counterid="'.$row['counterid'].'"';
            		// $results_pipcost2 = mysqli_query($conn, $sql_pipcost2);
            		// $row_pipcost2 = $results_pipcost2->fetch_assoc(); 
            		$price = array_key_exists('price1', $row) ? $row['price1'] : 0; 
            		$price2 = $price - $decimal_value;
            		// }else{ 
            		// 	$price = '';
            		// } 
            		?>
            		<form class="update-price" action="action.php" method="post"> 
            		<td>
		            	<input type="hidden" name="counterid" value="<?php echo $row["counterid"]; ?>"/>
						<input type="hidden" name="update-price" value="update-price"/>
		            	<input type="hidden" name="page" value="<?php echo $page;?>"/>
		            	<input type="hidden" name="crossrate" value="<?php echo $row['crossrate'];?>"/>

		            	<input type="text" name="price" class="form-control" data-decimal="<?php echo $row["decimal"]; ?>" data-price2="<?php echo $price2; ?>" data-lotsize="<?php echo $row['lotsize']; ?>" data-counterid="<?php echo $row["counterid"]; ?>" data-crossrate="<?php echo $row['crossrate'] ?>" value="<?php echo $price;?>" style="width: 60%; float: left;"/>
		            	<button type="submit" class="btn btn-info">Edit</button>
			            
		            </td> 
		            <td class="price2"><input type="text" name="price2" value="<?php if($price != 0){echo $price2;}else{echo '';} ?>"  class="form-control"/></td>
		            <td><input type="text"  name="costperpip" value="<?php echo $row['costperpip'] ?>" class="form-control"/></td>
		            </form>
		        </tr>
		<?php } } ?>
    </tbody>
</table>

<ul class="pagination">
	<?php //get all record
	$sql_all = "SELECT * FROM `counter`";
	$results_all = mysqli_query($conn, $sql_all);
	$total_pages = ceil($results_all->num_rows / $item_per_page); 
	for ($i=1; $i<=$total_pages; $i++) { 
		if ($page == $i) { $active = 'active'; } else{ $active = '';}
	    echo "<li><a class='".$active."' href='enter-price.php?page=".$i."'>".$i."</a></li>"; 
	}; ?>
</ul>
<?php require('includes/footer.php');?>