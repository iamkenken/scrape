<!DOCTYPE html>
<html lang="">
<head>
<title>Scraping</title>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" />
</head>
<body>
<div style="width: 100%;">
	<form id="whoisform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

	<div style="display: inline-block; vertical-align: top;">
	<label style="font-size: 14px; display: block;">Domains</label>
	<textarea name="domains" class="domain" Placeholder="Domains here..." style="width: 300px; height: 200px"></textarea>
	<input type="submit" id="whois" name="whois" value="Get WhoIs Properties" style="display: block" />
	</div>

	<div style="display: inline-block; margin: 0 20px; vertical-align: top;">
	<label style="font-size: 14px; display: block;">Proxy</label>
	<textarea name="proxies" class="proxies" Placeholder="Proxy here..." style="width: 300px; height: 200px; text-align: left">173.234.59.160:80
192.161.160.55:80
69.147.248.111:80
192.161.160.19:80
173.232.20.17:80
173.232.20.151:80
173.234.59.90:80
69.147.248.46:80
192.161.160.127:80
192.161.160.247:80</textarea>
		<div style="display: block;">
		<input type="checkbox" class="disproxy" name="disproxy" style="display:inline;" /> Disable Proxy
		</div>
	</div>
	</form>
</div>
<?php
error_reporting(E_ALL);
	include_once('functions.php');
	
	if(isset($_REQUEST['whois'])){
		$domains = $_POST['domains'];
		$proxies = $_POST['proxies'];
		//echo $proxies;
		if($domains != ''){
			
		$domarray = explode("\n", str_replace("\r", "", $domains));
		$proxies = explode("\n", str_replace("\r", "", $proxies)); // Declaring an array to store the proxy list

		$proxy = '';
        if (isset($proxies)) {  // If the $proxies array contains items, then
        $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        }

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
		if($state == ''){ $state = "free"; }

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
		
		?>
		<form action="export.php" method="POST">
		<input type="hidden" value="<?php echo $domains; ?>" name="dom" />
		<input type="hidden" value="<?php echo $proxies; ?>" name="prox" />
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
    var dProxy = $('.disproxy');
  	dProxy.click(function(){
  		if(dProxy.prop('checked')){
  			$('.proxies').prop('readonly', true);
  			$('.proxies').css('opacity', '0.5');
  		}else{
  			$('.proxies').prop('readonly', false);
  			$('.proxies').css('opacity', '1');
  		}
  	});

  	$('#whoisform').submit(function(){
  		if(dProxy.prop('checked')){
  			$('.proxies').val('');
  		}
  	});

});
</script>
</body>
</html>