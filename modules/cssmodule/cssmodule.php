<?php
/*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_CAN_LOAD_FILES_'))
	exit;
class Cssmodule extends Module
{
	public function __construct()
	{
		$this->name = 'cssmodule';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '1.0';
		$this -> author = 'dh42';
		parent::__construct();
		$this->displayName = $this->l('CSS Editing Module');
		$this->description = $this->l('Easily edit your shops CSS');
	}
	public function install()
	{
		$this->_clearCache('cssmodule.tpl');
		return (parent::install() 
				&& Configuration::updateValue('cssvalue', '')
				&& $this->registerHook('header'));
	}
	public function uninstall()

	{

		$this->_clearCache('cssmodule.tpl');
		return (Configuration::deleteByName('cssvalue') && parent::uninstall());
	}
	public function getContent()
	{
		$html = '';
		if (isset($_POST['submitModule']))
		{	
			Configuration::updateValue('cssvalue', ((isset($_POST['cssvalue']) && $_POST['cssvalue'] != '') ? $_POST['cssvalue'] : ''),  true);
			$html .= '<div class="confirm">'.$this->l('Configuration updated').'</div>';
		}
		$html .= '
		<h2>'.$this->displayName.'</h2>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>	
				<p><label for="cssvalue">'.$this->l('Custom CSS').' :</label>
				<textarea id="cssvalue" name="cssvalue" cols="100" rows="12">'.Configuration::get('cssvalue').'</textarea></p>
				<div class="margin-form">
					<input type="submit" name="submitModule" value="'.$this->l('Save').'" class="button" /></center>
				</div>
			</fieldset>
		</form>
		<h2>CSS Module Reference</h2>
		<fieldset>
		<p>The way that the module works is that it appends the CSS directly to the page. This should override the CSS files and make your changes the most prominent. If a changes does not take effect, you can try to use the modifier !important and that should work.
<br><br>
Also you should use either Google Chrome or Firefox with Firebug to figure out the targeting of the CSS you are trying to change. You can access the CSS reference here, http://www.w3schools.com/cssref/default.asp
<br>
<br><strong>Prestashop 1.5 common classes and ids, you can use these copy these and add CSS to them to change your theme.</strong>
<br>
<br><strong>Logo</strong> - .logo{}

<br><strong>Product Name</strong> - #pb-left-column h1 {}

<br><strong>Main Background</strong> - body{}

<br><strong>Main Footer</strong> - #footer{}

<br><strong>Menu Background</strong> - .sf-menu{}

<br><strong>Menu Hover Color</strong> - .sf-menu li {}

<br><strong>Category Heading</strong> - h1{}</p>
		</fieldset>
		';
		return $html;
	}
public function hookHeader($params)
	{

		if (!$this->isCached('cssmodule.tpl', $this->getCacheId()))
	{	
		global $smarty;
		$smarty->assign(array(
			'cssvalue' => Configuration::get('cssvalue')
		));
			}
return $this->display(__FILE__, 'cssmodule.tpl', $this->getCacheId());
	}
}