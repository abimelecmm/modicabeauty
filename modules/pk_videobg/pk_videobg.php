<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class pk_videobg extends Module
{
	public function __construct()
	{
		$this->name = 'pk_videobg';
		$this->author = 'promokit.eu';
		$this->tab = 'front_office_features';
		$this->version = '1.0';

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Advertising Block with video background');
		$this->description = $this->l('Allows you to add advertising with video background.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}
	
	public function install()
	{
		return parent::install()
			&& Configuration::updateValue('pk_videobg_link', 'https://www.youtube.com/watch?v=NzeHQqVtFZc')
			&& Configuration::updateValue('pk_videobg_title', 'Lookbook')
			&& Configuration::updateValue('pk_videobg_subtitle', 'spring summer 2015')
			&& Configuration::updateValue('pk_videobg_url', 'https://www.youtube.com/watch?v=NzeHQqVtFZc')
			&& Configuration::updateValue('pk_videobg_text', 'Quisque volutpat blandit ipsum eget pulvinar. In viverra, mi ac convallis dictum enim. condimentum lorem.')
			&& $this->registerHook('displayHeader')
			&& $this->registerHook('wide_top')
			&& $this->registerHook('wide_middle')
			&& $this->registerHook('wide_bottom');
	}
	
	public function uninstall()
	{
		// Delete configuration
		return 
			Configuration::deleteByName('pk_videobg_link') && 
			Configuration::deleteByName('pk_videobg_title') && 
			Configuration::deleteByName('pk_videobg_subtitle') &&
			Configuration::deleteByName('pk_videobg_url') &&
			Configuration::deleteByName('pk_videobg_text') &&
			parent::uninstall();
	}
	
	public function getContent()
	{
		$html = '';
		// If we try to update the settings
		if (Tools::isSubmit('submitModule'))
		{				
			Configuration::updateValue('pk_videobg_link', Tools::getValue('pk_videobg_link'));
			Configuration::updateValue('pk_videobg_title', Tools::getValue('pk_videobg_title'));
			Configuration::updateValue('pk_videobg_subtitle', Tools::getValue('pk_videobg_subtitle'));
			Configuration::updateValue('pk_videobg_url', Tools::getValue('pk_videobg_url'));
			Configuration::updateValue('pk_videobg_text', Tools::getValue('pk_videobg_text'));
			$this->_clearCache($this->name.'.tpl');
			$html .= $this->displayConfirmation($this->l('Configuration updated'));
		}

		$html .= $this->renderForm();

		return $html;
	}

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).$this->name.'.css', 'all');
	}

	public function getData($params) {
		
		global $smarty;
		$title = (string)Configuration::get('pk_videobg_title');
		$subtitle = (string)Configuration::get('pk_videobg_subtitle');
		$url = (string)Configuration::get('pk_videobg_url');
		$text = (string)Configuration::get('pk_videobg_text');
		$link = str_replace(array("http:", "https:"), "", Configuration::get('pk_videobg_link'));
		$local = false;

		if (strpos($link,'youtube') !== false)
			if (strpos($link,'watch?v=') !== false) {
				$link = str_replace("watch?v=", "embed/", $link);
				$link = $link."?autoplay=1&amp;controls=0&amp;loop=1&amp;showinfo=0&amp;modestbranding=1&amp;disablekb=1&amp;enablejsapi=1";
			}

		if (strpos($link,'vimeo') !== false)
			if (strpos($link,'//vimeo') !== false)
				$link = str_replace("//vimeo.com", "//player.vimeo.com/video", $link);

		if ((strpos($link,'youtube') == false) && (strpos($link,'vimeo') == false))
			$local = true;



		$tpl = $this->name;
		if (isset($params['pk_videobg_tpl']) && $params['pk_videobg_tpl'])
			$tpl = $params['pk_videobg_tpl'];

		if (!$this->isCached($tpl.'.tpl', $this->getCacheId()))
			$smarty->assign(array(
				'pk_videobg_link' => $link,
				'pk_videobg_title' => $title,
				'pk_videobg_subtitle' => $subtitle,
				'pk_videobg_url' => $url,
				'pk_videobg_text' => $text,
				'pk_videobg_local' => $local
			));

	}
	
	public function hookwide_top($params)
	{
		$status = $this->getModuleState("wide_top");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$this->getData($params);
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}

	public function hookwide_middle($params)
	{
		$status = $this->getModuleState("wide_middle");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$this->getData($params);
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}

	public function hookwide_bottom($params)
	{
		$status = $this->getModuleState("wide_bottom");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$this->getData($params);
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}
	
	public function hookDisplayLeftColumn($params)
	{
		return $this->hookDisplayRightColumn($params);
	}
	
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Title'),
						'name' => 'pk_videobg_title',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Subtitle'),
						'name' => 'pk_videobg_subtitle',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Text'),
						'name' => 'pk_videobg_text',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Button URL'),
						'name' => 'pk_videobg_url',
					),
					array(
						'type' => 'text',
						'label' => $this->l('Video Link'),
						'name' => 'pk_videobg_link',
						'desc' => "You can use direct links to video from youtube or vimeo. You can also upload video to your server and put direct link here. For example: http://alysum.promokit.eu/upload/videofile.mp4",
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{
		return array(
			'pk_videobg_link' => Tools::getValue('pk_videobg_link', Configuration::get('pk_videobg_link')),
			'pk_videobg_title' => Tools::getValue('pk_videobg_title', Configuration::get('pk_videobg_title')),
			'pk_videobg_subtitle' => Tools::getValue('pk_videobg_subtitle', Configuration::get('pk_videobg_subtitle')),
			'pk_videobg_url' => Tools::getValue('pk_videobg_url', Configuration::get('pk_videobg_url')),
			'pk_videobg_text' => Tools::getValue('pk_videobg_text', Configuration::get('pk_videobg_text')),
		);
	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

}