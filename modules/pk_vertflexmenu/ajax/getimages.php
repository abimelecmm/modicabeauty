<?php
require_once(dirname(__FILE__).'/../../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../../init.php');
include(dirname(__FILE__).'/../pk_vertflexmenu.php');
$flex = new pk_vertflexmenu();

echo $flex->getProductCover(Tools::getValue('pID'));


?>