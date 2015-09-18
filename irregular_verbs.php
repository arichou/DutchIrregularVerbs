<?php
require_once "includes/header.inc.php";
$table = "irregular_verbs_past_tense";
$conn = connect();
?>
<br><br>

<!-- ==================Spelling Search Feature================== -->
<div style="text-align: left;">
<form action="" method="post">
	Central vowels: 
	<input type="text" name="spellingSearch" value="<?php echo $_POST['spellingSearch']; ?>"/><br>
	
	<input type="radio" name="column" value="infinitive" <?php if($_POST['column']=='infinitive') echo " checked"?> />Infinitive 
	<input type="radio" name="column" value="simple" <?php if($_POST['column']=='simple') echo " checked"?> />Simple Past 
	<input type="radio" name="column" value="perfect" <?php if($_POST['column']=='perfect') echo " checked"?> />Past Perfect <br>
	
	Highlighted only <input type="checkbox" name="highlightedOnly" checked="checked" /><br>
	<input type="submit" value="Search vowels" />
</form>
</div>
</br>

<?php
$v = $_POST['spellingSearch'];
if ($v && preg_match("/^[a-z]+$/i", $v)) {
	$v = $_POST['spellingSearch'];
	$col = ($_POST['column'] ? $_POST['column'] : "infinitive");
	$string = "select * from $table where $col RLIKE '[bcdfghjklmnpqrstvwxyz]+(".$v.")[bcdfghjklmnpqrstvwxyz]+";
	if($col=='infinitive') $string .= "en";
	$string .= "'";
	if($_POST['highlightedOnly']) $string .= " AND CHAR_LENGTH(highlight)>0";
	
	//echo "COMMAND: $string<br>";
	
	$stmt = $conn->prepare($string);
	
	$stmt->execute();
	$result = $stmt->fetchAll();
	if ($result) {
		?>
		<table class="table table-striped table-bordered">
		<tr>
		<th>Infinitive</th>
		<th>Simple Past</th>
		<th>Past Perfect</th>
		<th>Definition</th>
		<th>Hebben/Zijn</th>
		<th>Notes</th>
		</tr>
		<?php
		foreach($result as $row) {
			extract($row);
			?>
			<tr>
			<td><?php echo $infinitive; ?></td>
			<td><?php echo $simple; ?></td>
			<td><?php echo $perfect; ?></td>
			<td><?php echo $definition; ?></td>
			<td><?php echo $helper; ?></td>
			<td><?php echo $notes; ?></td>
			</tr>
		<?php
		}
	} else {
		print_r($conn->errorInfo());
		echo "No results.<br>";
	}
	?>
	</table>
	<?php
} //end of spelling search
?>

<!-- ==================Search Feature================== -->
<div style="text-align: right;">
<form action="" method="post">
<input type="text" name="search" value="<?php echo $_POST['search']; ?>"/>
<input type="submit" value="Search" />
</form>
</div>
</br>

<?php
if ($_POST['search']) {
	?>
	<table class="table table-striped table-bordered">
	<tr>
	<th>Infinitive</th>
	<th>Simple Past</th>
	<th>Past Perfect</th>
	<th>Definition</th>
	<th>Hebben/Zijn</th>
	<th>Notes</th>
	</tr>
	<?php
		$conn = connect();
		$string = "SELECT * FROM $table WHERE infinitive LIKE :search OR definition LIKE :search OR notes LIKE :search";
		$stmt = $conn->prepare($string);
		
		$searchTerms = '%'.$_POST['search'].'%';
		$stmt->bindParam(':search', $searchTerms);
		
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($result) {
			foreach($result as $row) {
				extract($row);
				?>
				<tr>
				<td><?php echo $infinitive; ?></td>
				<td><?php echo $simple; ?></td>
				<td><?php echo $perfect; ?></td>
				<td><?php echo $definition; ?></td>
				<td><?php echo $helper; ?></td>
				<td><?php echo $notes; ?></td>
				</tr>
			<?php
			}
		} else {
			echo "No results.<br>";
		}
		?>
	</table>
	<?php
} //end of search
?>

<!-- ==================Main Listing================== -->
<table class="table table-striped table-bordered">

	<tr>
	<th>Infinitive</th>
	<th>Simple Past</th>
	<th>Past Perfect</th>
	<th>Definition</th>
	<th>Hebben/Zijn</th>
	<th>Notes</th>
	</tr>

<?php
$stmt = $conn->prepare("SELECT * FROM $table ORDER BY infinitive");
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row) {
	extract($row);
	
	if($highlight) echo "<tr style=\"background: $highlight;\">";
	else echo "<tr>";
	?>
	<td><?php echo $infinitive; ?></td>
	<td><?php echo $simple; ?></td>
	<td><?php echo $perfect; ?></td>
	<td><?php echo $definition; ?></td>
	<td><?php echo $helper; ?></td>
	<td><?php echo $notes; ?></td>
	</tr>
<?php
}
?>

</table>

<?php
$dbh = null;
require_once 'includes/footer.inc.php';
?>
