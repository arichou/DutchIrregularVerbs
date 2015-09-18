<?php

function pageFilesInDirectory ($fnm) {
	if($fnm=='error_log.php' || $fnm=="." || $fnm=="..") return false;
	
	return preg_match('/\.php$/', $fnm);
}

function connect(){
	$dbh = new PDO('mysql:host=localhost;dbname=markatch_dutch;charset=utf8mb4', 'markatch_dutch', 'REMOVED');
	return $dbh;
}

function insert($dbh, $table, $values){

	extract($values);
	
	$stmt = $dbh->prepare("INSERT INTO $table 
		(infinitive, simple, perfect, helper, definition, notes) 
		VALUES (:infinitive, :simple, :perfect, :helper, :definition, :notes)
		");
	$stmt->bindParam(':infinitive', $infinitive);
	$stmt->bindParam(':simple', $simple);
	$stmt->bindParam(':perfect', $perfect);
	$stmt->bindParam(':helper', $helper);
	$stmt->bindParam(':definition', $definition);
	$stmt->bindParam(':notes', $notes);
	
	// insert one row
	$stmt->execute();

}

function update($dbh, $table, $values) {
	
	extract($values);
	
	$string = "UPDATE $table SET ";
	foreach($values as $column=>$value){
		$string .= "$column=:value1, ";	
	}
	$string = substr($string, 0, -2); //remove last comma-space
	
	$string .= " WHERE id=:id";
	echo "COMMAND: $string<br>";
	$stmt = $dbh->prepare($string);
	$stmt->bindParam(${$value[$i]}, $value[$i]);
	
	// insert one row
	$stmt->execute();
	
}

?>
