<?php

if (!defined('_PS_VERSION_'))
	exit;

class pk_instafeed extends Module
{
	private $_html = '';
	private $_postErrors = array();

    function __construct()
    {
		$this->name = 'pk_instafeed';
		$this->tab = 'front_office_features';
		$this->version = '1.2.1';
		$this->author = 'promokit.eu';

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Instagram Feed');
		$this->description = $this->l('Shows instagram images by hashtag.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{		
		if (
			parent::install() == false
			|| $this->registerHook('header') == false
			|| $this->registerHook('hook_home_01') == false
			|| $this->registerHook('hook_home_02') == false
			|| $this->registerHook('narrow_top') == false
			|| $this->registerHook('narrow_middle') == false
			|| $this->registerHook('narrow_bottom') == false	
			|| Configuration::updateValue('PK_INSTA_API_CODE', "5254e92d3e394794a14537c1a0572e37") == false
			|| Configuration::updateValue('PK_INSTA_AT', "1535837010.5254e92.a1982a07ac4c44f08778cb3a58b488c9") == false
			|| Configuration::updateValue('PK_INSTA_CONTENT_TYPE', "tagged") == false
			|| Configuration::updateValue('PK_INSTA_HASHTAG', "sky") == false
			|| Configuration::updateValue('PK_INSTA_API_SECRET', "") == false
			|| Configuration::updateValue('PK_INSTA_USERNAME', "prestashop") == false
			|| Configuration::updateValue('PK_INSTA_API_CALLBACK', "http://localhost") == false
			|| Configuration::updateValue('PK_INSTA_SORTBY', "none") == false
			|| Configuration::updateValue('PK_INSTA_NUMBER', "10") == false	
			|| Configuration::updateValue('PK_INSTA_NUMBER_VIS', "4") == false
			|| Configuration::updateValue('PK_INSTA_LINKS', true) == false
			|| Configuration::updateValue('PK_INSTA_LIKES', true) == false
			|| Configuration::updateValue('PK_INSTA_COMMENTS', true) == false
			|| Configuration::updateValue('PK_INSTA_CAPTION', true) == false
			|| Configuration::updateValue('PK_INSTA_CAROUSEL', true) == false
			|| Configuration::updateValue('PK_INSTA_BACKGROUND', false) == false
			|| Configuration::updateValue('PK_INSTA_AUTOSCROLL', true) == false
			|| Configuration::updateValue('PK_INSTA_COLOR', false) == false
			)
			return false;
		return true;	
	}
	
	public function uninstall()
	{
		return 
			Configuration::deleteByName('PK_INSTA_API_CODE') &&
			Configuration::deleteByName('PK_INSTA_AT') &&
			Configuration::deleteByName('PK_INSTA_CONTENT_TYPE') &&
			Configuration::deleteByName('PK_INSTA_HASHTAG') &&
			Configuration::deleteByName('PK_INSTA_API_SECRET') &&
			Configuration::deleteByName('PK_INSTA_API_CALLBACK') &&
			Configuration::deleteByName('PK_INSTA_USERNAME') &&
			Configuration::deleteByName('PK_INSTA_SORTBY') &&
			Configuration::deleteByName('PK_INSTA_NUMBER') &&
			Configuration::deleteByName('PK_INSTA_NUMBER_VIS') &&
			Configuration::deleteByName('PK_INSTA_LINKS') &&
			Configuration::deleteByName('PK_INSTA_LIKES') &&
			Configuration::deleteByName('PK_INSTA_COMMENTS') &&
			Configuration::deleteByName('PK_INSTA_CAPTION') &&
			Configuration::deleteByName('PK_INSTA_CAROUSEL') &&
			Configuration::deleteByName('PK_INSTA_BACKGROUND') &&
			Configuration::deleteByName('PK_INSTA_AUTOSCROLL') &&
			Configuration::deleteByName('PK_INSTA_COLOR') &&
			parent::uninstall();
	}

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('pk_ig_submit')) {
			Configuration::updateValue('PK_INSTA_API_CODE', Tools::getValue('PK_INSTA_API_CODE'));
			Configuration::updateValue('PK_INSTA_AT', Tools::getValue('PK_INSTA_AT'));
			Configuration::updateValue('PK_INSTA_CONTENT_TYPE', Tools::getValue('PK_INSTA_CONTENT_TYPE'));
			Configuration::updateValue('PK_INSTA_HASHTAG', Tools::getValue('PK_INSTA_HASHTAG'));
			Configuration::updateValue('PK_INSTA_API_SECRET', Tools::getValue('PK_INSTA_API_SECRET'));
			Configuration::updateValue('PK_INSTA_API_CALLBACK', Tools::getValue('PK_INSTA_API_CALLBACK'));
			Configuration::updateValue('PK_INSTA_USERNAME', Tools::getValue('PK_INSTA_USERNAME'));
			Configuration::updateValue('PK_INSTA_SORTBY', Tools::getValue('PK_INSTA_SORTBY'));
			Configuration::updateValue('PK_INSTA_NUMBER', Tools::getValue('PK_INSTA_NUMBER'));
			Configuration::updateValue('PK_INSTA_NUMBER_VIS', Tools::getValue('PK_INSTA_NUMBER_VIS'));
			Configuration::updateValue('PK_INSTA_LINKS', Tools::getValue('PK_INSTA_LINKS'));
			Configuration::updateValue('PK_INSTA_LIKES', Tools::getValue('PK_INSTA_LIKES'));
			Configuration::updateValue('PK_INSTA_COMMENTS', Tools::getValue('PK_INSTA_COMMENTS'));
			Configuration::updateValue('PK_INSTA_CAPTION', Tools::getValue('PK_INSTA_CAPTION'));	
			Configuration::updateValue('PK_INSTA_CAROUSEL', Tools::getValue('PK_INSTA_CAROUSEL'));	
			Configuration::updateValue('PK_INSTA_BACKGROUND', Tools::getValue('PK_INSTA_BACKGROUND'));
			Configuration::updateValue('PK_INSTA_AUTOSCROLL', Tools::getValue('PK_INSTA_AUTOSCROLL'));
			Configuration::updateValue('PK_INSTA_COLOR', Tools::getValue('PK_INSTA_COLOR'));
			$output .= $this->displayConfirmation($this->l('Settings updated'));
			$id_shop = (int)$this->context->shop->id;

			if (isset($_FILES['insta-bg']) && isset($_FILES['insta-bg']['tmp_name']) && !empty($_FILES['insta-bg']['tmp_name'])) {
				$img = dirname(__FILE__).'/img/instabg_'.$id_shop.'.jpg';
				if (file_exists($img))
					unlink($img);
				
				if ($error = ImageManager::validateUpload($_FILES['insta-bg']))
					$errors .= $error;

				elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['insta-bg']['tmp_name'], $tmp_name))
					return false;			

				elseif (!ImageManager::resize($tmp_name, $img))
					$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));

				if (isset($tmp_name))
					unlink($tmp_name);

			}

		}
		$img = "";
		$rev = date("H").date("i").date("s");
		if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
			$img = '<div class="panel"><div class="panel-heading"><i class="icon-cogs"></i>&nbsp;Instagram Background Image</div><div class="form-wrapper"><div class="form-group" id="instabg" style="overflow:hidden"><div class="col-lg-12"><div class="form-group"><div class="col-sm-6"><img src="'.$this->_path.'img/instabg_'.$this->context->shop->id.'.jpg?'.$rev.' alt="" style="max-width:400px; height:auto; width:auto; height:150px;" /></div></div></div></div></div></div>';

		$end = '<div class="panel"><div class="panel-heading"><i class="icon-cogs"></i>&nbsp;<a target="_blank" href="http://jelled.com/instagram/access-token">'.$this->l('How to Generate an Instagram Access Token | Tutorial').'</a></div><div class="form-wrapper"><div class="form-group" style="overflow:hidden"><div class="col-lg-12"><div class="form-group"><div class="col-sm-6"><iframe width="560" height="315" src="//www.youtube.com/embed/LkuJtIcXR68" frameborder="0" allowfullscreen></iframe></div></div></div></div></div></div>';
		return $output.$this->renderForm().$img.$end;
	}

	public function hookhook_home_01($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
					$bgimg = "";
					if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
						$bgimg = "img/instabg_".$this->context->shop->id.".jpg";
					$this->context->smarty->assign(array(
						'pk_ig' => $this->getValuesFromDB(),
						'pk_ig_suffix' => "middle",
						'this_path' => $this->_path,
						'insta_bg' => $bgimg
					));		
				return $this->display(__FILE__, $this->name.'.tpl');
			}
		}

	}

	public function hookhook_home_02($params)
	{

		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				$bgimg = "";
				if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
					$bgimg = "img/instabg_".$this->context->shop->id.".jpg";
				$this->context->smarty->assign(array(
					'pk_ig' => $this->getValuesFromDB(),
					'pk_ig_suffix' => "middle",
					'this_path' => $this->_path,
					'insta_bg' => $bgimg
				));		
				return $this->display(__FILE__, $this->name.'.tpl');
			}
		}
	}

	public function hooknarrow_top($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				$bgimg = "";
				if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
					$bgimg = "img/instabg_".$this->context->shop->id.".jpg";
				$this->context->smarty->assign(array(
					'pk_ig' => $this->getValuesFromDB(),
					'pk_ig_suffix' => "middle",
					'this_path' => $this->_path,
					'insta_bg' => $bgimg
				));		
				return $this->display(__FILE__, $this->name.'.tpl');
			}
		}
	}
	public function hooknarrow_middle($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				$bgimg = "";
				if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
					$bgimg = "img/instabg_".$this->context->shop->id.".jpg";
				$this->context->smarty->assign(array(
					'pk_ig' => $this->getValuesFromDB(),
					'pk_ig_suffix' => "middle",
					'this_path' => $this->_path,
					'insta_bg' => $bgimg
				));		
				return $this->display(__FILE__, $this->name.'.tpl');
			}
		}
	}
	public function hooknarrow_bottom($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				$bgimg = "";
				if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
					$bgimg = "img/instabg_".$this->context->shop->id.".jpg";
				$this->context->smarty->assign(array(
					'pk_ig' => $this->getValuesFromDB(),
					'pk_ig_suffix' => "middle",
					'this_path' => $this->_path,
					'insta_bg' => $bgimg
				));		
				return $this->display(__FILE__, $this->name.'.tpl');
			}
		}
	}

	public function hookHeader($params)
	{
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index') {
			$this->context->controller->addJS(($this->_path).'js/instafeed.min.js');
			$this->context->controller->addJS(($this->_path).'js/init.js');
			$this->context->controller->addCSS(($this->_path).'css/styles.css', 'all');
		}
	}

	public function renderForm()
	{
		$fields_form_01 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Instagram Personal Data'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Your API client id from Instagram'),
						'name' => 'PK_INSTA_API_CODE',
						'class' => 'fixed-width-xxl',
						'required' => true,
						'desc' => $this->l('Put your instagram account API code.')
					),
					/*array(
						'type' => 'text',
						'label' => $this->l('Your API secret from Instagram'),
						'name' => 'PK_INSTA_API_SECRET',
						'class' => 'fixed-width-xxl',
						'required' => true,
						'desc' => $this->l('Put your instagram account API secret.')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Your website URL from Instagram'),
						'name' => 'PK_INSTA_API_CALLBACK',
						'class' => 'fixed-width-xxl',
						'required' => true,
						'desc' => $this->l('Put your instagram website URL.')
					),	*/				
					array(
						'type' => 'text',
						'label' => $this->l('Access Token (for feed by username)'),
						'name' => 'PK_INSTA_AT',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('Put your instagram account Access Token. Find a tutorial under the settings.')
					),						
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		$fields_form_02 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Module Appearance'),
					'icon' => 'icon-cogs'
				),
				'input' => array(					
					array(
						'type' => 'select',
						'label' => $this->l('Feed content by'),
						'name' => 'PK_INSTA_CONTENT_TYPE',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('Display images by hashtag or by username.'),
						'options' => array(
							'query' => array(
								array(
									'id' => 'tagged',
									'name' => $this->l('Hashtag')),
								array(
									'id' => 'user',
									'name' => $this->l('Username')),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),			
					array(
						'type' => 'text',
						'label' => $this->l('Hashtag # (without "#")'),
						'name' => 'PK_INSTA_HASHTAG',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('Name of the hashtag to get')
					),	
					array(
						'type' => 'text',
						'label' => $this->l('Username'),
						'name' => 'PK_INSTA_USERNAME',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('Your instagram username')
					),
					array(
						'type' => 'select',
						'label' => $this->l('Feed content'),
						'name' => 'PK_INSTA_SORTBY',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('Sort the images in a set order'),
						'options' => array(
							'query' => array(
								array(
									'id' => 'none',
									'name' => $this->l('None')),
								array(
									'id' => 'most-recent',
									'name' => $this->l('Most Recent')),
								array(
									'id' => 'least-recent',
									'name' => $this->l('Least Recent')),
								array(
									'id' => 'most-liked',
									'name' => $this->l('Most Liked')),
								array(
									'id' => 'least-liked',
									'name' => $this->l('Least Liked')),
								array(
									'id' => 'most-commented',
									'name' => $this->l('Most Commented')),
								array(
									'id' => 'least-commented',
									'name' => $this->l('Least Commented')),
								array(
									'id' => 'random',
									'name' => $this->l('Random')),
							),
							'id' => 'id',
							'name' => 'name'
						)
					),	
					array(
						'type' => 'text',
						'label' => $this->l('Number of images'),
						'name' => 'PK_INSTA_NUMBER',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('How many images you want to take from instagram')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Visible images'),
						'name' => 'PK_INSTA_NUMBER_VIS',
						'class' => 'fixed-width-xxl',
						'desc' => $this->l('How many images you want to see in carousel')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Images with links'),
						'name' => 'PK_INSTA_LINKS',
						'desc' => $this->l('Wrap the images with a link to the photo on Instagram'),
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show image likes number'),
						'name' => 'PK_INSTA_LIKES',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show image comments number'),
						'name' => 'PK_INSTA_COMMENTS',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show image captions'),
						'name' => 'PK_INSTA_CAPTION',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),	
					array(
						'type' => 'switch',
						'label' => $this->l('Display images in carousel'),
						'desc' => $this->l('Use carousel or just a list of instagram images'),
						'name' => 'PK_INSTA_CAROUSEL',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),		
					array(
						'type' => 'switch',
						'label' => $this->l('Carousel autorotate'),
						'name' => 'PK_INSTA_AUTOSCROLL',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),	
					array(
						'type' => 'switch',
						'label' => $this->l('Light color of the text'),
						'name' => 'PK_INSTA_COLOR',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),	
					array(
						'type' => 'switch',
						'label' => $this->l('Display background for module'),
						'name' => 'PK_INSTA_BACKGROUND',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),
					array(
						'type' => 'file',
						'label' => $this->l('Background Image'),
						'name' => 'insta-bg',
						'value' => true
					),			
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$bkimg = "";
		if (file_exists(dirname(__FILE__).'/img/instabg_'.$this->context->shop->id.'.jpg'))
			$bkimg = ShopUrl::getBaseURI()."/modules".$this->name."/img/instabg_".$this->context->shop->id.".jpg";
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'pk_ig_submit';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'insta-bg' => $bkimg
		);
		return $helper->generateForm(array($fields_form_01, $fields_form_02));
	}
	
	public function getConfigFieldsValues()
	{
		return array(
			'PK_INSTA_API_CODE' => Tools::getValue('PK_INSTA_API_CODE', Configuration::get('PK_INSTA_API_CODE')),
			'PK_INSTA_AT' => Tools::getValue('PK_INSTA_AT', Configuration::get('PK_INSTA_AT')),
			'PK_INSTA_CONTENT_TYPE' => Tools::getValue('PK_INSTA_CONTENT_TYPE', Configuration::get('PK_INSTA_CONTENT_TYPE')),
			'PK_INSTA_HASHTAG' => Tools::getValue('PK_INSTA_HASHTAG', Configuration::get('PK_INSTA_HASHTAG')),
			'PK_INSTA_API_SECRET' => Tools::getValue('PK_INSTA_API_SECRET', Configuration::get('PK_INSTA_API_SECRET')),
			'PK_INSTA_API_CALLBACK' => Tools::getValue('PK_INSTA_API_CALLBACK', Configuration::get('PK_INSTA_API_CALLBACK')),
			'PK_INSTA_USERNAME' => Tools::getValue('PK_INSTA_USERNAME', Configuration::get('PK_INSTA_USERNAME')),
			'PK_INSTA_SORTBY' => Tools::getValue('PK_INSTA_SORTBY', Configuration::get('PK_INSTA_SORTBY')),
			'PK_INSTA_NUMBER' => Tools::getValue('PK_INSTA_NUMBER', Configuration::get('PK_INSTA_NUMBER')),
			'PK_INSTA_NUMBER_VIS' => Tools::getValue('PK_INSTA_NUMBER_VIS', Configuration::get('PK_INSTA_NUMBER_VIS')),
			'PK_INSTA_LINKS' => Tools::getValue('PK_INSTA_LINKS', Configuration::get('PK_INSTA_LINKS')),
			'PK_INSTA_LIKES' => Tools::getValue('PK_INSTA_LIKES', Configuration::get('PK_INSTA_LIKES')),
			'PK_INSTA_COMMENTS' => Tools::getValue('PK_INSTA_COMMENTS', Configuration::get('PK_INSTA_COMMENTS')),
			'PK_INSTA_CAPTION' => Tools::getValue('PK_INSTA_CAPTION', Configuration::get('PK_INSTA_CAPTION')),
			'PK_INSTA_CAROUSEL' => Tools::getValue('PK_INSTA_CAROUSEL', Configuration::get('PK_INSTA_CAROUSEL')),
			'PK_INSTA_BACKGROUND' => Tools::getValue('PK_INSTA_BACKGROUND', Configuration::get('PK_INSTA_BACKGROUND')),
			'PK_INSTA_AUTOSCROLL' => Tools::getValue('PK_INSTA_AUTOSCROLL', Configuration::get('PK_INSTA_AUTOSCROLL')),
			'PK_INSTA_COLOR' => Tools::getValue('PK_INSTA_COLOR', Configuration::get('PK_INSTA_COLOR'))
		);
	}

	public function getValuesFromDB()
	{

		return array(
			'PK_INSTA_API_CODE' => (Configuration::get('PK_INSTA_API_CODE') ? Configuration::get('PK_INSTA_API_CODE'): ""),
			'PK_INSTA_AT' => (Configuration::get('PK_INSTA_AT') ? Configuration::get('PK_INSTA_AT'): ""),
			'PK_INSTA_CONTENT_TYPE' => (Configuration::get('PK_INSTA_CONTENT_TYPE') ? Configuration::get('PK_INSTA_CONTENT_TYPE'):""),
			'PK_INSTA_HASHTAG' => (Configuration::get('PK_INSTA_HASHTAG') ? Configuration::get('PK_INSTA_HASHTAG'): ""),
			'PK_INSTA_API_SECRET' => (Configuration::get('PK_INSTA_API_SECRET') ? Configuration::get('PK_INSTA_API_SECRET'): ""),
			'PK_INSTA_API_CALLBACK' => (Configuration::get('PK_INSTA_API_CALLBACK') ? Configuration::get('PK_INSTA_API_CALLBACK'): ""),			
			'PK_INSTA_USERNAME' => (Configuration::get('PK_INSTA_USERNAME') ? Configuration::get('PK_INSTA_USERNAME'): ""),
			'PK_INSTA_SORTBY' => (Configuration::get('PK_INSTA_SORTBY') ? Configuration::get('PK_INSTA_SORTBY'): ""),
			'PK_INSTA_NUMBER' => (Configuration::get('PK_INSTA_NUMBER') ? Configuration::get('PK_INSTA_NUMBER'): ""),
			'PK_INSTA_NUMBER_VIS' => (Configuration::get('PK_INSTA_NUMBER_VIS') ? Configuration::get('PK_INSTA_NUMBER_VIS'): ""),
			'PK_INSTA_LINKS' => (Configuration::get('PK_INSTA_LINKS') ? Configuration::get('PK_INSTA_LINKS'): ""),
			'PK_INSTA_LIKES' => (Configuration::get('PK_INSTA_LIKES') ? Configuration::get('PK_INSTA_LIKES'): ""),
			'PK_INSTA_COMMENTS' => (Configuration::get('PK_INSTA_COMMENTS') ? Configuration::get('PK_INSTA_COMMENTS'): ""),
			'PK_INSTA_CAPTION' => (Configuration::get('PK_INSTA_CAPTION') ? Configuration::get('PK_INSTA_CAPTION'): ""),
			'PK_INSTA_CAROUSEL' => (Configuration::get('PK_INSTA_CAROUSEL') ? Configuration::get('PK_INSTA_CAROUSEL'): ""),
			'PK_INSTA_BACKGROUND' => (Configuration::get('PK_INSTA_BACKGROUND') ? Configuration::get('PK_INSTA_BACKGROUND'): 0),
			'PK_INSTA_AUTOSCROLL' => (Configuration::get('PK_INSTA_AUTOSCROLL') ? Configuration::get('PK_INSTA_AUTOSCROLL'): ""),
			'PK_INSTA_COLOR' => (Configuration::get('PK_INSTA_COLOR') ? Configuration::get('PK_INSTA_COLOR'): ""),
			'PK_INSTA_USERID' => $this->getuserid()	
		);
	}

	public function getuserid() {		

		if ( (Configuration::get('PK_INSTA_USERNAME') == false) || (Configuration::get('PK_INSTA_AT') == false) || (Configuration::get('PK_INSTA_USERNAME') == NULL) || (Configuration::get('PK_INSTA_AT') == NULL) )
			return false;
		$filecontent = file_get_contents('https://api.instagram.com/v1/users/search?q='.Configuration::get('PK_INSTA_USERNAME').'&access_token='.Configuration::get('PK_INSTA_AT'));
		if ($filecontent == false) {
			$userDetails = false;
		} else {
			$userDetails = json_decode($filecontent);
		}
		return $userDetails;

	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

}