<?php

if (!defined('_PS_VERSION_'))
	exit;

$shops = Shop::getShops();
$sql = array();

foreach ($shops as $key => $shop)
		$sql[] = 'INSERT INTO `'.$this->hdb.'` (`id_shop`, `hook`, `module`, `ordr`, `value`) VALUES ('.$shop["id_shop"].', "top_slider", "pk_fullpageslider", 2, 0);';

foreach ($sql as $s) {
	if (!Db::getInstance()->Execute($s)) 
		return false;
}
return true;

