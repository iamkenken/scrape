<?php
	include_once('functions.php');

	$datas = $_POST['whoisarray'];
	$datas = unserialize(base64_decode($datas));
	// output headers so that the file is downloaded rather than displayed
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=result.csv');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, array('Domains','Nameserver','Nameserver', 'Holder', 'state', 'creation date', 'domain registrar', 'deactivation date', 'date to delete', 'date to release', 'modified, expires'));
	
	foreach ($datas as $data) {

	fputcsv($output, $data);
	}
	exit();	
	

?>