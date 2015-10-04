<?php

$username = "demo@demo.com";
$password = "opensecret"; /* please change this from the default "opensecret" */
$md5hashed = false; /* if you don't want your password in the source code you can encrypt it with the md5.php tool. In that case you should change $md5hashed from "false" to "true" */

/* when ipaddresses is empty everyone can access your script. You are strongly advised to enter here your ip address(es) so that access becomes restricted to them. */
$ipadresses = array(); // Example: $ipadresses = array("11.12.13.150","15.18.19.*","::1"); Note that "::1" is the IPv6 variation on "127.0.0.1" and used for localhost.
$usecookies = false;  /* make true to override default use of sessions */
$autosort = true; 	/* in product sort: should autosort by default be enabled? */

/* you can set here the default fields you will see for product_edit.php */
$default_product_fields = array("name","VAT","price", "quantity", "active","category", "ean", "description", "shortdescription", "image");
/* other options for product_edit: priceVAT,reference,linkrewrite,metatitle,metakeywords,metadescription,wholesaleprice,manufacturer,
   onsale,onlineonly,date_upd,minimalquantity,shipweight,shipheight,shipwidth,shipdepth,aShipCost,attachmnts,tags,carrier,available,
   accessories,combinations,discount,supplier */

/* The next settings determine where the scripts look for images. You should only change this when you have no pictures */
$img_extensions = array('-small_default.jpg','-small.jpg','-small_dm.jpg'); 
