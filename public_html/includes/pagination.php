<?php

$con = mysqli_connect("localhost","root","","test");  //opens a new connection to the MySQL server.host,user,password,db

function pagination($con,$table,$pg_no,$n) {//can call this functn to any table to get paginatn
	//You need to alias using the 'as' keyword in order to call it from mysql_fetch_assoc
	$result = $con->query("SELECT COUNT(*) as totalrows FROM ".$table); //[totalrows]=>some_no.
	$row = mysqli_fetch_assoc($result);  //total no of rows stored in row,similar to totalRecords
	//$totalRecords = 100000;  
	$pageno = $pg_no;
	$numberOfRecordsPerPage = $n;
	//total pages required if there are 100000 records with n records per page
	//$totalPages = ceil($totalRecords/$numberOfRecordsPerPage);

	//totalPages also tell which is the last page
	//$last = ceil($totalRecords/$numberOfRecordsPerPage);
	$last = ceil($row["totalrows"]/$numberOfRecordsPerPage); //column fetched as rows in query

	echo "Total Pages ".$last."<br/>";

	$pagination ="";

	if($last != 1) {
		if ($pageno > 1) {
			$previous = "";
			$previous = $pageno - 1;
			$pagination .= "<a href='pagination.php?pageno=".$previous."' style = 'color:#333;'> Previous </a>";
		}

		for($i=$pageno-5; $i<$pageno; $i++) {
			if ($i > 0) {
				$pagination .= "<a href='pagination.php?pageno=".$i."'> ".$i." </a>";  //here we show the no.s 1 2 3...
			}
		}

		$pagination .= "<a href='pagination.php?pageno=".$pageno."' style='color:#333;'> $pageno </a>";

		for ($i=$pageno+1; $i<=$last ; $i++) { 
			$pagination .= "<a href='pagination.php?pageno=".$i."'> ".$i." </a>";
			if($i > $pageno + 4) {
				break;
			}
		}
		if ($last > $pageno) {  //only when last pageno is > current pageno,next button shd be present
			$next = $pageno + 1;
			$pagination .= "<a href='pagination.php?pageno=".$next."' style = 'color:#333;'> Next </a>";
		}

	}

	//LIMIT 0,10  ->in page1,from 1 to 10 records
	//LIMIT 10,10 -> starting from record 11,10 records are put in page2
	//LIMIT 20,10 -> starting from record 21,10 records are selected in page3
	$limit = "LIMIT ".($pageno-1) * $numberOfRecordsPerPage.",".$numberOfRecordsPerPage;

	return ["pagination"=>$pagination,"limit"=>$limit];
}

if (isset($_GET["pageno"])) { //only when user selects a particular pageno,that becomes the current page
	$pageno = $_GET["pageno"];

	$table = "paragraph";

	$array = pagination($con,$table,$pageno,10); //returns array
	//echo "<pre>";
	//print_r(pagination($con,"xxx",$pageno,10));
	$sql = "SELECT * FROM".$table." ".$array["limit"];

	$result = $con->query($sql); //sql query being sent to db 

	// Associative array
	while($row = mysqli_fetch_assoc($result)) {
		echo "<div style='margin:0 auto;font-size:20px;'> <b>".$row["pid"]."</b> ".$row["p_description"]."</div>";
	}
	echo "<div style='font-size:22px;'>".$array["pagination"]."</div>";	

}


?>