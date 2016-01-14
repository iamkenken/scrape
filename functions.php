<?php
    error_reporting(E_ALL);
    ini_set('max_execution_time', 0);
    set_time_limit(0);
// Defining the basic scraping function
    function scrape_between($data, $start){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, "\n");   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }
    
    //Defining if there is the same starting point
    function scrape_nxt_between($data, $start2){
        $data = substr(stristr($data, $start2), 8); // remove the first string "nserver"
        $data = stristr($data, $start2); // find the 2nd string "nserver"
        $data = substr($data, strlen($start2));  // Stripping $start
        $stop = stripos($data, "\n");   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }
    
     // Defining the basic cURL function
    function curl($url, $proxy) {

        /**$proxies = array(); // Declaring an array to store the proxy list
 
        $proxies[] = '173.234.59.160:80';  
        $proxies[] = '192.161.160.55:80';
        $proxies[] = '69.147.248.111:80';
        $proxies[] = '192.161.160.19:80';
        $proxies[] = '173.232.20.17:80';
        $proxies[] = '173.232.20.151:80';
        $proxies[] = '173.234.59.90:80';
        $proxies[] = '69.147.248.46:80';
        $proxies[] = '192.161.160.127:80';

        if (isset($proxies)) {  // If the $proxies array contains items, then
        $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        }**/

        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
            CURLOPT_HEADER => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
           // CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 0,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 0,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_HTTPPROXYTUNNEL => 1,
            CURLOPT_PROXY => $proxy,
        );
         
        $ch = curl_init();  // Initialising cURL 


        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL 
        return $data;   // Returning the data from the function 
    }
    
?>