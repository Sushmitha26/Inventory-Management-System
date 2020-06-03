$(document).ready(function(){
	var DOMAIN = "http://localhost/inventory/public_html/";

	$("#register_form").on("submit",function(){
		var status = false;
		var name = $("#username"); //#id selector uses the id attribute of an HTML tag to find the specific element.
		var email = $("#email");
		var pass1 = $("#password1");
		var pass2 = $("#password2");
		var type = $("#usertype");
		var n_patt = new RegExp(/^[A-Za-z]+$/);
		var e_patt = new RegExp(/^[a-z0-9_-]+(\.[a-z0-9_-]+)*@[a-z0-9_-]+(\.[a-z0-9_-]+)*(\.[a-z]{2,4})$/);  //e_patt is object of RE

		if (name.val() == "" || name.val().length < 3) { //.val() method is primarily used to get the values of form elements such as input, select and textarea
			name.addClass("border-danger"); //adding a class to put red border over input text
			$("#u_error").html("<span class='text-danger'>Please enter Name and Name should be more than 2 char</span>");
			status = false;
		}else {
			name.removeClass("border-danger");
			$("#u_error").html("");
			status = true;
		}

		if (!e_patt.test(email.val())) { //Returns true if it finds a match
			email.addClass("border-danger"); 
			$("#e_error").html("<span class='text-danger'>Please enter valid email address</span>");
			status = false;
		}else {
			email.removeClass("border-danger");
			$("#e_error").html("");
			status = true;
		}

		if (pass1.val() == "" || pass1.val().length < 9) { 
			pass1.addClass("border-danger"); 
			$("#p1_error").html("<span class='text-danger'>Please enter more than 9 character password</span>");
			status = false;
		}else {
			pass1.removeClass("border-danger");
			$("#p1_error").html("");
			status = true;
		}

		if (pass2.val() == "" || pass2.val().length < 9) { 
			pass2.addClass("border-danger"); 
			$("#p2_error").html("<span class='text-danger'>Please enter more than 9 character password</span>");
			status = false;
		}else {
			pass2.removeClass("border-danger");
			$("#p2_error").html("");
			status = true;
		}

		if (type.val() == "") { 
			type.addClass("border-danger"); 
			$("#t_error").html("<span class='text-danger'>Please select user type</span>");
			status = false;
		}else {
			type.removeClass("border-danger");
			$("#t_error").html("");
			status = true;
		}
		//Main use-Encode a set of form elements as a string for submission.
		//The serialize() method creates a URL encoded text string by serializing form values.
		//The serialized values can be used in the URL query string when making an AJAX request.
		//ex-form after serializing-> FirstName=Mickey&LastName=Mouse 
		if((pass1.val() == pass2.val()) && status == true) {
				$(".overlay").show();
				$.ajax({
					url : DOMAIN+"/includes/process.php",
					method : "POST",
					data : $("#register_form").serialize(),  //getting the form data through form id
					success : function(data) {
						//alert(data);
						if(data == "EMAIL_ALREADY_EXISTS") {
							$(".overlay").hide();
							alert("Your email is already used");
						}
						else if(data == "SOME_ERROR"){
							$(".overlay").hide();
							alert("Something went wrong!!");
						}
						else {
							//used to encode a URI.This function encodes special characters, 
							//except: , / ? : @ & = + $ # (Use encodeURIComponent() to encode these characters).
							//The window.location object can be used to get the 
							//current page address (URL) and to redirect the browser to a new page
							//window.location.href returns the href (URL) of the current page
							$(".overlay").hide();
							window.location.href = encodeURI(DOMAIN+"/index.php?msg=You are registered.Now you can login");
							//console.log(data);  prints the insert id of that user,which is the id in sql
							//alert(data);
						}
					}
				})

		}else {
			pass2.addClass("border-danger"); 
			$("#p2_error").html("<span class='text-danger'>Password did not match</span>");
			status = true;
		}
	})

	//For login part
	$("#login_form").on("submit",function(){
		var email = $("#log_email");
		var pass = $("#log_password");
		var status = false;

		if (email.val() == "") {
			email.addClass("border-danger");
			$("#e_error").html("<span class='text-danger'>Please enter email address</span>");
			status = false;
		}else {
			email.removeClass("border-danger");
			$("#e_error").html("");
			status = true;
		}

		if (pass.val() == "") {
			pass.addClass("border-danger");
			$("#p_error").html("<span class='text-danger'>Please enter Password</span>");
			status = false;
		}else {
			pass.removeClass("border-danger");
			$("#p_error").html("");
			status = true;
		}

		if(status) {
			$(".overlay").show();
			//alert("ready");
			$.ajax({
					url : DOMAIN+"/includes/process.php",
					method : "POST",
					data : $("#login_form").serialize(),  //getting the login form data through form id
					success : function(data) {
						//alert(data);
						if(data == "NOT_REGISTERED") {		
							$(".overlay").hide();
							email.addClass("border-danger"); 
							$("#e_error").html("<span class='text-danger'>You are not registered</span>");
						}
						else if(data == "PASSWORD DID NOT MATCH"){
							$(".overlay").hide();
							pass.addClass("border-danger");
							$("#p_error").html("<span class='text-danger'>Please enter correct password</span>");
							status = false;
						}
						else {
							$(".overlay").hide();
							console.log(data);
							window.location.href = DOMAIN+"/dashboard.php";
						}
					}
				})
		}
 	})


	//Fetch available categories to the dropdown select table while choosing category
	fetch_category();
	function fetch_category() {  //get records from category from this page by calling this function
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			//"data" is what sent to the server
			// If data is an object, it gets serialized to an application/x-www-form-urlencoded string and 
			//then placed in the query string or request body as appropriate for the request type (GET/POST).
			//You don't send JSON (usually), you send simple GET or POST HTTP parameters. They are given to the 
			//ajax method in an object literal usually, but you could have used the string "{getparam:value}", too.
			//If you provide an object, jQuery will do the parameter-serialisation and URL-encoding for you - 
			//they're sent as x-www-form-urlencoded.

			data : {getCategory:1},  //to enable a particular block of code,use getCategory
			success : function(data) {
				//alert(data);
				var root = "<option value='0'>Root</option>";  //this is for 'add' in category
				var choose = "<option value=''>Choose Category</option>";//this is for 'add' in brands
				$("#parent_cat").html(root+data);  //for select option in category
				$("#select_cat").html(choose+data);  //for select option in brands
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



	//Add category
	$("#category_form").on("submit",function() {
		if($("#category_name").val() == "") {
			$("#category_name").addClass("border-danger");
			$("#cat_error").html("<span class='text-danger'>Please enter category name</span>");
		}else {
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : $("#category_form").serialize(),  //serialize the form data entered by user
				success : function(data) {  //response,in the name of data
					//alert(data);
					if(data == "CATEGORY_ADDED") {
						$("#category_name").removeClass("border-danger");
						$("#cat_error").html("<span class='text-success'>New category added successfully..!</span>");
						$("#category_name").val("");
						fetch_category();
					}else {
						alert(data);
					}
				}
			})
		}
	})

	//Add brand
	$("#brand_form").on("submit",function() {
		if($("#brand_name").val() == "") {
			$("#brand_name").addClass("border-danger");
			$("#brand_error").html("<span class='text-danger'>Please enter brand name</span>");
		}else {
			$.ajax({
				url : DOMAIN+"includes/process.php",
				method : "POST",
				data : $("#brand_form").serialize(),
				success : function(data) {
					if(data == "BRAND_ADDED") {
					$("#brand_name").removeClass("border-danger");
					$("#brand_error").html("<span class='text-success'>New brand added successfully..!</span>");
					$("#brand_name").val("");
					fetch_brand();
				}else {
					alert(data);
				}
				}
			})
		}
	})


	//Add product
	$("#product_form").on("submit",function(){
		$.ajax({
				url : DOMAIN+"includes/process.php",
				method : "POST",
				data : $("#product_form").serialize(),
				success : function(data) {
					if(data == "NEW_PRODUCT_ADDED") {
						alert("New Product Added Successfully..!");
						$("#product_name").val("");
						$("#select_cat").val("");
						$("#select_brand").val("");
						$("#product_price").val("");
						$("#product_qty").val("");
				}else {
					console.log(data);
					alert(data);
				}
				}
			})
	})


	

})