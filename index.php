<!DOCTYPE html>
<html lang="">
<head>
<title>Scraping</title>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
<textarea name="domains" class="domain" Placeholder="Domains here..." style="width: 300px; height: 200px"></textarea>
<br />
<input type="submit" id="whois" name="whois" value="Get WhoIs Properties" />
</form>

<?php
error_reporting(E_ALL);
	include_once('functions.php');
	
	if(isset($_REQUEST['whois'])){
	$domains = $_POST['domains'];
		if($domains != ''){
			
		$domarray = explode("\n", str_replace("\r", "", $domains));

		
		echo '<table id="domain-table" class="display" cellspacing="0" width="100%">';
		echo '<thead><tr>';
		echo '<th>Domain </th>';
		echo '<th>Nameserver</th>';
		echo '<th>Nameserver</th>';
		echo '<th>Holder</th>';
		echo '<th>State</th>';
		echo '<th>Creation Date</th>';
		echo '<th>Domain registrar</th>';
		echo '<th>Deactivation date</th>';
		echo '<th>Date to delete</th>';
		echo '<th>Date to release</th>';
		echo '<th>Modified</th>';
		echo '<th>Expires</th>';
		echo '</tr></thead>';
		foreach($domarray as $d){

		
		$scraped_page = curl("https://www.domainnameshop.com/whois?domainname=$d");
		
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

		echo '<tr>';
		echo '<td>'.$d.'</td>';
		echo '<td>'.$ns1.'</td>';
		echo '<td>'.$ns2.'</td>';
		echo '<td>'.$rname.'</td>';
		echo '<td>'.$state.'</td>';
		echo '<td>'.$creation.'</td>';
		echo '<td>'.$reg.'</td>';
		echo '<td>'.$deactivation.'</td>';
		echo '<td>'.$delete.'</td>';
		echo '<td>'.$release.'</td>';
		echo '<td>'.$modified.'</td>';
		echo '<td>'.$expires.'</td>';
		echo '</tr>';
		}
		echo '</table>';
		
		//echo '<a href="export.php?domains='.$domains.'" style="margin: 0px 5px; background: #eaeaea; border: 1px solid #cccccc; padding: 5px;">Export CSV</a>';
		
		?>
		<form action="export.php" method="POST">
		<input type="hidden" value="<?php echo $domains; ?>" name="dom" />
		<input type="submit" name="export" value="Export CSV" />
		</form>
<?php
		}
	}
	
?>
<script type="text/javascript" src="//code.jquery.com/jquery-git2.min.js" language="javascript"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js" language="javascript"></script>
<script>
$(document).ready(function(){
    $('#domain-table').DataTable({
    "bPaginate": false
  	});;
});
</script>
</body>
</html>