<?php


class DBOperation
{
	private $con;  //variable for database connection

	function __construct()
	{
		include_once("../database/db.php");
		$db = new Database();  //object
		$this->con = $db->connect();
	}


	public function addCategory($parent,$cat) {
		$pre_stmt = $this->con->prepare("INSERT INTO `categories`( `parent_cat`, `category_name`, `status`) VALUES (?,?,?)");
		$status = 1;  //since we r inserting,initially it's active
		$pre_stmt->bind_param("isi",$parent,$cat,$status);  //parent_cat is integer,its id
		$result = $pre_stmt->execute() or die($this->con->error);
		if($result) {
			return "CATEGORY_ADDED";
		}else {
			return 0;
		}
	}


	public function addBrand($brand_name) {
		$pre_stmt = $this->con->prepare("INSERT INTO `brands`(`brand_name`, `status`) VALUES (?,?)");
		$status = 1;  //since we r inserting,initially it's active
		$pre_stmt->bind_param("si",$brand_name,$status);  //parent_cat is integer,its id
		$result = $pre_stmt->execute() or die($this->con->error);
		if($result) {
			return "BRAND_ADDED";
		}else {
			return 0;
		}
	}


	public function addProduct($cid,$bid,$pro_name,$pro_price,$stock,$date) {
		$pre_stmt = $this->con->prepare("INSERT INTO `products`(`cid`, `bid`, `product_name`, `product_price`, `product_stock`, `added_date`, `p_status`) VALUES (?,?,?,?,?,?,?)");
		$status = 1;  //since we r inserting,initially it's active
		$pre_stmt->bind_param("iisdisi",$cid,$bid,$pro_name,$pro_price,$stock,$date,$status);  //parent_cat is integer,its id
		$result = $pre_stmt->execute() or die($this->con->error);
		if($result) {
			return "NEW_PRODUCT_ADDED";
		}else {
			return 0;
		}
	}


	public function getAllRecord($table) {  //get data from table
		$pre_stmt = $this->con->prepare("SELECT * FROM ".$table); 
		//print_r($pre_stmt);
		$pre_stmt->execute() or die($this->con->error);
		$result = $pre_stmt->get_result();
		//print_r($result);
		$rows = array();
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {  //fetch_assoc increments itself to next row
				$rows[] = $row; //adding all tuples(which is initially stored into 'row',which is an associative array storing a row data each time in the form of key-value) to 'rows'
			}
			return $rows;
		}
		return "NO_DATA";
	}

}

//$opr = new DBOperation();
//echo $opr->addCategory(0,"Electronics");  //parent category is 0,i.e. root 
//echo $opr->addCategory(0,"Software");
//echo $opr->addCategory(0,"Gadgets");
//echo $opr->addCategory(1,"Mobiles");
//echo "<pre>";
//print_r($opr->getAllRecord("categories"));
?>