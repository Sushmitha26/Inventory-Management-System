<?php
include_once("./database/constants.php");
if(!isset($_SESSION["userid"])) {
	header("location:".DOMAIN."/");  
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Inventory Management System</title>
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<!--including jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!--popper js-->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<!--Bootstrap js-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!--font awesome cdn-->
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">    

    <script type="text/javascript" src="../js/main.js"></script>
</head>
<body>
	<!--Navbar linking-->
	<?php include_once("./templates/header.php"); ?>
	<br>

	<div class="container">
		<div class="row">

			<div class="col-md-4">
				<div class="card mx-auto">
				  <img src="../images/user5.png" style="width: 60%" class="card-img-top mx-auto" alt="User icon">
				  <div class="card-body">
				    <h5 class="card-title">Profile Info</h5>
				    <p class="card-text"><i class="fa fa-user">&nbsp;</i>UserName : <?php echo $_SESSION["username"] ?></p>
				    <p class="card-text"><i class="fa fa-user">&nbsp;</i>UserType : <?php echo $_SESSION["usertype"] ?></p>
				    <p class="card-text"><i class="fa fa-clock-o">&nbsp;</i>Last Login : <?php echo $_SESSION["last_login"] ?>  </p>
				    <a href="#" class="btn btn-primary"><i class="fa fa-edit">&nbsp;</i>Edit Profile</a>
				  </div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="jumbotron" style="width: 100%; height:100%">
					<h1>Welcome Admin,</h1>
					<p>Have a nice day</p>
					<div class="row">
						<div class="col-sm-6">
							<iframe src="http://free.timeanddate.com/clock/i7asra7b/n438/szw160/szh160/cf100/hnce1ead6" frameborder="0" width="160" height="160"></iframe>

						</div>
						<div class="col-sm-6">
							<div class="card">
						      <div class="card-body">
						        <h5 class="card-title">Orders</h5>
						        <p class="card-text">Here you can place new order and print invoices</p>
						        <a href="new_order.php" class="btn btn-primary">New Order</a>
						      </div>
						    </div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<br>

	<div class="container">
		<div class="row">

			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
			        <h5 class="card-title">Category</h5>
			        <p class="card-text">Here you can add and manage categories</p>
			        <a href="#" data-toggle="modal" data-target="#form_category" class="btn btn-primary"><i class="fa fa-plus">&nbsp;</i>Add</a>
			        <a href="manage_categories.php" class="btn btn-primary"><i class="fa fa-edit">&nbsp;</i>Manage</a>
			        </div>
		    	</div>
			</div>

			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
			        <h5 class="card-title">Brands</h5>
			        <p class="card-text">Here you can add and manage brands</p>
			        <a href="#" data-toggle="modal" data-target="#form_brand" class="btn btn-primary"><i class="fa fa-plus">&nbsp;</i>Add</a>
			        <a href="manage_brand.php" class="btn btn-primary"><i class="fa fa-edit">&nbsp;</i>Manage</a>
			        </div>
		    	</div>
			</div>

			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
			        <h5 class="card-title">Products</h5>
			        <p class="card-text">Here you can add and manage Products</p>
			        <a href="#" data-toggle="modal" data-target="#form_products" class="btn btn-primary"><i class="fa fa-plus">&nbsp;</i>Add</a>
			        <a href="manage_products.php" class="btn btn-primary"><i class="fa fa-edit">&nbsp;</i>Manage</a>
			        </div>
		    	</div>
			</div>

		</div>
	</div>

	<?php include_once("./templates/category.php"); ?>

	<?php include_once("./templates/brand.php"); ?>

	<?php include_once("./templates/products.php"); ?>

</body>
</html>