<?php

//echo "Ready to get data";
include_once("../database/constants.php");
include_once("user.php");
include_once("DBOperation.php");
include_once("manage.php");
//$_POST variable is used to collect values from a form with method="post".
// $_POST variable is an array of variable names and values sent by the HTTP POST method.
//The "process.php" file can now use the $_POST variable to catch the form data (notice that the names of the form fields will automatically be the ID keys in the $_POST array)

//For registration processing
if (isset($_POST["username"]) AND isset($_POST["email"])) {  //POST method is declared in main.js
	$user = new User();
	$result = $user->createUserAccount($_POST["username"],$_POST["email"],$_POST["password1"],$_POST["usertype"]);
	echo $result;
	exit();
}

//For login processing
if(isset($_POST["log_email"]) AND isset($_POST["log_password"])){//name given in form is being used in post method 
	//once email and password are set,create user object
	$user = new User();
	$result = $user->userLogin($_POST["log_email"],$_POST["log_password"]);
	echo $result; 
	exit();
}

//to get/fetch category
if(isset($_POST["getCategory"])) {
	$obj = new DBOperation();
	$rows = $obj->getAllRecord("categories");  //passing table
	foreach ($rows as $row) {  //looping through each row
		echo "<option value='".$row["cid"]."'>".$row["category_name"]."</option>"; //goes to main.js
	}
	exit();
}

//to get/fetch brand
if(isset($_POST["getBrand"])) {
	$obj = new DBOperation();
	$rows = $obj->getAllRecord("brands");  //passing table
	foreach ($rows as $row) {  //looping through each row
		echo "<option value='".$row["bid"]."'>".$row["brand_name"]."</option>"; //goes to main.js
	}
	exit();
}

//add category
if(isset($_POST["parent_cat"]) AND isset($_POST["category_name"])) {
	$obj = new DBOperation();
	$result = $obj->addCategory($_POST["parent_cat"],$_POST["category_name"]);
	echo $result;
	exit();
}

//add brand
if(isset($_POST["brand_name"])) {
	$obj = new DBOperation();
	$result = $obj->addBrand($_POST["brand_name"]);
	echo $result;
	exit();
}

//add product
if(isset($_POST["product_name"]) AND isset($_POST["added_date"])) {  //added_date and product_name are the name attributes used in form
	$obj = new DBOperation();
	$result = $obj->addProduct($_POST["select_cat"],
							$_POST["select_brand"],
							$_POST["product_name"],
							$_POST["product_price"],
							$_POST["product_qty"],
							$_POST["added_date"]); //all these are given in name attribute of product form
	echo $result;
	exit();
}


//Manage category
if(isset($_POST["manageCategory"])) {
	$m = new Manage();
	$result = $m->manageRecordWithPagination("categories",$_POST["pageno"]);//result has two indices,rows and pagination,pageno sent by manage.js's data part
	$rows = $result["rows"];
	$pagination = $result["pagination"];
	if(count($rows) > 0) {
		$n = (($_POST["pageno"] - 1) * 5) + 1;
		foreach ($rows as $row) { //looping through each row
			?>
			<tr>
		        <td><?php echo $n; ?></td>
		        <td><?php echo $row["category"]; ?></td>
		        <td><?php echo $row["parent"]; ?></td>
		        <td><a href="#" class="btn btn-success btn-sm">Active</a></td>
		        <td>
		        	<a href="#" did="<?php echo $row['cid']; ?>"class="btn btn-danger btn-sm del_cat"><i class="fa fa-trash-o">&nbsp;</i>Delete</a>
		        	<a href="#" eid="<?php echo $row['cid']; ?>" data-toggle="modal" data-target="#form_category" class="btn btn-info btn-sm edit_cat"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        </td>
		      </tr>
		    <?php
		    $n++;
		}
		?>
			<tr><td colspan="5"><?php echo $pagination; ?></td></tr>
		<?php
		exit();
	}
}


//delete category
if(isset($_POST["deleteCategory"])) {
	$m = new Manage() ;
	$result = $m->deleteRecord("categories","cid",$_POST["id"]);//id sent by manage.js,frm ajax part as data
	echo $result;
	exit();
}


//update category
if(isset($_POST["updateCategory"])) { //when user presses on edit,this code is activated
	$m = new Manage();
	$result = $m->getSingleRecord("categories","cid",$_POST["id"]); //id sent by manage.js as POST request
	//result is array,so convert 
	echo json_encode($result); //Objects in PHP can be converted into JSON by using this PHP function,ex-format-{"name":"John","age":30,"city":"New York"}

	exit();
}


//update record 
if(isset($_POST["update_category"])) { //update_category is the name given in modal form
	$obj = new Manage();
	//getting all the post data through form names given in update_category.php
	$id = $_POST["cid"];
	$name = $_POST["update_category"];
	$parent = $_POST["parent_cat"];
	$result = $obj->update_record("categories",["cid"=>$id],["parent_cat"=>$parent,"category_name"=>$name,"status"=>1]);
	echo $result;
}




/*
A common use of JSON is to read data from a web server, and display the data in a web page.

When exchanging data between a browser and a server, the data can only be text.

JSON is text, and we can convert any JavaScript object into JSON, and send JSON to the server.

We can also convert any JSON received from the server into JavaScript objects.
// Storing data:
myObj = {name: "John", age: 31, city: "New York"};
myJSON = JSON.stringify(myObj); //{"name":"John","age":31,"city":"New York"}
localStorage.setItem("testJSON", myJSON);

// Retrieving data:
text = localStorage.getItem("testJSON");
obj = JSON.parse(text); //{name: "John", age: 31, city: "New York"};
document.getElementById("demo").innerHTML = obj.name;

In JSON, keys must be strings, written with double quotes:
{ "name":"John" }
In JavaScript, keys can be strings, numbers, or identifier names:
{ name:"John" }

*/

//------------------Brand--------------------

//Manage brand
if(isset($_POST["manageBrand"])) {
	$m = new Manage();
	$result = $m->manageRecordWithPagination("brands",$_POST["pageno"]);
	$rows = $result["rows"];
	$pagination = $result["pagination"];
	if(count($rows) > 0) {
		$n = (($_POST["pageno"] - 1) * 5) + 1;
		foreach ($rows as $row) { 
			?>
			<tr>
		        <td><?php echo $n; ?></td>
		        <td><?php echo $row["brand_name"]; ?></td>
		        <td><a href="#" class="btn btn-success btn-sm">Active</a></td>
		        <td>
		        	<a href="#" did="<?php echo $row['bid']; ?>"class="btn btn-danger btn-sm del_brand"><i class="fa fa-trash-o">&nbsp;</i>Delete</a>
		        	<a href="#" eid="<?php echo $row['bid']; ?>" data-toggle="modal" data-target="#form_brand" class="btn btn-info btn-sm edit_brand"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        </td>
		      </tr>
		    <?php
		    $n++;
		}
		?>
			<tr><td colspan="5"><?php echo $pagination; ?></td></tr>
		<?php
		exit();
	}
}


//delete brand
if(isset($_POST["deleteBrand"])) {
	$m = new Manage() ;
	$result = $m->deleteRecord("brands","bid",$_POST["id"]);
	echo $result;
	exit();
}


//update brand
if(isset($_POST["updateBrand"])) { 
	$m = new Manage();
	$result = $m->getSingleRecord("brands","bid",$_POST["id"]); 
	echo json_encode($result); 
	exit();
}


//update record 
if(isset($_POST["update_brand"])) { 
	$obj = new Manage();
	$id = $_POST["bid"];
	$name = $_POST["update_brand"];
	$result = $obj->update_record("brands",["bid"=>$id],["brand_name"=>$name,"status"=>1]);
	echo $result;
}

//---------------Products-----------------

//Manage product
if(isset($_POST["manageProduct"])) {
	$m = new Manage();
	$result = $m->manageRecordWithPagination("products",$_POST["pageno"]);
	$rows = $result["rows"];
	$pagination = $result["pagination"];
	if(count($rows) > 0) {
		$n = (($_POST["pageno"] - 1) * 5) + 1;
		foreach ($rows as $row) { 
			?>
			<tr>
		        <td><?php echo $n; ?></td>
		        <td><?php echo $row["product_name"]; ?></td>
		        <td><?php echo $row["category_name"]; ?></td>
		        <td><?php echo $row["brand_name"]; ?></td>
		        <td><?php echo $row["product_price"]; ?></td>
		        <td><?php echo $row["product_stock"]; ?></td>
		        <td><?php echo $row["added_date"]; ?></td>

		        <td><a href="#" class="btn btn-success btn-sm">Active</a></td>
		        <td>
		        	<a href="#" did="<?php echo $row['pid']; ?>"class="btn btn-danger btn-sm del_product"><i class="fa fa-trash-o">&nbsp;</i>Delete</a>
		        	<a href="#" eid="<?php echo $row['pid']; ?>" data-toggle="modal" data-target="#form_product" class="btn btn-info btn-sm edit_product"><i class="fa fa-edit">&nbsp;</i>Edit</a>
		        </td>
		      </tr>
		    <?php
		    $n++;
		}
		?>
			<tr><td colspan="5"><?php echo $pagination; ?></td></tr>
		<?php
		exit();
	}
}


//delete brand
if(isset($_POST["deleteProduct"])) {
	$m = new Manage() ;
	$result = $m->deleteRecord("products","pid",$_POST["id"]);
	echo $result;
	exit();
}


//update Product
if(isset($_POST["updateProduct"])) { 
	$m = new Manage();
	$result = $m->getSingleRecord("products","pid",$_POST["id"]); 
	echo json_encode($result); 
	exit();
}


//update product 
if(isset($_POST["update_product"])) { 
	$obj = new Manage();
	$id = $_POST["pid"];
	$name = $_POST["update_product"];
	$cat = $_POST["select_cat"];
	$brand = $_POST["select_brand"];
	$price = $_POST["product_price"];
	$qty = $_POST["product_qty"];
	$date = $_POST["added_date"];
	
	$result = $obj->update_record("products",["pid"=>$id],["cid"=>$cat,"bid"=>$brand,"product_name"=>$name,"product_price"=>$price,"product_stock"=>$qty,"added_date"=>$date]);
	echo $result;
}


//------------------------Order Processing-------------------------------

if(isset($_POST["getNewOrderItem"])) {
	$obj = new DBOperation();
	$rows = $obj->getAllRecord("products");
	?>
	<tr>
		<td><b class="number">1</b></td>
		<td> <!--taking all the available products to select option-->
			<select name="pid[]" class="form-control form-control-sm pid" required>	
			<option value="">Choose Products</option>	
				<?php
					foreach ($rows as $row) {  //pid is an array of id's, it increments in loop
						?><option value="<?php echo $row['pid']; ?>"><?php echo $row["product_name"]; ?></option><?php  
					}
				?>	

			</select>						            
		</td>
		<!--creating the input text holder(box) -->
		<td><input name="tqty[]" readonly type="text" class="form-control form-control-sm tqty"></td>   
		<td><input name="qty[]" type="text" class="form-control form-control-sm qty" required></td>
		<td><input name="price[]" type="text" class="form-control form-control-sm price" readonly></span>
		<span><input name="pro_name[]" type="hidden" class="form-control form-control-sm pro_name"></td>
		<td>Rs.<span class="amt">0</span></td>

	</tr>				
	<?php
	exit();

}


//Get price and quantity of one selected item
if(isset($_POST["getPriceAndQty"])) {
	$m = new Manage();
	$result = $m->getSingleRecord("products","pid",$_POST["id"]);  //id sent by order.js
	echo json_encode($result); 
	exit();
}

//--------------Order Accepting--------------

if(isset($_POST["order_date"]) AND isset($_POST["cust_name"])) {
	$orderdate = $_POST["order_date"];
	$cust_name = $_POST["cust_name"];

	//getting array from order_form becoz names given in td are arrays - tqty[],qty[],price[],pro_name[]
	$ar_tqty = $_POST["tqty"];
	$ar_qty = $_POST["qty"];
	$ar_price = $_POST["price"];
	$ar_pro_name = $_POST["pro_name"];

	$sub_total = $_POST["sub_total"];
	$gst = $_POST["gst"];
	$discount = $_POST["discount"];
	$net_total = $_POST["net_total"];
	$paid = $_POST["paid"];
	$due = $_POST["due"];
	$payment_type = $_POST["payment_type"];

	$m = new Manage();
	echo $result = $m->storeCustomerOrderInvoice($orderdate,$cust_name,$ar_tqty,$ar_qty,$ar_price,$ar_pro_name,$sub_total,$gst,$discount,$net_total,$paid,$due,$payment_type);

}

?>