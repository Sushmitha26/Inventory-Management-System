<?php

class Manage
{
	
	private $con;  //variable for database connection

	function __construct()
	{
		include_once("../database/db.php");
		$db = new Database();  //object
		$this->con = $db->connect();
	}

	public function manageRecordWithPagination($table,$pg_no) {

		$a = $this->pagination($this->con,$table,$pg_no,5);
		//echo $a["limit"];

		if($table == 'categories') {
			$sql = "SELECT p.cid,p.category_name as category, c.category_name as parent,p.status FROM categories p LEFT JOIN categories c ON p.parent_cat = c.cid ".$a["limit"];  
		}
		else if($table == "products") {
			$sql = "SELECT p.pid,p.product_name,c.category_name,b.brand_name,p.product_price,p.product_stock,p.added_date,p.p_status FROM products p,brands b,categories c WHERE p.bid = b.bid AND p.cid = c.cid ".$a["limit"];
		}

		else {
			$sql = "SELECT * FROM ".$table." ".$a["limit"];
		}
		//echo $sql;
		$result = $this->con->query($sql) or die($this->con->error);
		$rows = array();

		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}

		return ["rows"=>$rows,"pagination"=>$a["pagination"]]; //returning 'rows' and 'pagination' arrays

	}

	private function pagination($con,$table,$pg_no,$n) {//can call this functn to any table to get paginatn
		//You need to alias using the 'as' keyword in order to call it from mysql_fetch_assoc
		$result = $con->query("SELECT COUNT(*) as totalrows FROM ".$table); //[totalrows]=>some_no.
		$row = mysqli_fetch_assoc($result);  //total no of rows stored in row,similar to totalRecords
		//$totalRecords = 100000; 
		//echo $row;
		$pageno = $pg_no;
		$numberOfRecordsPerPage = $n;
		//total pages required if there are 100000 records with n records per page
		//$totalPages = ceil($totalRecords/$numberOfRecordsPerPage);

		//totalPages also tell which is the last page
		//$last = ceil($totalRecords/$numberOfRecordsPerPage);

		//echo $row["totalrows"]; ->no of rows
		$last = ceil($row["totalrows"]/$numberOfRecordsPerPage); //column fetched as rows in query

		//echo "Total Pages ".$last."<br/>";

		$pagination ="<ul class='pagination'>";

		if($last != 1) {
			if ($pageno > 1) {
				$previous = "";
				$previous = $pageno - 1;
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$previous."' href='#' style = 'color:#333;'> Previous </a></li>";
			}

			for($i=$pageno-5; $i<$pageno; $i++) {
				if ($i > 0) {
					$pagination .= "<li class='page-item'><a class='page-link' pn='".$i."' href='#'> ".$i." </a></li>";  //here we show the no.s 1 2 3...
				}
			}
			//pagination.php?pageno=".$pageno."  
			//pn is not html attribute,it's our attribute
			$pagination .= "<li class='page-item'><a class='page-link' pn='".$pageno."' href='#' style='color:#333;'> $pageno </a></li>";

			for ($i=$pageno+1; $i<=$last ; $i++) { 
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$i."' href='#'> ".$i." </a></li>";
				if($i > $pageno + 4) {
					break;
				}
			}
			if ($last > $pageno) {  //only when last pageno is > current pageno,next button shd be present
				$next = $pageno + 1;
				$pagination .= "<li class='page-item'><a class='page-link' pn='".$next."' href='#' style = 'color:#333;'> Next </a></li></ul>";
			}

		}
		//LIMIT 0,10  ->in page1,from 1 to 10 records
		//LIMIT 10,10 -> starting from record 11,10 records are put in page2
		//LIMIT 20,10 -> starting from record 21,10 records are selected in page3
		$limit = "LIMIT ".($pageno-1) * $numberOfRecordsPerPage.",".$numberOfRecordsPerPage;

		return ["pagination"=>$pagination,"limit"=>$limit];
	}

	public function deleteRecord($table,$prim_key,$id) {
		if($table == "categories") {
			$pre_stmt = $this->con->prepare("SELECT ".$id." FROM categories WHERE parent_cat = ?");
			$pre_stmt->bind_param("i",$id);
			$pre_stmt->execute();
			$result = $pre_stmt->get_result() or die($this->con->error);
			if($result->num_rows > 0) {
				return "DEPENDENT_CATEGORY";  //ex-Electronics
			}else {
				//return "It can be deleted";  //ex-Antivirus
				$pre_stmt = $this->con->prepare("DELETE FROM ".$table." WHERE ".$prim_key." = ?");//prim_key can be cid,bid,pid
				$pre_stmt->bind_param("i",$id);
				$result = $pre_stmt->execute() or die($this->con->error);
				if($result) {
					return "CATEGORY_DELETED";
				}
			}
		}
		else{  //for other tables except categories
			//return "It can be deleted"; //for other tables lyk brands,products
			$pre_stmt = $this->con->prepare("DELETE FROM ".$table." WHERE ".$prim_key." = ?");//prim_key can be bid or pid
				$pre_stmt->bind_param("i",$id);
				$result = $pre_stmt->execute() or die($this->con->error);
				if($result) {
					return "RECORD_DELETED";
				}
		}
	}


	public function getSingleRecord($table,$prim_key,$id) {
		$pre_stmt = $this->con->prepare("SELECT * FROM ".$table." WHERE ".$prim_key."= ? LIMIT 1");
		$pre_stmt->bind_param("i",$id);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		if($result->num_rows == 1) {
			$row = $result->fetch_assoc(); //fetching a row as associative array
		}
		return $row;
	}


	//one function to update all the tables 
	public function update_record($table,$where,$fields) {//where,fields are assoc array
		$sql = "";
		$condition = "";
		foreach ($where as $key => $value) {
			// id = '5' AND m_name = 'something'
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		foreach ($fields as $key => $value) {
			//UPDATE table SET m_name = '' , qty = '' WHERE id = '';
			$sql .= $key . "='".$value."', ";
		}
		$sql = substr($sql, 0,-2);
		$sql = "UPDATE ".$table." SET ".$sql." WHERE ".$condition;
		if(mysqli_query($this->con,$sql)){
			return "UPDATED";
		}
	}


	public function storeCustomerOrderInvoice($orderdate,$cust_name,$ar_tqty,$ar_qty,$ar_price,$ar_pro_name,$sub_total,$gst,$discount,$net_total,$paid,$due,$payment_type) {

		$pre_stmt = $this->con->prepare("INSERT INTO 
			`invoice`(`customer_name`, `order_date`, `sub_total`,
			 `gst`, `discount`, `net_total`, `paid`, `due`, `payment_type`) VALUES (?,?,?,?,?,?,?,?,?)");

		$pre_stmt->bind_param("ssdddddds",$cust_name,$orderdate,$sub_total,$gst,$discount,$net_total,$paid,$due,$payment_type);

		$pre_stmt->execute() or die($this->con->error);
		$invoice_no = $pre_stmt->insert_id; //gives last id of product inserted into invoice table 

		if($invoice_no != null) {

			for($i = 0; $i < count($ar_price); $i++) { //count of any array

				//Here we are finding the remaing quantity after giving customer
				$rem_qty = $ar_tqty[$i] - $ar_qty[$i];
				if ($rem_qty < 0) {
					return "ORDER_FAIL_TO_COMPLETE";
				}else {
					//Update Product stock
					$sql = "UPDATE products SET product_stock = '$rem_qty' WHERE product_name = '".$ar_pro_name[$i]."'";
					$this->con->query($sql);

				}

				$insert_product = $this->con->prepare("INSERT INTO `invoice_details`(`invoice_no`, 
				`product_name`, `price`, `qty`) VALUES (?,?,?,?)");
				$insert_product->bind_param("isdd",$invoice_no,$ar_pro_name[$i],$ar_price[$i],$ar_qty[$i]);
				$insert_product->execute() or die($this->con->error);

			}

			return $invoice_no;
		}

		//return "ORDER_COMPLETED";
	}


}

//$obj = new Manage();
//echo "<pre>";
//print_r($obj->manageRecordWithPagination("categories",1));

//echo $obj->deleteRecord("categories","cid",13);
//print_r($obj->getSinlgeRecord("categories","cid",1));//output-Array ( [cid] => 1 [parent_cat] => 0 [category_name] => Electronics [status] => 1 )
//echo $obj->update_record("categories",["cid"=>11],["parent_cat"=>1,"category_name"=>"Laptop","status"=>1]);
?>

