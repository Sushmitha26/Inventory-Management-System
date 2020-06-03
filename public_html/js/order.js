$(document).ready(function(){
	var DOMAIN = "http://localhost/inventory/public_html/";

	addNewRow();
	$("#add").click(function() {
		addNewRow();
	})


	function addNewRow() {
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			data : {getNewOrderItem:1},
			success : function(data) {
				//alert(data);
				$("#invoice_item").append(data);  //to add new data with existing data
				var n = 0;
				$(".number").each(function() {//each is jquery method, it specifies a function to run for each matched element.here a class
					$(this).html(++n);
				})   
			}
		})
	}

	$("#remove").click(function() {
		$("#invoice_item").children("tr:last").remove();  //among many children of invoice_item(body of table),remove last child i.e last row
		calculate(0,0);
	})

	//whenever the item name is changed,the corresponding quantity,price etc has to be shown
	$("#invoice_item").delegate(".pid","change",function() {
		//this refers to current class clicking on, here .pid
		var pid = $(this).val();
		//alert(pid);
		var tr = $(this).parent().parent();//parent of 'this'(.pid) is td,parent of td is tr,so here we are selecting a particular row which the user selects. 
		$(".overlay").show();
		$.ajax({
			url : DOMAIN+"/includes/process.php",
			method : "POST",
			dataType : "json",
			data : {getPriceAndQty:1,id:pid},
			success : function(data) {  //data is in json format ex-{"pid":4,"cid":3,"product_price":7000}
				//alert(data);
				tr.find(".tqty").val(data["product_stock"]); //find the class .tqty in the current tr,make its value to product_stock.
				tr.find(".qty").val(1); //this is he qty user enters,initially 1
				tr.find(".price").val(data["product_price"]);
				tr.find(".pro_name").val(data["product_name"]);
				tr.find(".amt").html(tr.find(".qty").val() * tr.find(".price").val());  //span tag is not input,it's html attribute
				calculate(0,0);
			}
		})
	})


	$("#invoice_item").delegate(".qty","keyup",function() { //whenever user presses any key over quantity input,run this function
		var qty = $(this);
		var tr = $(this).parent().parent();  //i.e. whole row data is stored in here
		//alert(tr.find(".tqty").val()); //alerts total quantity
		if(isNaN(qty.val())) {
			alert("Please enter a valid quantity");
			qty.val(1);  //reset qty to 1
		}
		else {
			if((qty.val() - 0) > (tr.find(".tqty").val() - 0)) {  //sometimes jquery treata qty.val() and
			// other one as a string,so subtract with an integer or multiply with 1 to make sure it is integer
				alert("Sorry! This much of quantity is not available");
				qty.val(1);
			}else {
				tr.find(".amt").html(qty.val() * tr.find(".price").val());
				calculate(0,0);
			}
		}
	})


	function calculate(dis,paid) {
		var sub_total = 0;
		var gst = 0;
		var net_total = 0;
		var discount = dis;
		var paid_amt = paid;
		var due = 0;

		$(".amt").each(function() {
			sub_total = sub_total +($(this).html() * 1);//amt class is in span tag,so it is html attribute
		})

		gst = 0.18 * sub_total;
		net_total =gst + sub_total;
		net_total = net_total - discount;
		due = net_total - paid_amt;

		$("#sub_total").val(sub_total);
		$("#gst").val(gst);
		$("#discount").val(discount);
		$("#net_total").val(net_total);
		$("#due").val(due);
		
	}

	$("#discount").keyup(function() {
		var discount = $(this).val();
		calculate(discount,0);
	})

	$("#paid").keyup(function() {
		var paid = $(this).val();
		var discount = $("#discount").val();
		calculate(discount,paid);
	})

	//--------------Order Accepting--------------

	$("#order_form").click(function() {

		var invoice = $("#get_order_data").serialize();
		//alert(invoice); whole form data is stored here
		if ($("#cust_name").val() === "") {
			alert("Please enter customer name");
		}else if($("#paid").val() === ""){
			alert("Please enter paid amount");
		}else {
			$.ajax({
				url : DOMAIN+"/includes/process.php",
				method : "POST",
				data : $("#get_order_data").serialize(),
				success : function(data){
					if (data < 0) {
						alert(data);
					}else {
						$("#get_order_data").trigger("reset");  //resets the form
						//alert(data);
						if (confirm("Do u want to print invoice ?")) {
							window.location.href = DOMAIN+"/includes/invoice_bill.php?invoice_no="+data+"&"+invoice;
						}
					}
				}
			});
		}
	});

});