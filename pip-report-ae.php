<?php require('includes/header.php');?>

<?php 
require 'database.php';

$page = array_key_exists('page', $_GET) ? $_GET['page'] : 1;
$conn = Database::connect();
$item_per_page = 15;
$wherePiptype = 'rebate';

$page_limit = ($page-1)*$item_per_page;
$sql = "SELECT * FROM `accbal` WHERE `inttable` = 'DE3' LIMIT $page_limit,$item_per_page";

$results = mysqli_query($conn, $sql);
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Init Table</th>
            <th>Pip Type</th>
            <th>Lot Size Ratio</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	<?php 
    	if ($results->num_rows > 0) {
		    while($row = $results->fetch_assoc()) { 
          echo "<pre>";
          var_dump($row);
          echo "</pre>";
          die;
		?>
		<tr>
    		<td><?php echo $row['inttable'] ?></td> 
            <td><?php echo $row['piptype'] ?></td>
            <td><?php echo $row['lotsizeratio'] ?></td>
            <td><?php echo ''; ?></td>
        </tr>
       	<?php  
       		} //end while
       	} //end if
       	?>
    </tbody>
</table>

<ul class="pagination">
	<?php //get all record
	$sql_all = "SELECT * FROM `piptype` WHERE `piptype` = '$wherePiptype'";
	$results_all = mysqli_query($conn, $sql_all);
	$total_pages = ceil($results_all->num_rows / $item_per_page); 
	if($total_pages > 1){
		for ($i=1; $i<=$total_pages; $i++) { 
			if ($page == $i) { $active = 'active'; } else{ $active = '';}
		    echo "<li><a class='".$active."' href='pip-report.php?page=".$i."'>".$i."</a></li>"; 
		}; 
	}
	?>
</ul>

<?php require('includes/footer.php');?>