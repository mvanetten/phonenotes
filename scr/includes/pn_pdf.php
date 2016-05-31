<?php

use Dompdf\Dompdf;

/**
 * phone_notes_download_pdf
 * Send a pdf file to the browser 
 *
 *
 * @param void
 * 
 * @return headers
 */ 
function phone_notes_download_pdf()
{
	//Generate PDF
	$pdfoutput = phone_notes_dompdf_creator(phone_notes_create_html(phone_notes_get_notes()));
	
	//Create Temp File and store pdfoutput
	date_default_timezone_set("Europe/Amsterdam");
	$now = date("Y-m-d His");
	$tempfile = sys_get_temp_dir()."/Phone Notes Overview - ".$now.".pdf";
	file_put_contents($tempfile , $pdfoutput);
 
	
	if (file_exists($tempfile)) 
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="'.basename($tempfile).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($tempfile);
		unlink($tempfile);
		exit;
	}
	
	unlink($tempfile);

}

/**
 * phone_notes_dompdf_creator
 * Creates a pdf document
 *
 * @param HTML formated string
 * 
 * @return binary data
 */ 
function phone_notes_dompdf_creator($html)
{
	//Third Party to Render PDF Files
	
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper("A4", "landscape");
	$dompdf->render();
	$output = $dompdf->output();
	return $output;
}

/**
 * phone_notes_send_pdf
 * Sends a PDF document
 *
 * @param void
 * 
 * @return bool
 */ 
function phone_notes_send_pdf()
{
	//Email headers
	$current_user = wp_get_current_user();
	$to = $current_user->user_email;
	$subject = "Phone Notes Overview";
	$message = "A generated list of your phone notes";
	$headers = "";
	
	//Generate PDF
	$pdfoutput = phone_notes_dompdf_creator(phone_notes_create_html(phone_notes_get_notes()));

	//Create Temp File and store pdfoutput
	date_default_timezone_set("Europe/Amsterdam");
	$now = date("Y-m-d His");
	$tempfile = sys_get_temp_dir()."/Phone Notes Overview - ".$now.".pdf";
	file_put_contents($tempfile , $pdfoutput);
	
	//Send email
	if (wp_mail( $to, $subject, $message, $headers, $tempfile))
	{
		//Delete temp file
		unlink($tempfile);
		return true;
	};
	
	//Delete temp file
	unlink($tempfile);
	return false;
}


/**
 * phone_notes_create_html
 * Create a HTML table
 *
 * @param a single row from table
 * 
 * @return string HTML
 */ 
function phone_notes_create_html(Array $notes)
{
	$html = "";
	$html .= "<h1>Phone Notes List</h1>";
	$html .= "<table style='width:100%'>";
	$html .= "	<tr>";
	$html .= "		<td width='15%'>Date</td>";
	$html .= "		<td width='25%'>Name</td> ";
	$html .= "		<td width='20%'>Number</td>";
	$html .= "		<td width='40%'>Note</td>";
	$html .= "	</tr>";
		foreach ($notes as $note)
		{
			$html .= "	<tr>";
			$html .= "		<td valign='top'>".$note->getDateTime()."</td>";
			$html .= "		<td valign='top'>".$note->getName()."</td>";
			$html .= "		<td valign='top'>".$note->getNumber()."</td>";
			$html .= "		<td valign='top'>".$note->getNotes()."</td>";
			$html .= "	</tr>";
		}
	$html .= "</table>";
	return $html;
}

?>