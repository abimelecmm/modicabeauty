<?php

if (!defined('_PS_VERSION_'))
	exit;

$shops = Shop::getShops();
$sql = array();
$hooks = array("hook_home_01", "hook_home_02", "narrow_top", "narrow_middle", "narrow_bottom");
$ordrs = array(15, 13, 9, 8, 8);

foreach ($shops as $key => $shop) {
	foreach ($hooks as $k => $hook) {
		$sql[] = 'INSERT INTO `'.$this->hdb.'` (`id_shop`, `hook`, `module`, `ordr`, `value`) VALUES ('.$shop["id_shop"].', "'.$hook.'", "pk_instafeed", '.$ordrs[$k].', 0);';
	}
}

$sql[] = "UPDATE `".$this->hdb."` SET `module` = 'pk_awshowcaseslider' WHERE `module` = 'pk_awShowcaseSlider';";

foreach ($sql as $s) {
	if (!Db::getInstance()->Execute($s)) 
		return false;
}
return true;

