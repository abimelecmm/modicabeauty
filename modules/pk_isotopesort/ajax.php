<?php
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
include(dirname(__FILE__).'/pk_isotopesort.php');

$isotopeSort = new pk_isotopesort();

if (Tools::getValue('type')) {
	$isotopeSort->ajaxCall(Tools::getValue('type'));
}
if (Tools::getValue('cID')) {
	$isotopeSort->getProductsFromCategory(Tools::getValue('cID'));
}
if (Tools::getValue('catID')) {
	$isotopeSort->getCatList(Tools::getValue('catID'));
}
if (Tools::getValue('rem_catID')) {
	$isotopeSort->remCatFromList(Tools::getValue('rem_catID'));
}
if (Tools::getValue('pID')) {
	$isotopeSort->saveData(Tools::getValue('pID'));
}
if (Tools::getValue('rem_pID')) {
	$isotopeSort->removeData(Tools::getValue('rem_pID'));
}
?>