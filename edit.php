<?php require('includes/header.php');?>
	<?php 
	$id = $_GET['id'];
	require 'database.php'; 
	$conn = Database::connect();
	$sql = "SELECT * FROM `counter` WHERE counterid = '$id'";
	$results = mysqli_query($conn, $sql);
	if ($results->num_rows > 0) {
	while($row = $results->fetch_assoc()) { ?>
	<form id="edit-counter" action="action.php" method="post">
		<input type="hidden" name="edit" value="edit">
		<div class="form-group">
		  <label>Counterid:</label>
		  <input type="text" class="form-control" value="<?php echo $row["counterid"]; ?>">
		</div>
		<div class="form-group">
		  <label>Lotsize:</label>
		  <input type="text" class="form-control" value="<?php echo $row["lotsize"]; ?>">
		</div>
		<div class="form-group">
		  <label>Buyspread:</label>
		  <input type="text" class="form-control" value="<?php echo $row["buyspread"]; ?>">
		</div>
		<div class="form-group">
		  <label>Spread:</label>
		  <input type="text" class="form-control" value="<?php echo $row["spread"]; ?>">
		</div>
		<div class="form-group">
		  <label>Decimal:</label>
		  <input type="text" class="form-control" value="<?php echo $row["decimal"]; ?>">
		</div>
		<?php } } ?>
		<button type="submit" class="btn btn-info">Save</button>
	</form>
<?php require('includes/footer.php');?>