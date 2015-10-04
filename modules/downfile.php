<?php
/* this function downloads an attachment file for a product. It is called from product-edit.php */
	$filename = $_GET["filename"];
	$filecode = $_GET["filecode"];
	$download_dir = $_GET["download_dir"];
	$mime = $_GET["mime"];	
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: '.$mime);
		header('Content-Length: '.filesize($download_dir.$filecode));
		header('Content-Disposition: attachment; filename="'.utf8_decode($filename).'"');
		readfile($download_dir.$filecode);