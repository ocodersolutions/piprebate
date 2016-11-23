<?php require('includes/header.php');?>

<?php 
require 'database.php';
$conn = Database::connect();

//Get parameter from URL
$page = array_key_exists('page', $_GET) ? $_GET['page'] : 1;
$piptype = array_key_exists('pip-type', $_GET) ? $_GET['pip-type'] : 'all';
$branch = array_key_exists('branch', $_GET) ? $_GET['branch'] : '';

$item_per_page = 15;
$page_limit = ($page-1)*$item_per_page;

//Get all piptype
$wherePiptype = ($piptype != '') ? ('WHERE piptype = "' . $piptype . '"') : '';
$sql = "SELECT * FROM `piptype` $wherePiptype";
$results = mysqli_query($conn, $sql);

$piptypeArr = array();
$inttableArr = array();
while($row = $results->fetch_assoc()) { 
  $piptypeArr[] = $row;
  $inttableArr[] = $row['inttable'];
}
?>

<form method="GET" id="form-filter-turnover">
  <div class="row" style="margin-bottom: 20px;">
    <div class="col-sm-6">
      
    </div>
    <div class="col-sm-3">
      <select name="pip-type" class="select-pip-type" class="form-control" style="width: 100%">
        <option value="all">Please Choose Pip Type</option>
        <option value="quote" <?php echo ($piptype == 'quote') ? 'selected' : '' ?>>Quote</option>
        <option value="rebate" <?php echo ($piptype == 'rebate') ? 'selected' : '' ?>>Rebate</option>
      </select>
    </div>
    <div class="col-sm-3">
      <?php  
      $sqlFilterAE = "SELECT DISTINCT(ae) FROM `accbal` WHERE `inttable` IN ('".implode("','", $inttableArr)."')";
      $resultsFilterAE = mysqli_query($conn, $sqlFilterAE);

      if($resultsFilterAE->num_rows > 0){ ?>
      <select name="branch" class="select-branch" class="form-control" style="width: 100%">
        <option value="all">All</option>
        <?php while($itemSelectAE = $resultsFilterAE->fetch_assoc()) { ?>
        <option value="<?php echo $itemSelectAE['ae'] ?>" <?php echo $branch == $itemSelectAE['ae'] ? 'selected' : '' ?>><?php echo $itemSelectAE['ae'] ?></option>
        <?php }//end while ?>
      </select>
      <?php }//end if ?>
    </div>
  </div>
</form>

<?php 
if($piptype && $piptype != 'all') {
?>

<?php 
if($branch == '' || $branch == 'all'){
  $inttableWhere = "WHERE `inttable` IN ('".implode("','", $inttableArr)."')";
} else {
  $inttableWhere = ($branch != '') ? "WHERE `ae` = '".$branch."'" : "";
}
$sqlAccbal = "SELECT DISTINCT(ae) FROM `accbal` $inttableWhere";
$resultsAccbal = mysqli_query($conn, $sqlAccbal);

if ($resultsAccbal->num_rows > 0) { ?>
<table class="table">
  <tbody>
    <?php 
    $aeArr = array();
    while($item = $resultsAccbal->fetch_assoc()) { 
      $aeArr[] = $item;
    }

    foreach($aeArr as $item) { ?>
    <tr class="danger">
        <td>Account</td> 
        <td><?php echo $item['ae'] ?></td>
        <td><?php echo date('M Y',strtotime("-2 month")); ?></td>
        <td><?php echo date('M Y',strtotime("-1 month")); ?></td>
        <td><?php echo date('M Y'); ?></td>
    </tr>
    <?php 
    $sqlSelectAccountByAE = "SELECT DISTINCT(account) FROM `accbal` WHERE ae = '".$item['ae']."'";

    $resultsAccountByAE = mysqli_query($conn, $sqlSelectAccountByAE);
    $totalCurrent = 0;
    $totalLast1 = 0;
    $totalLast2 = 0;

    while($rowAccount = $resultsAccountByAE->fetch_assoc()) {
      $aeArr = array();
      $counterArr = array();
      $totalArr = array();
     
      $sqlCountByAccCurrentMonth = "SELECT * FROM `trade` WHERE `account` = '".$rowAccount['account']."' AND `statusid` = 1 AND MONTH(CURRENT_DATE()) = MONTH(`done_datetime`) AND YEAR(CURRENT_DATE()) = YEAR(`done_datetime`) ORDER BY `trade`.`datetime` ASC";

      $resultsCountByAccCurrentMonth = mysqli_query($conn, $sqlCountByAccCurrentMonth);
      while ($accCurrentMonth = $resultsCountByAccCurrentMonth->fetch_assoc()) {
        $counterArr[$accCurrentMonth['counterid']]['current'] += $accCurrentMonth['quantity'];
        $aeArr[$rowAccount['account']]['current'] += $accCurrentMonth['quantity'];
      }

      $sqlCountByAccLast1 = "SELECT * FROM `trade` WHERE `account` = '".$rowAccount['account']."' AND `statusid` = 1 AND MONTH(CURRENT_DATE())-1 = MONTH(`done_datetime`) AND YEAR(CURRENT_DATE()) = YEAR(`done_datetime`) ORDER BY `trade`.`datetime` ASC";
      
      $resultsCountByAccLast1 = mysqli_query($conn, $sqlCountByAccLast1);
      while ($accLastMonth1 = $resultsCountByAccLast1->fetch_assoc()) {
        $counterArr[$accLastMonth1['counterid']]['last1'] += $accLastMonth1['quantity'];
        $aeArr[$rowAccount['account']]['last1'] += $accLastMonth1['quantity'];
      }

      $sqlCountByAccLast2 = "SELECT * FROM `trade` WHERE `account` = '".$rowAccount['account']."' AND `statusid` = 1 AND MONTH(CURRENT_DATE())-2 = MONTH(`done_datetime`) AND YEAR(CURRENT_DATE()) = YEAR(`done_datetime`) ORDER BY `trade`.`datetime` ASC";
      $resultsCountByAccLast2 = mysqli_query($conn, $sqlCountByAccLast2);
      while ($accLastMonth2 = $resultsCountByAccLast2->fetch_assoc()) {
        $counterArr[$accLastMonth2['counterid']]['last2'] += $accLastMonth2['quantity'];
        $aeArr[$rowAccount['account']]['last2'] += $accLastMonth2['quantity'];
      }

      $totalCurrent += $aeArr[$rowAccount['account']]['current'];
      $totalLast1 += $aeArr[$rowAccount['account']]['last1'];
      $totalLast2 += $aeArr[$rowAccount['account']]['last2'];
    ?>
    <?php if($aeArr[$rowAccount['account']]['current'] || $aeArr[$rowAccount['account']]['last1'] || $aeArr[$rowAccount['account']]['last2']){ ?>
    <tr class="info" style="font-weight: bold">
        <td><?php echo $rowAccount['account'] ?></td> 
        <td><?php echo $item['ae'] ?></td>
        <td><?php echo $aeArr[$rowAccount['account']]['last2'] ?></td>
        <td><?php echo $aeArr[$rowAccount['account']]['last1'] ?></td>
        <td><?php echo $aeArr[$rowAccount['account']]['current'] ?></td>
    </tr>
    <?php }//end if ?>
    <?php foreach ($counterArr as $counterid => $itemCounter) { ?>
    <tr>
        <td><?php echo $counterid ?></td> 
        <td></td>
        <td><?php echo array_key_exists('last2', $itemCounter) ? $itemCounter['last2'] : '--' ?></td>
        <td><?php echo array_key_exists('last1', $itemCounter) ? $itemCounter['last1'] : '--' ?></td>
        <td><?php echo array_key_exists('current', $itemCounter) ? $itemCounter['current'] : '--' ?></td>
    </tr>
    <?php }//end foreach ?>
    <?php }//end while ?>
    <tr class="warning" style="font-weight: bold">
        <td>Total</td> 
        <td><?php echo $item['ae'] ?></td>
        <td><?php echo $totalLast2 ?></td>
        <td><?php echo $totalLast1 ?></td>
        <td><?php echo $totalCurrent ?></td>
    </tr>
    <tr>
        <td></td> 
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php } //end while ?>
  </tbody>
</table>

<?php }//end if 
}?>

<script type="text/javascript">
  $("select.select-pip-type").change(function() {
    $('.select-branch').val('');
    $("#form-filter-turnover").submit();
  });
  $("select.select-branch").change(function() {
    $("#form-filter-turnover").submit();
  });
</script>

<?php require('includes/footer.php');?>