<?php

if (!defined('_PS_VERSION_'))
	exit;

class pk_aboutus extends Module
{
	public function __construct()
	{
		$this->name = 'pk_aboutus';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('About Us Block');
		$this->description = $this->l('Text module for your homepage.');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/pk_aboutusClass.php';
	}

	public function install()
	{
		if (
			!parent::install() || 
			!$this->registerHook('displayHome') || 
			!$this->registerHook('hook_home_01') ||
            !$this->registerHook('hook_home_02') ||
            !$this->registerHook('hook_home_03') ||
            !$this->registerHook('hook_home_04') ||
            !$this->registerHook('hook_home_05') ||
            !$this->registerHook('hook_home_06') ||
            !$this->registerHook('hook_home_07') ||
            !$this->registerHook('narrow_top') ||
            !$this->registerHook('narrow_middle') ||
            !$this->registerHook('narrow_bottom') ||
			!$this->registerHook('displayHeader'))
			return false;

		$res = Db::getInstance()->execute(
			'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pk_aboutus` (
			`id_pk_aboutus` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			`body_home_logo_link` varchar(255) NOT NULL,
			PRIMARY KEY (`id_pk_aboutus`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
		);

		if ($res)
			$res &= Db::getInstance()->execute(
				'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pk_aboutus_lang` (
				`id_pk_aboutus` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`body_title` varchar(255) NOT NULL,
				`body_subheading` varchar(255) NOT NULL,
				`body_paragraph` text NOT NULL,
				`body_logo_subheading` varchar(255) NOT NULL,
				PRIMARY KEY (`id_pk_aboutus`, `id_lang`))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
			);

		if ($res)
			foreach (Shop::getShops(false) as $shop)
				$res &= $this->createExamplepk_aboutus($shop['id_shop']);

		if (!$res)
			$res &= $this->uninstall();

		return (bool)$res;
	}

	private function createExamplepk_aboutus($id_shop)
	{
		$pk_aboutus = new pk_aboutusClass();
		$pk_aboutus->id_shop = (int)$id_shop;
		$pk_aboutus->body_home_logo_link = 'http://promokit.eu';
		$pk_aboutus->body_homepage_logo = "aboutus.jpg";
		foreach (Language::getLanguages(false) as $lang)
		{
			$pk_aboutus->body_title[$lang['id_lang']] = 'About Alysum Store.';
			$pk_aboutus->body_subheading[$lang['id_lang']] = '';
			$pk_aboutus->body_paragraph[$lang['id_lang']] = '<p>Pellentesque sollicitudin sem eget arcu imperdiet eu hasellus mattis sem et nibh accumsan molestie. Nulla facilisi. Proin auctor tortor eget quam sollicitudin dictum. In porttitor orci at lacus pharetra porta. Cras venenatis interdum mi sed molestie. Sed arcu erat, mattis non pretium id, adipiscing vitae lacus.</p><ul><li>Vestibulum vulputate, nisi quis blandit ultricies, arcu massa vehicula lacus.</li><li>Mauris mauris arcu, volutpat vitae interdum varius, feugiat at tellus.</li><li>Cras venenatis interdum mi sed molestie.</li></ul><p>Braesent consectetur ligula eu eros varius ut fringilla nulla metus metus, ullamcorper eget eleifend quis, molestie ut metus. Proin nec dolor elit. In in neque id mi vehicula luctus. Nullam suscipit diam posuere augue facilisis, ac luctus felis malesuada. Nullam volutpat gravida diam.</p>';
			$pk_aboutus->body_logo_subheading[$lang['id_lang']] = '';
		}

		return $pk_aboutus->add();
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_aboutus`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_aboutus_lang`');

		if ($res == 0 || !parent::uninstall())
			return false;

		return true;
	}

	private function initForm()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'pk_aboutus';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitUpdatepk_aboutus';

		$file = dirname(__FILE__).'/img/homepage_logo_'.(int)$this->context->shop->id.'.jpg';
		$logo = (file_exists($file) ? '<img src="'.$this->_path.'img/homepage_logo_'.(int)$this->context->shop->id.'.jpg">' : '');

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->displayName,
				'image' => $this->_path.'logo.gif'
			),
			'submit' => array(
				'name' => 'submitUpdatepk_aboutus',
				'title' => $this->l('Save '),
				'class' => 'button pull-right'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Main title'),
					'name' => 'body_title',
					'lang' => true,
					'size' => 64,
					'desc' => $this->l('Appears along top of your homepage'),
				),
				/*array(
					'type' => 'text',
					'label' => $this->l('Subheading'),
					'name' => 'body_subheading',
					'lang' => true,
					'size' => 64,
				),*/
				array(
					'type' => 'textarea',
					'label' => $this->l('Introductory text'),
					'name' => 'body_paragraph',
					'lang' => true,
					'autoload_rte' => true,
					'desc' => $this->l('For example... explain your mission, highlight a new product, or describe a recent event.'),
					'cols' => 60,
					'rows' => 30
				),
				array(
					'type' => 'file',
					'label' => $this->l('Homepage logo'),
					'name' => 'body_homepage_logo',
					'display_image' => true,
					'image' => $logo,
					'delete_url' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteLogoImage=1'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Homepage logo link'),
					'name' => 'body_home_logo_link',
					'size' => 33,
				),
				/*array(
					'type' => 'text',
					'label' => $this->l('Homepage logo subheading'),
					'name' => 'body_logo_subheading',
					'lang' => true,
					'size' => 33,
				),*/
			)
		);

		return $helper;
	}

	private function initToolbar()
	{
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);

		return $this->toolbar_btn;
	}

	public function getContent()
	{
		$this->_html = '';
		$this->postProcess();

		$helper = $this->initForm();

		$id_shop = (int)$this->context->shop->id;
		$pk_aboutus = pk_aboutusClass::getByIdShop($id_shop);

		if (!$pk_aboutus) //if pk_aboutus ddo not exist for this shop => create a new example one
			$this->createExamplepk_aboutus($id_shop);

		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
		{
			if ($input['name'] != 'body_homepage_logo')
				$helper->fields_value[$input['name']] = $pk_aboutus->{$input['name']};
		}

		$file = dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg';
		$helper->fields_value['body_homepage_logo']['image'] = (file_exists($file) ? '<img src="'.$this->_path.'img/homepage_logo_'.(int)$id_shop.'.jpg">' : '');
		if ($helper->fields_value['body_homepage_logo'] && file_exists($file))
			$helper->fields_value['body_homepage_logo']['size'] = filesize($file) / 1000;

		$this->_html .= $helper->generateForm($this->fields_form);

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		// Delete logo image retrocompat 1.5
		if (Tools::isSubmit('deleteLogoImage') || Tools::isSubmit('deleteImage'))
		{
			if (!file_exists(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg'))
				$errors .= $this->displayError($this->l('This action cannot be made.'));
			else
			{
				unlink(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('pk_aboutus_IMAGE_DISABLE', 1);
				$this->_clearCache('pk_aboutus.tpl');
				Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
			}
			$this->_html .= $errors;
		}

		if (Tools::isSubmit('submitUpdatepk_aboutus'))
		{
			$id_shop = (int)$this->context->shop->id;
			$pk_aboutus = pk_aboutusClass::getByIdShop($id_shop);
			$pk_aboutus->copyFromPost();
			if (empty($pk_aboutus->id_shop))
				$pk_aboutus->id_shop = (int)$id_shop;
			$pk_aboutus->save();

			/* upload the image */
			if (isset($_FILES['body_homepage_logo']) && isset($_FILES['body_homepage_logo']['tmp_name']) && !empty($_FILES['body_homepage_logo']['tmp_name']))
			{
				Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
				if (file_exists(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg'))
					unlink(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg');
				if ($error = ImageManager::validateUpload($_FILES['body_homepage_logo']))
					$errors .= $error;
				elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['body_homepage_logo']['tmp_name'], $tmp_name))
					return false;
				elseif (!ImageManager::resize($tmp_name, dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg'))
					$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));
				if (isset($tmp_name))
					unlink($tmp_name);
			}
			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;
			if (file_exists(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg'))
			{
				list($width, $height, $type, $attr) = getimagesize(dirname(__FILE__).'/img/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('pk_aboutus_IMAGE_WIDTH', (int)round($width));
				Configuration::updateValue('pk_aboutus_IMAGE_HEIGHT', (int)round($height));
				Configuration::updateValue('pk_aboutus_IMAGE_DISABLE', 0);
			}
			$this->_clearCache('pk_aboutus.tpl');
			Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id));
		}

		return true;
	}

	public function prepareHook($params)	{
		$id_shop = (int)$this->context->shop->id;
		$pk_aboutus = pk_aboutusClass::getByIdShop($id_shop);
		if (!$pk_aboutus)
			return;
		$pk_aboutus = new pk_aboutusClass((int)$pk_aboutus->id, $this->context->language->id);
		if (!$pk_aboutus)
			return;
		$this->smarty->assign(
			array(
				'pk_aboutus' => $pk_aboutus,
				'default_lang' => (int)$this->context->language->id,
				'image_width' => Configuration::get('pk_aboutus_IMAGE_WIDTH'),
				'image_height' => Configuration::get('pk_aboutus_IMAGE_HEIGHT'),
				'id_lang' => $this->context->language->id,
				'homepage_logo' => !Configuration::get('pk_aboutus_IMAGE_DISABLE') && file_exists('modules/pk_aboutus/img/homepage_logo_'.(int)$id_shop.'.jpg'),
				'image_path' => $this->_path.'img/homepage_logo_'.(int)$id_shop.'.jpg'
			)
		);
	}

	public function hookDisplayHome($params)
	{
		if ($this->context->controller->php_self == 'index') {
		$status = $this->getModuleState("displayHome");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."dh")))
					$this->prepareHook($params);

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."dh"));
			}
		}
	}

	public function hookDisplayHeader()
	{
		if ($this->context->controller->php_self == 'index')
			$this->context->controller->addCSS(($this->_path).'css/pk_aboutus.css', 'all');
	}

	public function hookhook_home_01($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh01")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh01"));
			}
		}

	}

	public function hookhook_home_02($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh02")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh02"));
			}
		}

	}

	public function hookhook_home_03($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_03");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh03")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh03"));
			}
		}

	}

	public function hookhook_home_04($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_04");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh04")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh04"));
			}
		}

	}

	public function hookhook_home_05($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_05");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh05")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh05"));
			}
		}

	}

	public function hookhook_home_06($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_06");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh06")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh06"));
			}
		}

	}

	public function hookhook_home_07($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("hook_home_07");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."hh07")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."hh07"));
			}
		}

	}

	public function hooknarrow_top($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."nt")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."nt"));
			}
		}

	}

	public function hooknarrow_middle($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."nm")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."nm"));
			}
		}

	}

	public function hooknarrow_bottom($params) {

		if ($this->context->controller->php_self == 'index') {
	 		$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name."nb")))
					$this->prepareHook($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name."nb"));
			}
		}

	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

	protected function getCacheId($name = null)
	{
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

}