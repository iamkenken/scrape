<?php
	include_once('functions.php');
	$domains = $_POST['dom'];
	$proxies = $_POST['proxies'];
	
	if($domains != ''){
	
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=result.csv');
		
		$domarray = explode("\n", str_replace("\r", "", $domains));
		$proxies = explode("\n", str_replace("\r", "", $proxies)); // Declaring an array to store the proxy list
		$proxy = '';
        if (isset($proxies)) {  // If the $proxies array contains items, then
        $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        }
		// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');

			// output the column headings
			fputcsv($output, array('Domains','Nameserver','Nameserver', 'Holder', 'state', 'creation date', 'domain registrar', 'deactivation date', 'date to delete', 'date to release', 'modified, expires'));
		
		
			$domarray = explode("\n", str_replace("\r", "", $domains));
			
			foreach($domarray as $d){
			$scraped_page = curl("https://www.domainnameshop.com/whois?domainname=$d", $proxy);
			
			$ns1 = scrape_between($scraped_page, "nserver:");
			$ns2 = scrape_nxt_between($scraped_page, "nserver:");
			//$remail = scrape_between($scraped_page, "", "");
			$rname = scrape_between($scraped_page, "holder:");
			$state = scrape_between($scraped_page, "state:");
			$creation = scrape_between($scraped_page, "created:");
			$reg = scrape_between($scraped_page, "registrar:");
			$deactivation = scrape_between($scraped_page, "deactivationdate:");
			$delete = scrape_between($scraped_page, "date_to_delete:");
			$release = scrape_between($scraped_page, "date_to_release:");
			$modified = scrape_between($scraped_page, "modified:");
			$expires = scrape_between($scraped_page, "expires:");

			
			if($state == ''){
				$state = "free";
			}
			
			$r = array($d, $ns1, $ns2, $rname, $state, $creation, $reg, $deactivation, $delete, $release, $modified, $expires);
			fputcsv($output, $r);
			}
		
		exit();	
		
	}
?>