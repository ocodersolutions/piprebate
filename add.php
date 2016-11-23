<?php require('includes/header.php');?>
<form id="add-counter" action="action.php" method="post">
	<input type="hidden" name="add" value="add">
	<div class="form-group">
	  <label>Counterid:</label>
	  <input name="counterid" type="text" class="form-control" value="">
	</div>
	<div class="form-group">
	  <label>Lotsize:</label>
	  <input name="lotsize" type="text" class="form-control" value="">
	</div>
	<div class="form-group">
	  <label>Buyspread:</label>
	  <input name="buyspread" type="text" class="form-control" value="">
	</div>
	<div class="form-group">
	  <label>Spread:</label>
	  <input name="spread" type="text" class="form-control" value="">
	</div>
	<div class="form-group">
	  <label>Decimal:</label>
	  <input name="decimal" type="text" class="form-control" value="">
	</div>
	<button type="submit" class="btn btn-success">Add</button>
</form>
<?php require('includes/footer.php');?>