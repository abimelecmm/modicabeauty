<?php

if (!defined('_PS_VERSION_'))
  exit;

class pk_promominicslider extends Module
{
	protected $maxImageSize = 1048576;

	public function __construct()
	    {
		    $this->name = 'pk_promominicslider';
		    $this->tab = 'advertising_marketing';
		    $this->version = '3.3';
		    $this->author = 'promokit.eu';
		    $this->need_instance = 1;
			$this->secure_key = Tools::encrypt($this->name);
			$this->bootstrap = true;
	
		    parent::__construct();
	
		    $this->displayName = $this->l('Promo Minic slider');
		    $this->description = $this->l('Powerfull image slider for advertising with promo products section.');
	    }
	
	private function installDB()
		{
	
			Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_slider`');
    		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_options`');
	
			if (!Db::getInstance()->Execute('
				CREATE TABLE `'._DB_PREFIX_.'minic_slider` (
					`id_slide` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`id_lang` int(10) unsigned NOT NULL,
					`id_order` int(10) unsigned NOT NULL,
					`lang_iso` VARCHAR(5),
					`title` VARCHAR(100),
					`url` VARCHAR(100),
					`target` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`image` VARCHAR(100),
					`alt` VARCHAR(100),
					`caption` VARCHAR(300),
					`active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					PRIMARY KEY (`id_slide`, `id_shop`)
			    ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;'))
				return false;
	
			if (!Db::getInstance()->Execute('
				CREATE TABLE `'._DB_PREFIX_.'minic_options` (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`effect` VARCHAR(300),
					`current` VARCHAR(300),
					`slices` int(3) NOT NULL DEFAULT \'8\',
					`cols` int(3) NOT NULL DEFAULT \'8\',
					`rows` int(3) NOT NULL DEFAULT \'8\',
					`speed` int(4) NOT NULL DEFAULT \'800\',
					`pause` int(4) NOT NULL DEFAULT \'3000\',
					`manual` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`hover` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`buttons` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`control` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`thumbnail` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`price_reduction` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`random` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`start_slide` int(2) unsigned NOT NULL DEFAULT 0,
					`single` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`width` int(4) unsigned NOT NULL DEFAULT \'0\',
					`height` int(4) unsigned NOT NULL DEFAULT \'0\',
					`front` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`products_type` int(2) unsigned NOT NULL DEFAULT 0,
					`img_view` int(2) unsigned NOT NULL DEFAULT 0,
					PRIMARY KEY (`id`, `id_shop`)
		        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;'))
				return false;	
			return true;
		}
	
	private function insertOptions()
		{
			if (!Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'minic_options` (
					`id_shop`, `effect`
				) VALUES (
					1,
					"random,simpleFade, curtainTopLeft, curtainTopRight, curtainBottomLeft, curtainBottomRight, curtainSliceLeft, curtainSliceRight, blindCurtainTopLeft, blindCurtainTopRight, blindCurtainBottomLeft, blindCurtainBottomRight, blindCurtainSliceBottom, blindCurtainSliceTop, stampede, mosaic, mosaicReverse, mosaicRandom, mosaicSpiral, mosaicSpiralReverse, topLeftBottomRight, bottomRightTopLeft, bottomLeftTopRight, bottomLeftTopRight");'))
				return false;	

			if (!Db::getInstance()->Execute("
				INSERT INTO `"._DB_PREFIX_."minic_slider` (`id_slide`, `id_shop`, `id_lang`, `id_order`, `lang_iso`, `title`, `url`, `target`, `image`, `alt`, `caption`, `active`) VALUES
					(1, 1, 1, 1, 'en', 'Slide01', '#', 0, 'promo01.jpg', '', '', 1),
					(2, 1, 1, 2, 'en', 'Slide02', '#', 0, 'promo02.jpg', '', '', 1),
					(3, 1, 1, 3, 'en', 'Slide03', '#', 0, 'promo03.jpg', '', '', 1);"))
				return false;		
			
			return true;
		}
	
	public function install()
	    {
			if (
				parent::install() && 
				$this->installDB() && 
				$this->insertOptions() && 
				$this->registerHook('hook_home_01') && 
				$this->registerHook('hook_home_02') && 
				$this->registerHook('hook_home_03') && 
				$this->registerHook('hook_home_04') && 
				$this->registerHook('hook_home_05') && 
				$this->registerHook('hook_home_06') && 
				$this->registerHook('hook_home_07') && 
				$this->registerHook('displayHeader') && 
				$this->registerHook('displayBackOfficeHeader')){
				return true;
			}else{
				$this->uninstall();
				return false;
			}
		}
	
	public function uninstall()
		{
			//$image = Db::getInstance()->ExecuteS('SELECT image FROM `'._DB_PREFIX_.'minic_slider`');
	
			foreach($image as $img){
				$this->_deleteImages($img['image']);
			}
	
			if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'promo_minic_slider`') OR
	    		!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'promo_minic_options`') OR
				!parent::uninstall())
				return false;
			return true;	
		}		
	
	public function getContent()
		{
			if (Tools::isSubmit('submitOptions')){
				$this->_handleOptions();
			} elseif (Tools::isSubmit('submitNewSlide')){
				$this->_handleNewSlide();
			} elseif (Tools::isSubmit('editSlide')){
				$this->_handleEditSlide();
			} elseif (Tools::isSubmit('deleteSlide')) {
				$this->_handleDeleteSlide();
			}
			return $this->_displayForm();
		}
	
	private function _displayForm()
		{	
			$defaultLanguage = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
			$activeLanguages = Language::getLanguages(true);
			$allLanguages = Language::getLanguages(false);
			$id_shop = (int)$this->context->shop->id;
			$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'minic_options`');
			$slides = array();
		//print_r($activeLanguages);
			foreach($activeLanguages as $lang){
				$slides[$lang['iso_code']] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider` WHERE id_lang ='.$lang['id_lang'].' AND id_shop = '.$id_shop.' ORDER BY id_order ASC');
			}
			$this->context->smarty->assign('slider', array(
				'options' => array(
					'effect' => (!empty($options['effect'])) ? explode(',', $options['effect']) : NULL,
					'current' => (!empty($options['current'])) ? explode(',', $options['current']) : NULL,
					'slices' => $options['slices'],
					'cols' => $options['cols'],
					'rows' => $options['rows'],
					'speed' => $options['speed'],
					'pause' => $options['pause'],
					'manual' => $options['manual'],
					'hover' => $options['hover'],
					'buttons' => $options['buttons'],
					'control' => $options['control'],
					'thumbnail' => $options['thumbnail'],
					'random' => $options['random'],
					'startSlide' => $options['start_slide'],
					'single' => $options['single'],
					'width' => $options['width'],
					'height' => $options['height'],
					'front' => $options['front'],
					'products_type' => $options['products_type'],
					'img_view' => $options['img_view'],
					'price_reduction' => $options['price_reduction']
				),
				'slides' => $slides,
				'lang' => array(
					'default' => $defaultLanguage,
					'default_iso' => $defaultLanguage,
					'default_name' => $defaultLanguage,
					'all' => $activeLanguages,
					'lang_dir' => _THEME_LANG_DIR_,
					'user' => $this->context->language->id
				),				
				'tpl' => array(
                	'options' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/admin-options.tpl',
                	'new' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/admin-new.tpl',
                	'slides' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/admin-slides.tpl',
                	'feedback' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/admin-feedback.tpl',
                	'bug' => _PS_MODULE_DIR_.$this->name.'/views/templates/admin/admin-bug.tpl'
            	),
            	'info' => array(
            		'name' => Configuration::get('PS_SHOP_NAME'),
            		'domain' => Configuration::get('PS_SHOP_DOMAIN'),
            		'email' => Configuration::get('PS_SHOP_EMAIL'),
            		'version' => $this->version,
                	'psVersion' => _PS_VERSION_,
            		'server' => $_SERVER['SERVER_SOFTWARE'],
            		'php' =>phpversion(),
            		'mysql' => Db::getInstance()->getVersion(),
            		'theme' => _THEME_NAME_,
            		'userInfo' => $_SERVER['HTTP_USER_AGENT']
        		),
				'postAction' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=advertising_marketing='.$this->name.'',
				'sortUrl' => _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/ajax_'.$this->name.'.php?action=updateOrder&secure_key='.$this->secure_key,
				'id_shop' => (int)$this->context->shop->id
			));	
			
			$this->context->controller->addCSS($this->_path . 'views/css/tipsy.css');
			$this->context->controller->addCSS($this->_path . 'views/css/style.css');
			$this->context->controller->addJS($this->_path . 'views/js/fn.ghostText.min.js');
			$this->context->controller->addJS($this->_path . 'views/js/jquery.tipsy.js');
			$this->context->controller->addJS($this->_path . 'views/js/minicFeedback.js');
			$this->context->controller->addJS($this->_path . 'views/js/minicSlider.js');
			$this->context->controller->addJS(_PS_ROOT_DIR_.'js/jquery/ui/jquery.ui.sortable.min.js');

			return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
		}

	private function _handleOptions()
		{		
			$id_shop = (int)$this->context->shop->id;
			$effect = implode(',', Tools::getValue('nivo_effect'));
			$current = '';
			if(Tools::getValue('nivo_current') != '')
				$current = implode(',', Tools::getValue('nivo_current'));
		
			if(!Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'minic_options` SET 
					effect = "'.$effect.'",
					current = "'.$current.'",
					slices = "'.(int)Tools::getValue('slices').'",
					cols = "'.(int)Tools::getValue('cols').'",
					rows = "'.(int)Tools::getValue('rows').'",
					speed = "'.(int)Tools::getValue('speed').'",
					pause = "'.(int)Tools::getValue('pause').'",
					manual = "'.(int)Tools::getValue('manual').'",
					hover = "'.(int)Tools::getValue('hover').'",
					buttons = "'.(int)Tools::getValue('buttons').'",
					control = "'.(int)Tools::getValue('control').'",
					thumbnail = "'.(int)Tools::getValue('thumbnail').'",					
					random = "'.(int)Tools::getValue('random').'",
					start_slide = "'.(int)Tools::getValue('startSlide').'",
					single = "'.(int)Tools::getValue('single').'",
					width = "'.(int)Tools::getValue('width').'",
					height = "'.(int)Tools::getValue('height').'",
					front = "'.(int)Tools::getValue('front').'",
					products_type = "'.(int)Tools::getValue('products_type').'",
					img_view = "'.(int)Tools::getValue('img_view').'",
					price_reduction = "'.(int)Tools::getValue('price_reduction').'"				
				WHERE id = 1
					')){
				$this->context->smarty->assign('error', $this->l('An error occurred while saving data. I`m sure this is a DataBase error.'));
				return false;
			}
			parent::_clearCache('/views/templates/front/front.tpl');
			return true;
		}
	
	private function _handleNewSlide()
		{
			$languages = Language::getLanguages(false);		
			$id_lang = (int)Tools::getValue('language');
			$lang = Language::getLanguage($id_lang);
			$id_shop = (int)$this->context->shop->id;
			$lastSlideID = Db::getInstance()->ExecuteS('SELECT id_slide, id_order FROM `'._DB_PREFIX_.'minic_slider` WHERE id_lang = '.$id_lang.' AND id_shop = '.$id_shop.' ORDER BY id_slide DESC LIMIT 1');
			$currentSlideID = ($lastSlideID) ? $lastSlideID[0]['id_slide']+1 : 1;
			$currentOrderID = ($lastSlideID) ? $lastSlideID[0]['id_order']+1 : 1 ;
		
			if(empty($_FILES['image']['name'])){
				$this->context->smarty->assign('error', $this->l('Image needed, please choose one.'));
				return false;
			}
			
			$image = $this->_resizer($_FILES['image'], Tools::getValue('imageName'));
		
			if(!$image)
				return false;
		
			$insert = Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'minic_slider` ( 
					id_shop, id_lang, id_order, lang_iso, title, url, target, image, alt, caption 
				) VALUES ( 
					"'.$id_shop.'",
					"'.(int)Tools::getValue('language').'",
					"'.$currentOrderID.'",
					"'.$lang['iso_code'].'",
					"'.Tools::getValue('title').'",
					"'.Tools::getValue('url').'",
					"'.(int)Tools::getValue('target').'",
					"'.$image.'",
					"'.Tools::getValue('alt').'",
					"'.pSQL(Tools::getValue('caption'), true).'")
				');

			if(!$insert){
				$this->_deleteImages($image);
				$this->context->smarty->assign('error', $this->l('An error occured while saving data.'));	
				return false;	
			}	
			parent::_clearCache('/views/templates/front/front.tpl');
			$this->context->smarty->assign('confirmation', $this->l('New slide added successfull.'));
		}
	private function _handleEditSlide()
		{	
			$langIso = Tools::getValue('slideIso');
			$newImage = '';
		
			if(!empty($_FILES['newImage']['name'])){
				$image = $this->_resizer($_FILES['newImage']);
				if(empty($image))
					return false;
				$newImage = 'image = "'.$image.'",';
			}
		
			$update = Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'minic_slider` SET 
					title = "'.Tools::getValue('title').'",
					url = "'.Tools::getValue('url').'",
					target = "'.(int)Tools::getValue('target').'",
					'.$newImage.'
					alt = "'.Tools::getValue('alt').'",
					caption = "'.pSQL(Tools::getValue('caption'), true).'",
					active = "'.(int)Tools::getValue('isActive').'"
				WHERE id_slide = '.(int)Tools::getValue('slideId'));
		
			if(!$update){
				$this->_deleteImages(Tools::getValue('image'));			
				$this->context->smarty->assign('error', $this->l('An error occured while saving data.'));	
				return false;			
			}
		
			if(!empty($_FILES['newImage']['name'])){
				$this->_deleteImages(Tools::getValue('oldImage'));
			}
			parent::_clearCache('/views/templates/front/front.tpl');
			$this->context->smarty->assign('confirmation', $this->l('Saved succsessfull.'));
		}
	
	public function _handleDeleteSlide()
		{
			$id_shop = (int)$this->context->shop->id;
			Db::getInstance()->delete(_DB_PREFIX_.'minic_slider', 'id_slide = '.(int)Tools::getValue('slideId'));
		
			if(Db::getInstance()->Affected_Rows() == 1){
				Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'minic_slider` 
					SET id_order = id_order-1 
					WHERE (
						id_order > '.Tools::getValue('orderId').' AND 
						lang_iso = "'.Tools::getValue('slideIso').'" AND 
						id_shop = '.$id_shop.')
				');
		
				$this->_deleteImages(Tools::getValue('oldImage'));
				$this->context->smarty->assign('confirmation', $this->l('Deleted succsessfull.'));
			}else{
				$this->context->smarty->assign('error', $this->l('Cant delete slide data from database.'));
			}
		}
	
	private function _resizer($image, $newName = NULL)
		{
			$path = '../modules/'.$this->name.'/uploads/';
			$pathThumb = '../modules/'.$this->name.'/uploads/thumbs/';	
			$imageName = explode('.', str_replace(' ', '_', $image['name']));
			$name = $imageName[0].'.'.$imageName[1];
			if($newName)
				$name = str_replace(' ', '_', $newName).'.'.$imageName[1];
			
			Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
		
			if(file_exists($path.$name) && $newName == NULL){
				$name = $imageName[0].date('-i-s').'.'.$imageName[1];
			}
			if (!ImageManager::isRealImage($image["tmp_name"], $image["type"])) {
				$this->context->smarty->assign('error', $this->l('Image format not recognized, allowed formats are: .gif, .jpg, .png'));
				return false;
			}else{
				if (ImageManager::validateUpload($image, $this->maxImageSize)){
					$this->context->smarty->assign('error', $this->l('Image is to large: ').$image['size'].' kb '.$this->l('Maximum allowed: ').$this->maxImageSize.' kb');
					return false;
				}else{
					if (!$tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS') OR !move_uploaded_file($image['tmp_name'], $tmpName)){
						$this->context->smarty->assign('error', $this->l('An error occurred while moving files. Please check permissions.'));
						return false;
						unlink($tmpName);
					}else{
						if (!ImageManager::resize($tmpName, $pathThumb.'admin_'.$name,250)){
							$this->context->smarty->assign('error', $this->l('An error occurred while creating thumbnails.'));
							return false;
							unlink($tmpName);
						}else{
							if (!ImageManager::resize($tmpName, $pathThumb.'front_'.$name,50)){
								$this->context->smarty->assign('error', $this->l('An error occurred while creating thumbnails.'));
								return false;
								unlink($tmpName);
							}else{
								if (!rename($tmpName, $path.$name)){
									$this->context->smarty->assign('error', $this->l('An error occurred while renaming files.'));
									unlink($tmpName);
								}else{
									$image = $name;
									chmod($path.$name, 0644);
									return $image;
								}
							}
						}
					}
				}
			}
		}	
	
	private function _deleteImages($image) {
			$path = '../modules/'.$this->name.'/uploads/';
			$pathThumb = '../modules/'.$this->name.'/uploads/thumbs/';	
			
			if(file_exists($path.$image)){
				if(!unlink($path.$image) || !unlink($pathThumb.'admin_'.$image) || !unlink($pathThumb.'front_'.$image))
					$this->context->smarty->assign('error', $this->l('Cant delete images, please check permissions!'));			
			}else{
				//$this->context->smarty->assign('error', $this->l('Image doesn`t exists!'));
			}
		}

	public function getData($position) {

		$defLanguages = (int)Configuration::get('PS_LANG_DEFAULT');
		$activeLanguages = Language::getLanguages(true);
		$allLanguages = Language::getLanguages(false);
		$defLangIso = $allLanguages[$defLanguages-1]['iso_code'];
		$id_shop = (int)$this->context->shop->id;
		$idLng = $this->context->language->id;

	    $shopUrl = Tools::getShopProtocol().$_SERVER['HTTP_HOST']. __PS_BASE_URI__;
		$themeUrl = $shopUrl.'themes/'._THEME_NAME_.'/';
		$moduleUrl = $shopUrl."modules/".$this->name."/uploads/";

		//$idLang = (int)($position['cookie']->id_lang);
		$category = new Category(Context::getContext()->shop->getCategory(), $idLng);
		$nb = 4;

		$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
   		$orderWay = Tools::getProductsOrder('way', Tools::getValue('orderway'));

		$new = Product::getNewProducts($idLng, 0, $nb); /*get new products*/		
		$featured = $category->getProducts($idLng, 1, ($nb ? $nb : 4));	 /*		get featured products	*/
		$specials = Product::getPricesDrop($idLng, 0, $nb, false, $orderBy, $orderWay); /* get special products */		

		$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'minic_options`');
		
		switch ($options['products_type']) {
			case 0:
			  $products = $new;
			  break;
			case 1:
			  $products = $featured;
			  break;
			case 2:
			  $products = $specials;
			  break;
			default:
			  $products = $specials;
		}
		
		// $this->context->smarty->assign('defaultLangIso', $defLangIso);			
		$tpl = 'single.tpl';
		if($options['single'] == 1)
			$tpl = 'multiple.tpl';
		$width = array();
		$slides = array();

		if($options['single'] == 0){
			$slides[$defLangIso] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider` WHERE (id_lang ='.$defLanguages.' AND id_shop = '.$id_shop.' AND active = 1) ORDER BY id_order ASC');			
		}else{
			foreach ($activeLanguages as $lang) {
				$slides[$lang['iso_code']] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider` WHERE (id_lang ='.$lang['id_lang'].' AND id_shop = '.$id_shop.' AND active = 1) ORDER BY id_order ASC');	
			}
		}			

		$this->context->smarty->assign('slides', $slides);	
		$this->smarty->assign('def_iso', $defLangIso);	
		$this->smarty->assign('currentLang', Language::getIsoById($idLng));	
		$this->context->smarty->assign('minicSlider', array(
			'options' => array(
				'current' => $options['current'],
				'slices' => $options['slices'],
				'cols' => $options['cols'],
				'rows' => $options['rows'],
				'speed' => $options['speed'],
				'pause' => $options['pause'],
				'manual' => $options['manual'],
				'hover' => $options['hover'],
				'buttons' => $options['buttons'],
				'control' => $options['control'],
				'thumbnail' => $options['thumbnail'],
				'random' => $options['random'],
				'startSlide' => $options['start_slide'],
				'single' => $options['single'],
				'width' => $options['width'],
				'height' => $options['height'],
				'front' => $options['front'],
				'products_type' => $options['products_type'],
				'img_view' => $options['img_view'],	
				'price_reduction' => $options['price_reduction']				
			),
			'path' => array(
				'images' => $moduleUrl,
				'thumbs' => $moduleUrl.'thumbs/front_',
				'mod_url' => $moduleUrl
			),
			'tpl' => _PS_MODULE_DIR_.$this->name.'/views/templates/front/'.$tpl,
			'position' => $position,
			'promo_products' => $products				
		));
	}
	
	public function hookDisplayHeader() {
		$this->context->controller->addCSS($this->_path.'views/css/camera.css');
		$this->context->controller->addJS($this->_path.'views/js/camera.js');
	}

	public function hookHook_home_01($params)
	{
		$s = $this->getModuleState("hook_home_01");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}
 	
 	public function hookHook_home_02($position = '') {
 		$s = $this->getModuleState("hook_home_02");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}

	public function hookHook_home_03($params)
	{
		$s = $this->getModuleState("hook_home_03");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookHook_home_04($params)
	{
		$s = $this->getModuleState("hook_home_04");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookHook_home_05($params)
	{
		$s = $this->getModuleState("hook_home_05");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}	
	public function hookHook_home_06($params)
	{
		$s = $this->getModuleState("hook_home_06");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}	
	public function hookHook_home_07($params)
	{
		$s = $this->getModuleState("hook_home_07");
 		if ($s == 1) {
 			if (!$this->isCached('/views/templates/front/front.tpl', $this->getCacheId($this->name))) {
		 		$this->getData($position);}
		 	return $this->display(__FILE__, 'views/templates/front/front.tpl', $this->getCacheId($this->name));
	 	}
	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

	protected function getCacheId($name = null) {
		return parent::getCacheId($name.'|'.date('Ymd'));
	}
}	

?>