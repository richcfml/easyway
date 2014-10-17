<?php

		require_once '../tcpdf/config/lang/eng.php';
 		require_once '../tcpdf/tcpdf.php';
		
		$firstname = $_GET['fn'];
		$lastname = $_GET['ln'];
		$restphone = $_GET['restph'];
		$restfax = $_GET['resfax'];
		$restname = $_GET['restname'];
		$date = date('Y-m-d');
		 
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(8.5, 11), true, 'UTF-8', false);
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Easywayordering');
		$pdf->SetTitle('Online Order');
		$pdf->SetSubject('Online Order Fax ');
		$pdf->SetKeywords('Easywayordering, Order, Online, fax');
		
		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		 
		
		// ---------------------------------------------------------
		// set font
		//$pdf->SetFont('Arial', '', 8);
		
		//$pdf->SetPrintHeader(false);
		//$pdf->SetPrintFooter(false);
		// add a page
		$pdf->AddPage();
		
		
		$Template=file_get_contents("../includes/pdftemplate/coverletter1.html");
		
		$Template =  str_replace("[DATE]",$date,$Template);
		$Template =  str_replace("[CLIENTFIRSTNAME]",$firstname,$Template);
		$Template =	str_replace("[CLIENTLASTNAME]",$lastname,$Template);
		$Template =	str_replace("[RESTURENTPHONE]",$restphone,$Template);
		$Template =	str_replace("[RESTURENTFAX]",$restfax,$Template);
		$Template =	str_replace("[RESTURENTNAME]",$restname,$Template);
		/*if($pdfHeaderImage!="")
			$pdfHeaderImage= '<img  src="images/resturant_headers/'.$pdfHeaderImage .'"  height="2in;"  width="10in"/>';*/
			
		//$Template =	str_replace("[HEADER_IMAGE]",$pdfHeaderImage,$Template);
		// output the HTML content
		$pdf->writeHTML($Template, true,  false, true, false, '');		
		//Close and output PDF document
		$pdf->Output();

?>
 