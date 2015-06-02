<?php
/*
	Module Name: pk_customlinks
	Version: 2.2
	Author: Marek Mnishek
	Author URI: http://promokit.eu
	Copyright (C) 2013 promokit.eu 
*/

if (!defined('_PS_VERSION_'))
	exit;

class pk_customlinks extends Module
{
	/* @var boolean error */
	protected $error = false;
	
	public function __construct()
	{
		$this->name = 'pk_customlinks';
		$this->tab = 'front_office_features';
		$this->version = '2.2.2';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;

	 	parent::__construct();

		$this->displayName = $this->l('Custom Links');
		$this->description = $this->l('Adds a block with custom links.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete all your links ?');
	}
	
	public function install()
	{
		if (!parent::install() ||
			!$this->registerHook('displayNav') || !$this->registerHook('header') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'pk_customlink (
			`id_pk_customlink` int(2) NOT NULL AUTO_INCREMENT, 
			`url` varchar(255) NOT NULL,
			`new_window` TINYINT(1) NOT NULL,
			PRIMARY KEY(`id_pk_customlink`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'pk_customlink_shop (
			`id_pk_customlink` int(2) NOT NULL AUTO_INCREMENT, 
			`id_shop` int(2) NOT NULL,
			PRIMARY KEY(`id_pk_customlink`, `id_shop`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Db::getInstance()->execute('
			CREATE TABLE '._DB_PREFIX_.'pk_customlink_lang (
			`id_pk_customlink` int(2) NOT NULL,
			`id_lang` int(2) NOT NULL,
			`text` varchar(64) NOT NULL,
			PRIMARY KEY(`id_pk_customlink`, `id_lang`))
			ENGINE='._MYSQL_ENGINE_.' default CHARSET=utf8') ||
			!Configuration::updateValue('PS_CUSTOMLINK_TITLE', array('1' => 'Block link', '2' => 'Bloc lien')) ||
			!Configuration::updateValue('LINK_MYACC', 1) ||
			!Configuration::updateValue('LINK_MYWSH', 1) ||
			!Configuration::updateValue('LINK_MYFAV', 0) ||
			!Configuration::updateValue('LINK_MYWTL', 1) ||
			!Configuration::updateValue('LINK_MYCMP', 1))
			return false;
		return true;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'pk_customlink') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'pk_customlink_lang') ||
			!Db::getInstance()->execute('DROP TABLE '._DB_PREFIX_.'pk_customlink_shop') ||
			!Configuration::deleteByName('PS_CUSTOMLINK_TITLE') ||
			!Configuration::deleteByName('PS_CUSTOMLINK_URL') ||
			!Configuration::deleteByName('LINK_MYACC') ||
			!Configuration::deleteByName('LINK_MYWSH') ||
			!Configuration::deleteByName('LINK_MYFAV') ||
			!Configuration::deleteByName('LINK_MYWTL') ||
			!Configuration::deleteByName('LINK_MYCMP'))
			return false;
		return true;
	}
	
	public function hookDisplayNav($params)
	{		
		//if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name))) {

			$sett = array();
			$sett["myacc"] = Configuration::get('LINK_MYACC');
			$sett["mywsh"] = Configuration::get('LINK_MYWSH');
			$sett["myfav"] = Configuration::get('LINK_MYFAV');
			$sett["mywtl"] = Configuration::get('LINK_MYWTL');
			$sett["mycmp"] = Configuration::get('LINK_MYCMP');

			$links = $this->getLinks();
			$module_link = $favorite_module = $favoriteProducts = $pk_wishlist = "";
			if (Module::isInstalled('blockwishlist')) {
				$module_link = $this->context->link->getModuleLink('blockwishlist', 'mywishlist');
				$pk_wishlist = $this->pk_getWishList($params);
			}
			if (Module::isInstalled('favoriteproducts')) {
				require_once(_PS_MODULE_DIR_.'/favoriteproducts/FavoriteProduct.php');
				$favorite_module = 1;
				if (Context::getContext()->customer->id) {
					$favoriteProducts = FavoriteProduct::getFavoriteProducts((int)Context::getContext()->customer->id, (int)Context::getContext()->language->id);				
				}
			}	
			// add prices	
			if ($favoriteProducts) {
				foreach ($favoriteProducts as $key => $product) {
					$currency = new Currency($params['cookie']->id_currency);
					$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
					$favoriteProducts[$key]["price"] = Tools::displayPrice(Product::getPriceStatic($product["id_product"], $usetax), $currency);
				}
			}
			if ($pk_wishlist["wishlist_products"]) {
				foreach ($pk_wishlist["wishlist_products"] as $key => $product) {
					$currency = new Currency($params['cookie']->id_currency);
					$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
					$pk_wishlist["wishlist_products"][$key]["price"] = Tools::displayPrice(Product::getPriceStatic($product["id_product"], $usetax), $currency);
				}
			}
			// --
			
			$this->smarty->assign(array(
				'customlinks_links' => $links,
				'title' => Configuration::get('PS_CUSTOMLINK_TITLE', $this->context->language->id),
				'url' => Configuration::get('PS_CUSTOMLINK_URL'),
				'lang' => 'text_'.$this->context->language->id,
				'wishlist_link' => $module_link,
				'favorite_module' => $favorite_module,
				'watchlist' => $this->getWatchList($params),
				'pk_favoriteProducts' => $favoriteProducts,
				'pk_wishlist' => $pk_wishlist,
				'pk_compare' => $this->pk_getCompare(),
				'pk_voucherAllowed' => CartRule::isFeatureActive(),
				'pk_returnAllowed' => (int)(Configuration::get('PS_ORDER_RETURN')),
				'main_links' => $sett
			));
		//}
		//return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
		return $this->display(__FILE__, $this->name.'.tpl');

	}
	
	public function hookHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'pk_customlinks.css', 'all');
		$this->context->controller->addJS(($this->_path).'js/script.js');
	}

	public function getLinks()
	{
		$result = array();
		// Get id and url

		$sql = 'SELECT b.`id_pk_customlink`, b.`url`, b.`new_window`
				FROM `'._DB_PREFIX_.'pk_customlink` b';
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_ALL)
			$sql .= ' JOIN `'._DB_PREFIX_.'pk_customlink_shop` bs ON b.`id_pk_customlink` = bs.`id_pk_customlink` AND bs.`id_shop` IN ('.implode(', ', Shop::getContextListShopID()).') ';
		$sql .= (int)Configuration::get('PS_BLOCKLINK_ORDERWAY') == 1 ? ' ORDER BY `id_pk_customlink` DESC' : '';

		if (!$links = Db::getInstance()->executeS($sql))
			return false;
		$i = 0;
		foreach ($links as $link)
		{
			$result[$i]['id'] = $link['id_pk_customlink'];
			$result[$i]['url'] = $link['url'];
			$result[$i]['newWindow'] = $link['new_window'];
			// Get multilingual text
			if (!$texts = Db::getInstance()->executeS('SELECT `id_lang`, `text` 
																	FROM '._DB_PREFIX_.'pk_customlink_lang 
																	WHERE `id_pk_customlink`='.(int)$link['id_pk_customlink']))
				return false;
			foreach ($texts as $text)
				$result[$i]['text_'.$text['id_lang']] = $text['text'];
			$i++;
		}
		return $result;
	}
	
	public function addLink()
	{
		if (!($languages = Language::getLanguages()))
			 return false;
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');

		if ($id_link = Tools::getValue('id_link'))
		{
			if (!Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'pk_customlink SET `url` = \''.pSQL($_POST['url']).'\', `new_window` = '.(isset($_POST['newWindow']) ? 1 : 0).' WHERE `id_pk_customlink` = '.(int)$id_link))
				return false;
			if (!Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'pk_customlink_lang WHERE `id_pk_customlink` = '.(int)$id_link))
				return false;
				
			foreach ($languages as $language)
				if (!empty($_POST['text_'.$language['id_lang']]))
		 	 	{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'pk_customlink_lang VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$language['id_lang']]).'\')'))
						return false;
		 	 	}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'pk_customlink_lang VALUES ('.(int)$id_link.', '.$language['id_lang'].', \''.pSQL($_POST['text_'.$id_lang_default]).'\')'))
						return false;
		}
		else
		{
			if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'pk_customlink 
														VALUES (NULL, \''.pSQL($_POST['url']).'\', '.((isset($_POST['newWindow']) && $_POST['newWindow']) == 'on' ? 1 : 0).')') ||
														!$id_link = Db::getInstance()->Insert_ID())
				return false;

			foreach ($languages as $language)
				if (!empty($_POST['text_'.$language['id_lang']]))
				{
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'pk_customlink_lang 
																VALUES ('.(int)$id_link.', '.(int)$language['id_lang'].', \''.pSQL($_POST['text_'.$language['id_lang']]).'\')'))
						return false;
				}
				else
					if (!Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'pk_customlink_lang VALUES ('.(int)$id_link.', '.(int)($language['id_lang']).', \''.pSQL($_POST['text_'.$id_lang_default]).'\')'))
						return false;
		}

		Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'pk_customlink_shop WHERE id_pk_customlink='.(int)$id_link);

		if (!Shop::isFeatureActive())
		{
			Db::getInstance()->insert('pk_customlink_shop', array(
				'id_pk_customlink' => (int)$id_link,
				'id_shop' => (int)Context::getContext()->shop->id,
			));
		}
		else
		{
			$assos_shop = Tools::getValue('checkBoxShopAsso_blocklink');
			if (empty($assos_shop))
				return false;
			foreach ($assos_shop as $id_shop => $row)
				Db::getInstance()->insert('pk_customlink_shop', array(
					'id_pk_customlink' => (int)$id_link,
					'id_shop' => (int)$id_shop,
				));
		}
		return true;
	}

	public function deleteLink()
	{
		return (Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'pk_customlink WHERE `id_pk_customlink` = '.(int)$_GET['id']) &&
					Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'pk_customlink_shop WHERE `id_pk_customlink` = '.(int)$_GET['id']) &&
					Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'pk_customlink_lang WHERE `id_pk_customlink` = '.(int)$_GET['id']));
	}

	public function getContent()
	{
		$this->_html = '
		<script type="text/javascript" src="'.$this->_path.'blocklink.js"></script>';

		// Add a link
		if (isset($_POST['submitLinkAdd']))
     	{
			if (empty($_POST['text_'.Configuration::get('PS_LANG_DEFAULT')]) || empty($_POST['url']))
				$this->_html .= $this->displayError($this->l('You must fill in all fields'));
			elseif (!Validate::isUrl(str_replace('http://', '', $_POST['url'])))
				$this->_html .= $this->displayError($this->l('Bad URL'));
			else
				if ($this->addLink())
	     	  		$this->_html .= $this->displayConfirmation($this->l('The link has been added.'));
				else
					$this->_html .= $this->displayError($this->l('An error occurred during link creation.'));
     	}
		
		// Delete a link
		elseif (Tools::getValue('delete_link') && isset($_GET['id']))
		{

			if (!is_numeric($_GET['id']) || !$this->deleteLink())
			 	$this->_html .= $this->displayError($this->l('An error occurred during link deletion.'));
			else
			 	$this->_html .= $this->displayConfirmation($this->l('The link has been deleted.'));
		}

		if (isset($_POST['submitOrderWay']))
		{
			if (
				Configuration::updateValue('PS_BLOCKLINK_ORDERWAY', (int)(Tools::getValue('orderWay'))) &&
				Configuration::updateValue('LINK_MYACC', (int)(Tools::getValue('link_myacc'))) &&
				Configuration::updateValue('LINK_MYWSH', (int)(Tools::getValue('link_mywsh'))) &&
				Configuration::updateValue('LINK_MYFAV', (int)(Tools::getValue('link_myfav'))) &&
				Configuration::updateValue('LINK_MYWTL', (int)(Tools::getValue('link_mywtl'))) &&
				Configuration::updateValue('LINK_MYCMP', (int)(Tools::getValue('link_mycmp')))
				)
				$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
			else
				$this->_html .= $this->displayError($this->l('An error occurred during settings set-up.'));
		}

		$this->_displayForm();
		$this->_list();

		return $this->_html;
	}
	
	private function _displayForm()
	{
	 	/* Language */
		$id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages(false);
		$divLangName = 'text¤title';
		/* Title */
		$title_url = Configuration::get('PS_CUSTOMLINK_URL');
		if (!Tools::isSubmit('submitLinkAdd'))
		{
			if ($id_link = (int)Tools::getValue('id_link'))
			{
				$res = Db::getInstance()->executeS('
				SELECT *
				FROM '._DB_PREFIX_.'pk_customlink b
				LEFT JOIN '._DB_PREFIX_.'pk_customlink_lang bl ON (b.id_pk_customlink = bl.id_pk_customlink)
				WHERE b.id_pk_customlink='.(int)$id_link);
				if ($res)
					foreach ($res as $row)
					{
						$links['text'][(int)$row['id_lang']] = $row['text'];
						$links['url'] = $row['url'];
						$links['new_window'] = $row['new_window'];
					}
			}
			parent::_clearCache($this->name.'.tpl');
		}
		$this->_html .= '
		<script type="text/javascript">
			id_language = Number('.(int)$id_lang_default.');
		</script>
		<style>
			#languages_text br {display: none}
			.displayed_flag, #languages_text {float:left; vertical-align:top; margin-top:3px}
			#languages_text {display: none}
			.tree-folder label {width:auto}
		</style>
		<fieldset>
			<legend><img src="'.$this->_path.'add.png" alt="" title="" /> '.$this->l('Add a new link').'</legend>
			<form method="post" action="index.php?controller=adminmodules&configure='.Tools::safeOutput(Tools::getValue('configure')).'&token='.Tools::safeOutput(Tools::getValue('token')).'&tab_module='.Tools::safeOutput(Tools::getValue('tab_module')).'&module_name='.Tools::safeOutput(Tools::getValue('module_name')).'">
				<input type="hidden" name="id_link" value="'.(int)Tools::getValue('id_link').'" />
				<label>'.$this->l('Text:').'</label>
				<div class="margin-form">';
			foreach ($languages as $language)
				$this->_html .= '
					<div id="text_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $id_lang_default ? 'block' : 'none').'; float: left;">
						<input type="text" name="text_'.$language['id_lang'].'" id="textInput_'.$language['id_lang'].'" value="'.((isset($links) && isset($links['text'][$language['id_lang']])) ? $links['text'][$language['id_lang']] : '').'" /><sup> *</sup>
					</div>';
			$this->_html .= $this->displayFlags($languages, $id_lang_default, $divLangName, 'text', true);
			$this->_html .= '
					<div class="clear"></div>
				</div>
				<label>'.$this->l('URL:').'</label>
				<div class="margin-form"><input type="text" name="url" id="url" value="'.(isset($links) && isset($links['url']) ? Tools::safeOutput($links['url']) : '').'" /><sup> *</sup></div>
				<label>'.$this->l('Open in a new window:').'</label>
				<div class="margin-form"><input type="checkbox" name="newWindow" id="newWindow" '.((isset($links) && $links['new_window']) ? 'checked="checked"' : '').' /></div>';
				$shops = Shop::getShops(true, null, true);
				if (Shop::isFeatureActive() && count($shops) > 1)
				{
					$helper = new HelperForm();
					$helper->id = (int)Tools::getValue('id_link');
					$helper->table = 'blocklink';
					$helper->identifier = 'id_pk_customlink';
		
					$this->_html .= '<label for="shop_association">'.$this->l('Shop association:').'</label><div id="shop_association" class="margin-form">'.$helper->renderAssoShop().'</div>';
				}
			$this->_html .= '
				<div class="margin-form">
					<input type="submit" class="button" name="submitLinkAdd" value="'.$this->l('Add this link').'" />
				</div>
			</form>
		</fieldset>
		<fieldset class="space">
			<legend><img src="'.$this->_path.'prefs.gif" alt="" title="" /> '.$this->l('Settings').'</legend>
			<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">
				<label>'.$this->l('Order list:').'</label>
				<div class="margin-form">
					<select name="orderWay">
						<option value="0"'.(!Configuration::get('PS_BLOCKLINK_ORDERWAY') ? 'selected="selected"' : '').'>'.$this->l('by most recent links').'</option>
						<option value="1"'.(Configuration::get('PS_BLOCKLINK_ORDERWAY') ? 'selected="selected"' : '').'>'.$this->l('by oldest links').'</option>
					</select>
				</div>
				<label>'.$this->l('"My Account":').'</label>
				<div class="margin-form">
					<input type="radio" name="link_myacc" id="link_myacc_on" value="1" '.(Tools::getValue('link_myacc', Configuration::get('LINK_MYACC')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_myacc_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="link_myacc" id="link_myacc_off" value="0" '.(!Tools::getValue('link_myacc', Configuration::get('LINK_MYACC')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_myacc_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('"My Wishlist":').'</label>
				<div class="margin-form">
					<input type="radio" name="link_mywsh" id="link_mywsh_on" value="1" '.(Tools::getValue('link_mywsh', Configuration::get('LINK_MYWSH')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mywsh_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="link_mywsh" id="link_mywsh_off" value="0" '.(!Tools::getValue('link_mywsh', Configuration::get('LINK_MYWSH')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mywsh_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('"My Favorites":').'</label>
				<div class="margin-form">
					<input type="radio" name="link_myfav" id="link_myfav_on" value="1" '.(Tools::getValue('link_myfav', Configuration::get('LINK_MYFAV')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_myfav_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="link_myfav" id="link_myfav_off" value="0" '.(!Tools::getValue('link_myfav', Configuration::get('LINK_MYFAV')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_myfav_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('"Watch List":').'</label>
				<div class="margin-form">
					<input type="radio" name="link_mywtl" id="link_mywtl_on" value="1" '.(Tools::getValue('link_mywtl', Configuration::get('LINK_MYWTL')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mywtl_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="link_mywtl" id="link_mywtl_off" value="0" '.(!Tools::getValue('link_mywtl', Configuration::get('LINK_MYWTL')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mywtl_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<label>'.$this->l('"Compare":').'</label>
				<div class="margin-form">
					<input type="radio" name="link_mycmp" id="link_mycmp_on" value="1" '.(Tools::getValue('link_mycmp', Configuration::get('LINK_MYCMP')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mycmp_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="link_mycmp" id="link_mycmp_off" value="0" '.(!Tools::getValue('link_mycmp', Configuration::get('LINK_MYCMP')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="link_mycmp_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
				</div>
				<div class="margin-form"><input type="submit" class="button" name="submitOrderWay" value="'.$this->l('Update').'" /></div>
			</form>
		</fieldset>';
	}
	
	private function _list()
	{
		$links = $this->getLinks();
		$languages = Language::getLanguages();
		$token = Tools::safeOutput(Tools::getValue('token'));
		if (!Validate::isCleanHtml($token))
			$token = '';
		if ($links)
	 	{
			$this->_html .= '
			<script type="text/javascript">
				var currentUrl = \''.Tools::safeOutput($_SERVER['REQUEST_URI']).'\';
				var token=\''.$token.'\';
				var links = new Array();';
			foreach ($links as $link)
	 		{
				$this->_html .= 'links['.$link['id'].'] = new Array(\''.addslashes($link['url']).'\', '.$link['newWindow'];
				foreach ($languages as $language)
					if (isset($link['text_'.$language['id_lang']]))
						$this->_html .= ', \''.addslashes($link['text_'.$language['id_lang']]).'\'';
					else
						$this->_html .= ', \'\'';
				$this->_html .= ');';
	 		}
			$this->_html .= '</script>';
	 	}
		$this->_html .= '
		<h3 class="blue space">'.$this->l('Link list').'</h3>
		<table class="table">
			<tr>
				<th>'.$this->l('ID').'</th>
				<th>'.$this->l('Text').'</th>
				<th>'.$this->l('URL').'</th>
				<th>'.$this->l('Actions').'</th>
			</tr>';
			
		if (!$links)
			$this->_html .= '
			<tr>
				<td colspan="3">'.$this->l('There are no links.').'</td>
			</tr>';
		else
			foreach ($links as $link)
				$this->_html .= '
				<tr>
					<td>'.(int)$link['id'].'</td>
					<td>'.Tools::safeOutput($link['text_'.$this->context->language->id]).'</td>
					<td>
						<a href="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'&id_link='.(int)$link['id'].'"><img src="../img/admin/edit.gif" alt="" title="" style="cursor: pointer" /></a>
						<a href="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'&id='.(int)$link['id'].'&delete_link=1"><img src="../img/admin/delete.gif" alt="" title="" style="cursor: pointer" /></a>
					</td>
				</tr>';
		$i = 0;
		$nb = count($languages);
		$idLng = 0;
		while ($i < $nb)
		{
			if ($languages[$i]['id_lang'] == (int)Configuration::get('PS_LANG_DEFAULT'))
				$idLng = $i;
			$i++;
		}
		$this->_html .= '
		</table>
		<input type="hidden" id="languageFirst" value="'.(int)$languages[0]['id_lang'].'" />
		<input type="hidden" id="languageNb" value="'.count($languages).'" />';
	}

	public function getWatchList($params)
	{
		Configuration::updateValue('PRODUCTS_VIEWED_NBR', 4);
		$id_product = (int)Tools::getValue('id_product');
		$productsViewed = (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) ? array_slice(explode(',', $params['cookie']->viewed), 0, Configuration::get('PRODUCTS_VIEWED_NBR')) : array();

		if (count($productsViewed))
		{
			$defaultCover = Language::getIsoById($params['cookie']->id_lang).'-default';

			$productIds = implode(',', $productsViewed);
			$productsImages = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(image_shop.id_image) id_image, p.id_product, p.price, il.legend, product_shop.active, pl.name, pl.description_short, pl.link_rewrite, cl.link_rewrite AS category_rewrite
			FROM '._DB_PREFIX_.'product p
			'.Shop::addSqlAssociation('product', 'p').'
			LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product'.Shop::addSqlRestrictionOnLang('pl').')
			LEFT JOIN '._DB_PREFIX_.'image i ON (i.id_product = p.id_product)'.
			Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
			LEFT JOIN '._DB_PREFIX_.'image_lang il ON (il.id_image = image_shop.id_image)
			LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = product_shop.id_category_default'.Shop::addSqlRestrictionOnLang('cl').')
			WHERE p.id_product IN ('.$productIds.')
			AND pl.id_lang = '.(int)($params['cookie']->id_lang).'
			AND cl.id_lang = '.(int)($params['cookie']->id_lang).'
			GROUP BY product_shop.id_product'
			);

			$productsImagesArray = array();
			foreach ($productsImages as $pi)
				$productsImagesArray[$pi['id_product']] = $pi;

			$productsViewedObj = array();
			$priceDisplay = Product::getTaxCalculationMethod();
			$currency = new Currency($params['cookie']->id_currency);
			$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
			foreach ($productsViewed as $productViewed)
			{				
				$obj = (object)'Product';
				if (!isset($productsImagesArray[$productViewed]) || (!$obj->active = $productsImagesArray[$productViewed]['active']))
					continue;
				else
				{
					$obj->id = (int)($productsImagesArray[$productViewed]['id_product']);
					$obj->id_image = (int)$productsImagesArray[$productViewed]['id_image'];
					$obj->cover = (int)($productsImagesArray[$productViewed]['id_product']).'-'.(int)($productsImagesArray[$productViewed]['id_image']);
					$obj->legend = $productsImagesArray[$productViewed]['legend'];
					$obj->name = $productsImagesArray[$productViewed]['name'];
					$obj->description_short = $productsImagesArray[$productViewed]['description_short'];
					$obj->link_rewrite = $productsImagesArray[$productViewed]['link_rewrite'];
					$obj->category_rewrite = $productsImagesArray[$productViewed]['category_rewrite'];
					$obj->price = Tools::displayPrice(Product::getPriceStatic((int)$obj->id, $usetax), $currency);
					// $obj is not a real product so it cannot be used as argument for getProductLink()
					$obj->product_link = $this->context->link->getProductLink($obj->id, $obj->link_rewrite, $obj->category_rewrite);

					if (!isset($obj->cover) || !$productsImagesArray[$productViewed]['id_image'])
					{
						$obj->cover = $defaultCover;
						$obj->legend = '';
					}
					$productsViewedObj[] = $obj;
				}
			}

			if ($id_product && !in_array($id_product, $productsViewed))
			{
				// Check if the user to the right of access to this product
				$product = new Product((int)$id_product);
				if ($product->checkAccess((int)$this->context->customer->id))
					array_unshift($productsViewed, $id_product);
			}
			$viewed = '';
			foreach ($productsViewed as $id_product_viewed)
				$viewed .= (int)($id_product_viewed).',';
			$params['cookie']->viewed = rtrim($viewed, ',');

			if (!count($productsViewedObj))
				return;

			return $productsViewedObj;
		}
		elseif ($id_product)
			$params['cookie']->viewed = (int)($id_product);
		return;
	}

	public function pk_getWishList($params)
	{
		global $errors;
		
		if ($this->context->customer->isLogged() && (file_exists(_PS_MODULE_DIR_.'blockwishlist/WishList.php')))
		{
			require_once(_PS_MODULE_DIR_.'blockwishlist/WishList.php');
			$wishlists = Wishlist::getByIdCustomer($this->context->customer->id);
			if (empty($this->context->cookie->id_wishlist) === true ||
				WishList::exists($this->context->cookie->id_wishlist, $this->context->customer->id) === false)
			{
				if (!sizeof($wishlists))
					$id_wishlist = false;
				else
				{
					$id_wishlist = (int)($wishlists[0]['id_wishlist']);
					$this->context->cookie->id_wishlist = (int)($id_wishlist);
				}
			}
			else
				$id_wishlist = $this->context->cookie->id_wishlist;			

			$pk_wishlist['id_wishlist'] = $id_wishlist;
			$pk_wishlist['isLogged'] = true;
			$pk_wishlist['wishlist_products'] = ($id_wishlist == false ? false : WishList::getProductByIdCustomer($id_wishlist, $this->context->customer->id, $this->context->language->id, null, true));
			
			$pk_wishlist['wishlists'] = $wishlists;
			$pk_wishlist['ptoken'] = Tools::getToken(false);			
			if ($pk_wishlist['wishlist_products']) {
				foreach ($pk_wishlist['wishlist_products'] as $id => $data) {
					$cover = Image::getCover($pk_wishlist['wishlist_products'][$id]['id_product']);		
					$pk_wishlist['wishlist_products'][$id]['image'] = $pk_wishlist['wishlist_products'][$id]['id_product'].'-'.(int)$cover["id_image"];
				}				
			}

		} else {

			$pk_wishlist['wishlist_products'] = false;
			$pk_wishlist['wishlists'] = false;

		}
		return $pk_wishlist;
	}

	public function pk_getCompare() {
				
		$products = array();
		$product = false;
		$compareID = Db::getInstance()->executeS('SELECT id_compare FROM '._DB_PREFIX_.'compare WHERE id_customer='.(int)$this->context->customer->id);
		
		if ($this->context->cookie->id_compare) {
			$compareProductsReq = Db::getInstance()->executeS('SELECT id_product FROM '._DB_PREFIX_.'compare_product WHERE id_compare='.$this->context->cookie->id_compare);
			foreach ($compareProductsReq as $key => $value) {
				$obj[$key] = new Product((int)$value["id_product"], false, $this->context->language->id);
				if (!Validate::isLoadedObject($obj[$key]))
					continue;
				else
				{
					$images = $obj[$key]->getImages($this->context->language->id);
					foreach ($images as $image)
					{
						if ($image['cover'])
						{
							$products['cover'] = $obj[$key]->id.'-'.$image['id_image'];
							break;
						}
					}
					if (!isset($products['cover']))
						$products['cover'] = $this->context->language->iso_code.'-default';
				}
				$product[$key] = get_object_vars($obj[$key]);
				$product[$key]['cover'] = $products['cover'];
				$product[$key]['price'] = $obj[$key]->getPublicPrice();
				$product[$key]['specprice'] = $obj[$key]->getPriceStatic($product[$key]["id"]);
			}	
		} else {
			return false;
			}

		return $product;
	}

	protected function getCacheId($name = null) {
		return parent::getCacheId($name.'|'.date('Ymd'));
	}
}
