<?php require('includes/header.php');?>
<div class="info">Are you sure ?</div>
<form id="delete-counter" action="action.php" method="post">
	<input type="hidden" name="delete" value="delete">
	<button type="submit" class="btn btn-info">OK</button>
	<button type="submit" class="btn btn-danger">Cancel</button>
</form>
<?php require('includes/footer.php');?>