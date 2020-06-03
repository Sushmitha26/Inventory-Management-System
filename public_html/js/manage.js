$(document).ready(function(){
	var DOMAIN = "http://localhost/inventory/public_html/";

	//Manage category
	manageCategory(1);
	function manageCategory(pn) {
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			data : {manageCategory : 1,pageno : pn},
			success : function(data) {
				$("#get_category").html(data);
				//alert(data);
			}
		})
	}

	//The delegate() method attaches one or more event handlers for specified elements that are 
	//children of selected elements, and specifies a function to run when the events occur.

	//here,only when a .page-link class element inside a <body> element is clicked,run this function
	$("body").delegate(".page-link","click",function(){  //body of manage_categories.php is referred here
		var pn = $(this).attr("pn");  //fetching the value in attribute pn
		manageCategory(pn);
	})

	//delete category
	$("body").delegate(".del_cat","click",function() {
		var did = $(this).attr("did");   //taking the instance of /del_cat class using 'this'
		//alert(did);
		if(confirm("Are you sure? You want to delete?!")) { //asks for confirmation from user,ok and cancel
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : {deleteCategory : 1,id : did},
				success : function(data) {
					//$("#del_cat").html(data);
					if(data == "DEPENDENT_CATEGORY") {  //data is response from php script
						alert("Sorry! this category is parent of other sub categories")
					}else if(data == "CATEGORY_DELETED") {
						alert("Category deleted successfully..!");
						manageCategory(1);
					}else if(data == "RECORD_DELETED") {
						alert("Deleted successfully..!");
					}else {
						alert(data);
					}
					
				}
			})
		}else {
			alert("No");
		}
	})

	//Fetch available categories to the dropdown select table while choosing category
	fetch_category();
	function fetch_category() {  
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			data : {getCategory:1},  
			success : function(data) {
				var root = "<option value='0'>Root</option>";  //this is for 'add' in category
				var choose = "<option value=''>Choose Category</option>";
				$("#parent_cat").html(root+data);  //for select option in category
				$("#select_cat").html(choose+data);  //whatever category user has chosen earlier will be present
			}

		})
	}

	//fetch brands to the dropdown select table in while choosing brand in products
	fetch_brand();
	function fetch_brand() {  //get records from category from this page by calling this function
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",

			//"data" is what sent to the server
			data : {getBrand:1},  //to enable a particular block of code,use getBrand
			success : function(data) {
				//alert(data);
				var choose = "<option value=''>Choose Brand</option>";//this is for 'add' in brands
				$("#select_brand").html(choose+data);  //for select option in brands
			}

		})
	}

	//update category
	$("body").delegate(".edit_cat","click",function(){
		var eid = $(this).attr("eid");
		//alert(eid);
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST", 
			dataType : "json", //to place data in form,we need an array.so datatype is json
			data : {updateCategory:1,id:eid},
			success : function(data) {
				console.log(data);
				$("#cid").val(data["cid"]);
				$("#update_category").val(data["category_name"]);
				$("#parent_cat").val(data["parent_cat"]);

			}
		})
	})

	$("#update_category_form").on("submit",function() {
		if($("#update_category").val() == "") {
			$("#update_category").addClass("border-danger");
			$("#cat_error").html("<span class='text-danger'>Please enter category name</span>");
		}else {
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : $("#update_category_form").serialize(),  //serialize the form data entered by user
				success : function(data) {  //response,in the name of data
					alert(data);
					window.location.href = "";
				}
			})
		}
	})


	//----------------Brand----------------

	manageBrand(1);
	function manageBrand(pn) {
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			data : {manageBrand : 1,pageno : pn},
			success : function(data) {
				$("#get_brand").html(data);
				//alert(data);
			}
		})
	}

	$("body").delegate(".page-link","click",function(){  //body of manage_categories.php is referred here
		var pn = $(this).attr("pn");  //fetching the value in attribute pn
		manageBrand(pn);
	})

	//delete brand
	$("body").delegate(".del_brand","click",function() {
		var did = $(this).attr("did");   //taking the instance of /del_cat class using 'this'
		//alert(did);
		if(confirm("Are you sure? You want to delete?!")) { //asks for confirmation from user,ok and cancel
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : {deleteBrand:1,id:did},
				success : function(data) {
					if(data == "RECORD_DELETED") {
						alert("Brand deleted successfully..!");
						manageBrand(1);
					}
					else {
						alert(data);
					}
					
				}
			})
		}
	})


	//update brand
	$("body").delegate(".edit_brand","click",function(){
		var eid = $(this).attr("eid");
		//alert(eid);
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST", 
			dataType : "json", //to place data in form,we need an array.so datatype is json
			data : {updateBrand:1,id:eid},
			success : function(data) {
				console.log(data);
				$("#bid").val(data["bid"]);
				$("#update_brand").val(data["brand_name"]);
			}
		})
	})


	$("#update_brand_form").on("submit",function() {
		if($("#update_brand").val() == "") {
			$("#update_brand").addClass("border-danger");
			$("#brand_error").html("<span class='text-danger'>Please enter brand name</span>");
		}else {
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : $("#update_brand_form").serialize(),  //serialize the form data entered by user
				success : function(data) {  //response,in the name of data
					alert(data);
					window.location.href = "";
				}
			})
		}
	})


//---------------------Products----------------------

manageProduct(1);
	function manageProduct(pn) {
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			data : {manageProduct : 1,pageno : pn},
			success : function(data) {
				$("#get_product").html(data);
				//alert(data);
			}
		})
	}

	$("body").delegate(".page-link","click",function(){  //body of manage_categories.php is referred here
		var pn = $(this).attr("pn");  //fetching the value in attribute pn
		manageProduct(pn);
	})


	//delete product
	$("body").delegate(".del_product","click",function() {
		var did = $(this).attr("did");   //taking the instance of /del_cat class using 'this'
		//alert(did);
		if(confirm("Are you sure? You want to delete?!")) { //asks for confirmation from user,ok and cancel
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : {deleteProduct:1,id:did},
				success : function(data) {
					if(data == "RECORD_DELETED") {
						alert("Product deleted successfully..!");
						manageProduct(1);
					}
					else {
						alert(data);
					}
					
				}
			})
		}
	})


	//update product
	$("body").delegate(".edit_product","click",function(){
		var eid = $(this).attr("eid");
		//alert(eid);
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST", 
			dataType : "json", //to place data in form,we need an array.so datatype is json
			data : {updateProduct:1,id:eid},
			success : function(data) {
				console.log(data);
				$("#pid").val(data["pid"]);
				$("#update_product").val(data["product_name"]);
				$("#select_cat").val(data["cid"]);
				$("#select_brand").val(data["bid"]);
				$("#product_price").val(data["product_price"]);
				$("#product_qty").val(data["product_stock"]); //data["..."] is the name in database
			}
		})
	})


	//Update product
	$("#update_product_form").on("submit",function(){
		$.ajax({
				url : DOMAIN+"includes/process.php",
				method : "POST",
				data : $("#update_product_form").serialize(),
				success : function(data) {
					//alert(data);
					if(data == "UPDATED") {
						alert("Product updated successfully..!");
						window.location.href=""; //automatically refreshes the screen after update
					}else {
						alert(data);
					}
				}
			})
	})

})