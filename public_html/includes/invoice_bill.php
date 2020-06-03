<?php
session_start();  //create a session here to call the invoices unique
include_once("../fpdf/fpdf.php");

if($_GET["order_date"] && $_GET["invoice_no"]) {
	//echo "done";
	$pdf = new FPDF();
	$pdf->AddPage();

	$pdf->SetFont("Arial","B",16);
	$pdf->Cell(190,10,"Inventory System",0,1,"C");
	$pdf->setFont("Arial",null,12);

	$pdf->Cell(50,10,"Date",0,0);
	$pdf->Cell(50,10,": ".$_GET["order_date"],0,1);
	$pdf->Cell(50,10,"Customer Name",0,0);
	$pdf->Cell(50,10,": ".$_GET["cust_name"],0,1);

	$pdf->Cell(50,10,"",0,1); //to create space
 	//table creation
	$pdf->Cell(10,10,"#",1,0,"C");
	$pdf->Cell(70,10,"Product Name",1,0,"C");
	$pdf->Cell(30,10,"Quantity",1,0,"C");
	$pdf->Cell(40,10,"Price",1,0,"C");
	$pdf->Cell(40,10,"Total (Rs)",1,1,"C");

	for ($i=0; $i < count($_GET["pid"]) ; $i++) { 
		$pdf->Cell(10,10, ($i+1) ,1,0,"C");
		$pdf->Cell(70,10, $_GET["pro_name"][$i],1,0,"C");
		$pdf->Cell(30,10, $_GET["qty"][$i],1,0,"C");
		$pdf->Cell(40,10, $_GET["price"][$i],1,0,"C");
		$pdf->Cell(40,10, ($_GET["qty"][$i] * $_GET["price"][$i]) ,1,1,"C");
	}

	$pdf->Cell(50,10,"",0,1);

	$pdf->Cell(50,10,"Sub Total",0,0);
	$pdf->Cell(50,10,": ".$_GET["sub_total"],0,1);
	$pdf->Cell(50,10,"Gst Tax",0,0);
	$pdf->Cell(50,10,": ".$_GET["gst"],0,1);
	$pdf->Cell(50,10,"Discount",0,0);
	$pdf->Cell(50,10,": ".$_GET["discount"],0,1);
	$pdf->Cell(50,10,"Net Total",0,0);
	$pdf->Cell(50,10,": ".$_GET["net_total"],0,1);
	$pdf->Cell(50,10,"Paid",0,0);
	$pdf->Cell(50,10,": ".$_GET["paid"],0,1);
	$pdf->Cell(50,10,"Due Amount",0,0);
	$pdf->Cell(50,10,": ".$_GET["due"],0,1);
	$pdf->Cell(50,10,"Payment Type",0,0);
	$pdf->Cell(50,10,": ".$_GET["payment_type"],0,1);

	$pdf->Ln();
	$pdf->Cell(180,10,"Signature",0,0,"R");

	$pdf->Output("../PDF_INVOICE/PDF_INVOICE_".$_GET["invoice_no"].".pdf","F");//save locally to my server

	$pdf->Output();
}

/*FPDF is a PHP class which allows to generate PDF files with pure PHP, that is to say without using the PDFlib library. F from FPDF stands for Free: you may use it for any kind of usage and modify it to suit your needs.

$pdf = new FPDF();//After including the library file,the class FPDf is present,so we create FPDF object.
	//The constructor is used here with default values: pages are in A4 portrait and the unit of measure mm 
	//lyk this-new FPDF('P','mm','A4'); but we can change the parameters

	//There's no page at the moment, so we have to add one with AddPage().
	$pdf->AddPage();

SetFont(string family, string style, float size)

Cell(float w, float h, string txt, mixed border, int ln, string align, boolean fill, mixed link)

w-Cell width. If 0, the cell extends up to the right margin.
h-Cell height. Default value: 0.
border- 0: no border
		1: frame
		L: left
		T: top
		R: right
		B: bottom
ln-Indicates where the current position should go after the call. Possible values are:
	0: to the right
	1: to the beginning of the next line
	2: below
align-Allows to center or align the text. Possible values are:
	L or empty string: left align (default value)
	C: center
	R: right align
fill-Indicates if the cell background must be painted (true) or transparent (false).
link-URL or identifier returned by AddLink().

Output-string Output(string dest, string name, boolean isUTF8)

dest-Destination where to send the document. It can be one of the following:
	I: send the file inline to the browser. The PDF viewer is used if available.(default)
	D: send to the browser and force a file download with the name given by name.
	F: save to a local file with the name given by name (may include a path).-permanent
	S: return the document as a string.
name-The name of the file. It is ignored in case of destination S.
	The default value is doc.pdf.
Indicates if name is encoded in ISO-8859-1 (false) or UTF-8 (true). Only used for destinations I and D.
The default value is false.*/
?>