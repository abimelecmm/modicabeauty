<?php


if (!defined('_CAN_LOAD_FILES_'))
	exit;

include_once _PS_MODULE_DIR_.'pk_features/pk_featuresClass.php';

class pk_features extends Module
{
	public function __construct()
	{
		$this->name = 'pk_features';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '2.2';
		$this->author = 'promokit.eu';

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Blocks of features');
		$this->description = $this->l('Show features of your shop to your customers.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return parent::install() &&
			$this->installDB() &&
			Configuration::updateValue('pk_features_NBBLOCKS', 5) &&
			$this->registerHook('wide_top') &&
			$this->registerHook('displayHeader') &&
			$this->registerHook('wide_middle') &&
			$this->registerHook('wide_bottom') &&
			$this->installFixtures();		
	}
	
	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pk_features` (
				`id_reinsurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL ,
				`file_name` VARCHAR(100) NOT NULL,
				PRIMARY KEY (`id_reinsurance`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pk_features_lang` (
				`id_reinsurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_lang` int(10) unsigned NOT NULL ,
				`text` VARCHAR(300) NOT NULL,
				`url` VARCHAR(300) NOT NULL,
				`title` VARCHAR(300) NOT NULL,
				PRIMARY KEY (`id_reinsurance`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
		// Delete configuration
		return Configuration::deleteByName('pk_features_NBBLOCKS') &&
			$this->uninstallDB() &&
			parent::uninstall();
	}

	public function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_features`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_features_lang`');
	}

	public function addToDB()
	{
		if (isset($_POST['nbblocks']))
		{
			for ($i = 1; $i <= (int)$_POST['nbblocks']; $i++)
			{
				$filename = explode('.', $_FILES['info'.$i.'_file']['name']);
				if (isset($_FILES['info'.$i.'_file']) && isset($_FILES['info'.$i.'_file']['tmp_name']) && !empty($_FILES['info'.$i.'_file']['tmp_name']))
				{
					if ($error = ImageManager::validateUpload($_FILES['info'.$i.'_file']))
						return false;
					elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['info'.$i.'_file']['tmp_name'], $tmpName))
						return false;
					elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/img/'.$filename[0].'.jpg'))
						return false;
					unlink($tmpName);
				}
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'pk_features` (`filename`,`text`,`title`,`url`) VALUES ("'.((isset($filename[0]) && $filename[0] != '') ? pSQL($filename[0]) : '').'", "'.((isset($_POST['info'.$i.'_text']) && $_POST['info'.$i.'_text'] != '') ? $_POST['info'.$i.'_text'] : '').'", "'.((isset($_POST['info'.$i.'_title']) && $_POST['info'.$i.'_title'] != '') ? $_POST['info'.$i.'_title'] : '').'", "'.((isset($_POST['info'.$i.'_url']) && $_POST['info'.$i.'_url'] != '') ? $_POST['info'.$i.'_url'] : '').'")');
			}
			return true;
		} else
			return false;
	}

	public function removeFromDB()
	{
		$dir = opendir(dirname(__FILE__).'/img');
		while (false !== ($file = readdir($dir)))
		{
			$path = dirname(__FILE__).'/img/'.$file;
			if ($file != '..' && $file != '.' && !is_dir($file))
				unlink($path);
		}
		closedir($dir);

		return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pk_features`');
	}

	public function getContent()
	{
		$html = '';
		$id_reinsurance = (int)Tools::getValue('id_reinsurance');

		if (Tools::isSubmit('savepk_features'))
		{
			if ($id_reinsurance = Tools::getValue('id_reinsurance'))
				$reinsurance = new pk_featuresClass((int)$id_reinsurance);
			else
				$reinsurance = new pk_featuresClass();
			$reinsurance->copyFromPost();
			$reinsurance->id_shop = $this->context->shop->id;
			
			if ($reinsurance->validateFields(false) && $reinsurance->validateFieldsLang(false))
			{
				$reinsurance->save();
				if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
				{
					if ($error = ImageManager::validateUpload($_FILES['image']))
						return false;
					elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['image']['tmp_name'], $tmpName))
						return false;
					elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/img/reinsurance-'.(int)$reinsurance->id.'-'.(int)$reinsurance->id_shop.'.jpg'))
						return false;
					unlink($tmpName);
					$reinsurance->file_name = 'reinsurance-'.(int)$reinsurance->id.'-'.(int)$reinsurance->id_shop.'.jpg';
					$reinsurance->save();
				}
				$this->_clearCache('pk_features.tpl');
			}
			else
				$html .= '<div class="conf error">'.$this->l('An error occurred while attempting to save.').'</div>';
		}
		
		if (Tools::isSubmit('updatepk_features') || Tools::isSubmit('addpk_features'))
		{
			$helper = $this->initForm();
			foreach (Language::getLanguages(false) as $lang)
				if ($id_reinsurance)
				{
					$reinsurance = new pk_featuresClass((int)$id_reinsurance);
					$helper->fields_value['text'][(int)$lang['id_lang']] = $reinsurance->text[(int)$lang['id_lang']];
					$helper->fields_value['title'][(int)$lang['id_lang']] = $reinsurance->title[(int)$lang['id_lang']];
					$helper->fields_value['url'][(int)$lang['id_lang']] = $reinsurance->url[(int)$lang['id_lang']];
				}	
				else {
					$helper->fields_value['text'][(int)$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang'], '');
					$helper->fields_value['title'][(int)$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], '');
					$helper->fields_value['url'][(int)$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], '');
				}
			if ($id_reinsurance = Tools::getValue('id_reinsurance'))
			{
				$this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_reinsurance');
				$helper->fields_value['id_reinsurance'] = (int)$id_reinsurance;
 			}
				
			return $html.$helper->generateForm($this->fields_form);
		}
		else if (Tools::isSubmit('deletepk_features'))
		{
			$reinsurance = new pk_featuresClass((int)$id_reinsurance);
			if (file_exists(dirname(__FILE__).'/img/'.$reinsurance->file_name))
				unlink(dirname(__FILE__).'/img/'.$reinsurance->file_name);
			$reinsurance->delete();
			$this->_clearCache('pk_features.tpl');
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $html.$helper->generateList($this->getListContent((int)Configuration::get('PS_LANG_DEFAULT')), $this->fields_list);
		}

		if (isset($_POST['submitModule']))
		{
			Configuration::updateValue('pk_features_NBBLOCKS', ((isset($_POST['nbblocks']) && $_POST['nbblocks'] != '') ? (int)$_POST['nbblocks'] : ''));
			if ($this->removeFromDB() && $this->addToDB())
			{
				$this->_clearCache('pk_features.tpl');
				$output = '<div class="conf confirm">'.$this->l('The block configuration has been updated.').'</div>';
			}
			else
				$output = '<div class="conf error"><img src="../img/admin/disabled.gif"/>'.$this->l('An error occurred while attempting to save.').'</div>';
		}
	}

	protected function getListContent($id_lang)
	{
		return  Db::getInstance()->executeS('
			SELECT r.`id_reinsurance`, r.`id_shop`, r.`file_name`, rl.`text`, rl.`title`, rl.`url`
			FROM `'._DB_PREFIX_.'pk_features` r
			LEFT JOIN `'._DB_PREFIX_.'pk_features_lang` rl ON (r.`id_reinsurance` = rl.`id_reinsurance`)
			WHERE `id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang());
	}

	protected function initForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('New feature block'),
			),
			'input' => array(
				array(
					'type' => 'file',
					'label' => $this->l('Image'),
					'name' => 'image',
					'value' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Text'),
					'lang' => true,
					'name' => 'text',
					'cols' => 40,
					'rows' => 10
				),
				array(
					'type' => 'text',
					'label' => $this->l('Title'),
					'lang' => true,
					'name' => 'title'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Url'),
					'lang' => true,
					'name' => 'url'
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'pk_features';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'savepk_features';
		$helper->toolbar_btn =  array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' =>
			array(
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);
		return $helper;
	}

	protected function initList()
	{
		$this->fields_list = array(
			'id_reinsurance' => array(
				'title' => $this->l('ID'),
				'width' => 120,
				'type' => 'text',
				'search' => false,
				'orderby' => false
			),
			'text' => array(
				'title' => $this->l('Text'),
				'width' => 140,
				'type' => 'text',
				'search' => false,
				'orderby' => false
			),
			'title' => array(
				'title' => $this->l('Title'),
				'width' => 140,
				'type' => 'text',
				'search' => false,
				'orderby' => false
			),
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array('title' => $this->l('ID Shop'), 'align' => 'center', 'width' => 25, 'type' => 'int');

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->identifier = 'id_reinsurance';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}

	public function hookHeader($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$this->context->controller->addCSS($this->_path.'css/style.css', 'all');
			$this->context->controller->addJS($this->_path.'js/scripts.js');
		}
	}

	public function hookwide_top($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
		$status = $this->getModuleState("wide_top");
		if ($status == 1) {
			if (!$this->isCached('pk_features.tpl', $this->getCacheId())) {
				$infos = $this->getListContent($this->context->language->id);
				$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
			}
			return $this->display(__FILE__, 'pk_features.tpl', $this->getCacheId());
			//return $this->display(__FILE__, 'pk_features.tpl');
		}
		}
	}

	public function hookwide_middle($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
		$status = $this->getModuleState("wide_middle");
		if ($status == 1) {
			if (!$this->isCached('pk_features.tpl', $this->getCacheId())) {
				$infos = $this->getListContent($this->context->language->id);
				$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
			}
			return $this->display(__FILE__, 'pk_features.tpl', $this->getCacheId());
			//return $this->display(__FILE__, 'pk_features.tpl');
		}
		}
	}

	public function hookwide_bottom($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
		$status = $this->getModuleState("wide_bottom");
		if ($status == 1) {
			if (!$this->isCached('pk_features.tpl', $this->getCacheId())) {
				$infos = $this->getListContent($this->context->language->id);
				$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
			}
			return $this->display(__FILE__, 'pk_features.tpl', $this->getCacheId());
		}
		}
	}

	public function installFixtures()
	{
		$return = true;
		$tab_texts = array(
			array('text' => 'Cras pellentesque, nisi ac tempus pellentesque, orci sem commodo urna,amet egestas ipsum orci sit amet tellus. Mauris eu ante felis.', 'file_name' => 'reinsurance-1-1.jpg', 'title' => 'Responsive Design', 'url' => ''),
			array('text' => 'Etiam dapibus mattis sapien, blandit molestie nunc venenatis ut. Phasellusimperdiet lacinia est, nec convallis dolor aliquet ac.', 'file_name' => 'reinsurance-2-1.jpg', 'title' => 'Powerful Admin Panel', 'url' => ''),
			array('text' => 'Duis a dignissim nulla. Phasellus lacinia aliquam lorem, a consequat erat interdum nec. Aenean ut leo sem, id gravida tortor.', 'file_name' => 'reinsurance-3-1.jpg', 'title' => 'Retina Ready', 'url' => ''),
			array('text' => 'Pellentesque ut libero in nibh aliquet pretium eget elementum felis. Integer dapibus auctor tincidunt. Suspendisse potenti.', 'file_name' => 'reinsurance-4-1.jpg', 'title' => 'Premium Support', 'url' => '')
		);
		
		foreach($tab_texts as $tab)
		{
			$reinsurance = new pk_featuresClass();
			foreach (Language::getLanguages(false) as $lang) {
				$reinsurance->text[$lang['id_lang']] = $tab['text'];
				$reinsurance->title[$lang['id_lang']] = $tab['title'];
				$reinsurance->url[$lang['id_lang']] = $tab['url'];
			}
			$reinsurance->file_name = $tab['file_name'];
			$reinsurance->id_shop = $this->context->shop->id;
			$return &= $reinsurance->save();
		}
		return $return;
	}
	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}
}
