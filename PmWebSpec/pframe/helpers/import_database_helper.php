<?php
function import_database($data) {


//echo '<script> window.location.href = base_url + "CIAdmin/formula_update?field="+data[0]+"&algorithm="+data[1]+"&id="+data[2];';
 echo '<script> window.location.href = "'.base_url("import_database/database_import.php?hostname=".$data['hostname']."&username=".$data['username']."&pwd=".$data['pwd']."&db=".$data['db']."&file=".$data['file']."").'";</script>'; 
//print_r($data);exit;
// set_time_limit(500);
// 	$dbhost1 = $data['hostname'];//'pmwebspecdb.web.bms.com:3306';
// 	$dbuser1 = $data['username'];//'usr_dbstruct';
// 	$dbpass1 = $data['pwd'];//'ppwd$dbstruct';
// 	$dbname1 = $data['db']; ////'dbstruct';
//   $file = $data['file'];
	
// 	// check if connection is successful
// 	try {
// 		$conn = new mysqli($dbhost1, $dbuser1, $dbpass1, $dbname1);
// 	} catch (mysqli_sql_exception $e) {
// 		error_log("dbstruct database not connected: " . $e->errorMessage(), 0);
// 		die("dbstruct database not connected!");
// 	}
// //$fp = fopen("PmWebSpec-template-20201118.csv", "r");


// $fileName = $data['file'];

// $file = fopen($fileName, "r");

// echo $file;exit;

// while (($column = fgetcsv($file, 1000, ",")) !== FALSE) {
//     print_r($column);exit;
//     //$varorder = "";
//     if (isset($column[0])) {
//         echo $varorder = mysqli_real_escape_string($conn, $column[0]);
//     }

//     if (isset($column[1])) {
//          echo $var_name = mysqli_real_escape_string($conn, $column[1]);
//     }

//     if (isset($column[2])) {
//          echo $var_label = mysqli_real_escape_string($conn, $column[2]);
//     }

//     if (isset($column[3])) {
//        echo $units = mysqli_real_escape_string($conn, $column[3]);
//     }

//      if (isset($column[4])) {
//        echo $type = mysqli_real_escape_string($conn, $column[4]); 
//     }

//     if (isset($column[5])) {
//        echo  $round = mysqli_real_escape_string($conn, $column[5]);
//     }
//     if (isset($column[6])) {
//        echo $missVal = mysqli_real_escape_string($conn, $column[6]);
//     }

//    if (isset($column[7])) {
//         $note = mysqli_real_escape_string($conn, $column[7]);
//     }
//     if (isset($column[8])) {
//         $source = mysqli_real_escape_string($conn, $column[8]);
//     }
//     if (isset($column[9])) {
//         $requiredFlag = mysqli_real_escape_string($conn, $column[9]);
//     }
//     if (isset($column[10])) {
//         $SpecType = mysqli_real_escape_string($conn, $column[10]);
//     } 
//     if (isset($column[11])) {
//         $nameChange = mysqli_real_escape_string($conn, $column[11]);
//     }
//     if (isset($column[12])) {
//         $labelChange = mysqli_real_escape_string($conn, $column[12]);
//     }
//     if (isset($column[13])) {
//         $unitChange = mysqli_real_escape_string($conn, $column[13]);
//     }
//     if (isset($column[14])) {
//         $typeChange = mysqli_real_escape_string($conn, $column[14]);
//     }
//     if (isset($column[15])) {
//         $roundChange = mysqli_real_escape_string($conn, $column[15]);
//     }
//     if (isset($column[16])) {
//         $missValChange = mysqli_real_escape_string($conn, $column[16]);
//     }
//     if (isset($column[17])) {
//         $noteChange = mysqli_real_escape_string($conn, $column[17]);
//     }
//     if (isset($column[18])) {
//         $sourceChange = mysqli_real_escape_string($conn, $column[18]);
//     }
//     if (isset($column[19])) {
//         $erflag = mysqli_real_escape_string($conn, $column[19]);
//     } else {
//     	$erflag = '';
//     }
// echo "Welcome";
// exit;
//     $sql ="INSERT INTO dsstruct (varorder,var_name,var_label,units,type,round,missVal,note,source,requiredFlag,SpecType,nameChange,labelChange,	unitChange,typeChange,roundChange,missValChange,noteChange,sourceChange,erflag)
// 		VALUES ('$varorder','$var_name','$var_label','$units','$type','$round','$missVal','$note','$source','$requiredFlag','$SpecType','$nameChange','$labelChange',	'$unitChange','$typeChange','$roundChange','$missValChange','$noteChange','$sourceChange','$erflag')";


//      // $insertId = $conn->insert($sql);

// $result = $conn->query($sql);

//  if (!$result) {	
// 		echo $conn->error;
// 		die("An error has occurred!");
// 	}


// }
    
// echo "done";
// $conn->query($result);



}

?>

