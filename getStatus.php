<?
	$url=$_GET['url'];
	$headers = get_headers($url);
	echo substr($headers[0], 9, 3);
?>
