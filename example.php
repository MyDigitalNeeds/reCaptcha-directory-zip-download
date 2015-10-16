<?php

/* Function to get webpage... */
function get_web_page( $url ) {
    $ch = curl_init( $url );
    curl_setopt_array( $ch, array(
        CURLOPT_RETURNTRANSFER => true,     // Return web page
        CURLOPT_HEADER         => false,    // Don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // Follow redirects
        CURLOPT_ENCODING       => "",       // Handle all encodings
        CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'], // Pass along the User Agent string
        CURLOPT_AUTOREFERER    => true,     // Set referer on redirect
        CURLOPT_SSL_VERIFYPEER => true    	// Validate SSL Certificate
    ) );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $content;
}

$secret = "recaptcha_key_goes_here";
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

if ( !isset($_REQUEST['g-recaptcha-response']) ) {
echo '
<html>
	<head>
		<title>Download copy of our CDN</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//www.google.com/recaptcha/api.js"></script>
	</head>
	<body>
		<p>Websites pulling JavaScript and stylesheet resources from our CDN are benefiting from Commnetivity\'s use of <a href="https://www.cloudflare.com/railgun">Railgun by CloudFlare</a>. Download a fresh copy of our CDN: </p>
		<form id="download" action="example.php" method="get"><input type="submit" value="Download" />
			<div class="g-recaptcha" data-sitekey="recaptcha_key_goes_here"></div>
		</form>
	</body>
</html>';
exit;

}

$response_string = $_REQUEST['g-recaptcha-response'];

$json = get_web_page("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response_string."&remoteip=".$ip);

$json = json_decode($json, true);

if($json['success'] != true) {
	echo "Sorry. Cannot download unless we know you are a human. Pleast go back and try again.";
	exit;
}

// Adding files to a .zip file, no zip file exists it creates a new ZIP file

// increase script timeout value
ini_set('max_execution_time', 5000);

// create object
$zip = new ZipArchive();

/* Garbage cleanup... */
$old = glob("./libs-*.zip");
foreach($old as $to_delete) {
	unlink($to_delete);
}

$filename = 'libs-'.time().'.zip';

// open archive 
if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
    die ("Could not open archive");
}

// initialize an iterator
// pass it the directory to be processed
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("libs/"));

// iterate over the directory
// add each file found to the archive
foreach ($iterator as $key=>$value) {
    $zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");
}

// close and save archive
$zip->close();

if (file_exists($filename)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
    exit;
}

?>