<?php
/*
* 2007-2015 mitsos1os
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize the module for your
* needs please refer to
*  @author mitsos1os
*  @copyright  2014-2015
*/
if (!defined('_PS_VERSION_'))
    exit;
function upgrade_module_2_5($object)
{
	//first check if  column is already there
	$checkSQL = 'SHOW COLUMNS FROM `'._DB_PREFIX_.'extraproducttab_lang`;';
	$columnNames = Db::getInstance()->executeS($checkSQL);
	if (count($columnNames)<4){//if there are 4 columns, defaultContent is already added
		return Db::getInstance()->execute(
           'ALTER TABLE `'._DB_PREFIX_.'extraproducttab_lang` ADD COLUMN `defaultContent` TEXT;'
    	);
	}
	else{
		return true;
	}
    
}