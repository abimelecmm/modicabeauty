<?php

if (!defined('_PS_VERSION_'))
  exit;

class pk_bannercarousel extends Module {

	protected $maxImageSize = 1048576;

	public function __construct()
	    {
		    $this->name = 'pk_bannercarousel';
		    $this->tab = 'advertising_marketing';
		    $this->version = '1.5';
		    $this->author = 'promokit.eu';
		    $this->need_instance = 1;
			$this->secure_key = Tools::encrypt($this->name);
			$this->bootstrap = true;

		    parent::__construct();
	
		    $this->displayName = $this->l('Banner Carousel');
		    $this->description = $this->l('Usefull image carousel for advertising.');

		    // Paths
			$this->module_path 		= _PS_MODULE_DIR_.$this->name.'/';
			$this->admin_tpl_path 	= _PS_MODULE_DIR_.$this->name.'/views/templates/admin/';
			$this->front_tpl_path	= _PS_MODULE_DIR_.$this->name.'/views/templates/front/';
			$this->hooks_tpl_path	= _PS_MODULE_DIR_.$this->name.'/views/templates/hooks/';
	    }
	
	public function install()
	    {
			if (parent::install() && 
				$this->installDB() && 
				$this->registerHook('displayHome') && 
				$this->registerHook('hook_home_01') && 
                $this->registerHook('hook_home_02') && 
                $this->registerHook('hook_home_03') && 
                $this->registerHook('narrow_top') && 
                $this->registerHook('narrow_middle') && 
                $this->registerHook('narrow_bottom') && 
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
			//$image = Db::getInstance()->ExecuteS('SELECT image FROM `'._DB_PREFIX_.'pk_bannercarousel`');
	
			//foreach($image as $img)
			//	$this->_deleteImages($img['image']);
	
			if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_bannercarousel`') OR
	    		!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pk_bannercarousel_options`') OR
				!parent::uninstall())
				return false;
			return true;	
		}		

	public function installDb() {		
		$def_lid = Configuration::get('PS_LANG_DEFAULT');
		$def_liso = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
		$sid = (int)$this->context->shop->id;
		if (!file_exists(dirname(__FILE__).'/install.sql'))	return false;
		else if (!$sql = file_get_contents(dirname(__FILE__).'/install.sql')) return false;
		else {
			$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE', 'DEF_LANG_ISO', 'SID', 'LID'), array(_DB_PREFIX_, _MYSQL_ENGINE_, $def_liso['iso_code'], $sid, $def_lid), $sql);
			$sql = preg_split("/;\s*[\r\n]+/", $sql);
			foreach ($sql AS $query)				
				if($query)
					if(!Db::getInstance()->execute(trim($query)))
						return false;
			return true;
		}
	}
	
	public function getContent()
		{
			$this->smarty->assign('error', 0);
			$this->smarty->assign('confirmation', 0);

			if (Tools::isSubmit('submitMinicOptions')){
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
			$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'pk_bannercarousel_options`');
			$slides = array();
	
			foreach($activeLanguages as $lang)
				$slides[$lang['iso_code']] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'pk_bannercarousel` WHERE id_lang ='.$lang['id_lang'].' AND id_shop = '.$id_shop.' ORDER BY id_order ASC');
	
			$this->smarty->assign('slider', array(
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
					'front' => $options['front']
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
				'postAction' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=advertising_marketing&module_name='.$this->name.'',
				'sortUrl' => _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/ajax_'.$this->name.'.php?action=updateOrder&secure_key='.$this->secure_key,
				'id_shop' => (int)$this->context->shop->id
			));	

			$this->smarty->assign('minic', array(
				'admin_tpl_path' => $this->admin_tpl_path,
				'front_tpl_path' => $this->front_tpl_path,
				'hooks_tpl_path' => $this->hooks_tpl_path,

				'info' => array(
					'module'	=> $this->name,
	            	'name'      => Configuration::get('PS_SHOP_NAME'),
	        		'domain'    => Configuration::get('PS_SHOP_DOMAIN'),
	        		'email'     => Configuration::get('PS_SHOP_EMAIL'),
	        		'version'   => $this->version,
	            	'psVersion' => _PS_VERSION_,
	        		'server'    => $_SERVER['SERVER_SOFTWARE'],
	        		'php'       => phpversion(),
	        		'mysql' 	=> Db::getInstance()->getVersion(),
	        		'theme' 	=> _THEME_NAME_,
	        		'userInfo'  => $_SERVER['HTTP_USER_AGENT'],
	        		'today' 	=> date('Y-m-d'),
	        		'module'	=> $this->name,
	        		'context'	=> (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 0) ? 1 : ($this->context->shop->getTotalShops() != 1) ? $this->context->shop->getContext() : 1,
				)
			));

			return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
		}

	private function _handleOptions()
		{		
			$id_shop = (int)$this->context->shop->id;
			$effect = "";
			$current = '';
			if(Tools::getValue('nivo_current') != '')
				$current = implode(',', Tools::getValue('nivo_current'));

			$check = Db::getInstance()->ExecuteS('SELECT start_slide FROM `'._DB_PREFIX_.'pk_bannercarousel_options` WHERE id_shop = '.$id_shop.';');

			if (empty($check)) {

				if(!Db::getInstance()->Execute("INSERT INTO `"._DB_PREFIX_."pk_bannercarousel_options` (`id_shop`, `effect`, `current`, `slices`, `cols`, `rows`, `speed`, `pause`, `manual`, `hover`, `buttons`, `control`, `thumbnail`, `random`, `start_slide`, `single`, `width`, `height`, `front`) VALUES (".$id_shop.", 'sliceDown,sliceDownLeft,sliceUp,sliceUpLeft,sliceUpDown,sliceUpDownLeft,fold,slideInRight,slideInLeft,boxRandom,boxRain,boxRainReverse,boxRainGrow,boxRainGrowReverse,fade', '', 15, 8, 4, 800, 4000, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1);"))
					return false;
			}

			if(!Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'pk_bannercarousel_options` SET 
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
					front = "'.(int)Tools::getValue('front').'" 
				WHERE id_shop = '.$id_shop)) {					
					$this->smarty->assign('error', $this->l('An error occurred while saving data. I`m sure this is a DataBase error.'));
				return false;
			}


			$this->smarty->assign('confirmation', $this->l('Saved successfull.'));		
			return true;
		}
	
	private function _handleNewSlide()
		{
			$languages = Language::getLanguages(false);		
			$id_lang = (int)Tools::getValue('language');
			$lang = Language::getLanguage($id_lang);
			$id_shop = (int)$this->context->shop->id;
			$lastSlideID = Db::getInstance()->ExecuteS('SELECT id_slide, id_order FROM `'._DB_PREFIX_.'pk_bannercarousel` WHERE id_lang = '.$id_lang.' AND id_shop = '.$id_shop.' ORDER BY id_slide DESC LIMIT 1');
			$currentSlideID = ($lastSlideID) ? $lastSlideID[0]['id_slide']+1 : 1;
			$currentOrderID = ($lastSlideID) ? $lastSlideID[0]['id_order']+1 : 1;
		
			if(empty($_FILES['image']['name'])){
				$this->smarty->assign('error', $this->l('Image needed, please choose one.'));
				return false;
			}
			
			$image = $this->_resizer($_FILES['image'], Tools::getValue('imageName'));
		
			if(!$image)
				return false;
		
			$insert = Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'pk_bannercarousel` ( 
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
				$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
				return false;	
			}	
		
			$this->smarty->assign('confirmation', $this->l('New slide added successfull.'));
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
				UPDATE `'._DB_PREFIX_.'pk_bannercarousel` SET 
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
				$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
				return false;			
			}
		
			if(!empty($_FILES['newImage']['name']))
				$this->_deleteImages(Tools::getValue('oldImage'));
		
			$this->smarty->assign('confirmation', $this->l('Saved succsessfull.'));
		}
	
	public function _handleDeleteSlide()
		{
			$id_shop = (int)$this->context->shop->id;
			Db::getInstance()->delete(_DB_PREFIX_.'pk_bannercarousel', 'id_slide = '.(int)Tools::getValue('slideId'));
		
			Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'pk_bannercarousel` 
				SET id_order = id_order-1 
				WHERE (
					id_order > '.Tools::getValue('orderId').' AND 
					lang_iso = "'.Tools::getValue('slideIso').'" AND 
					id_shop = '.$id_shop.')
			');
	
			$this->_deleteImages(Tools::getValue('oldImage'));
			$this->smarty->assign('confirmation', $this->l('Deleted succsessfull.'));
		}
	
	private function _resizer($image, $newName = NULL)
		{
			$path = '../modules/'.$this->name.'/uploads/';
			$pathThumb = '../modules/'.$this->name.'/uploads/thumbs/';

			// Check if thumb dir is exists and create if not
			if(!file_exists($pathThumb) && !is_dir($pathThumb))
				mkdir($pathThumb);

			// Replace whitesapce
			$imageName = explode('.', str_replace(' ', '_', $image['name']));
			$name = $imageName[0].'.'.$imageName[1];
			// Replace unwanted chars
			if($newName){
				$unwanted_chars = array(
					'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                    'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                    'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 
                    'ş'=>'s', 'ü'=>'u',
                );
				$nameB = strtr( $newName, $unwanted_chars );
				$name = str_replace(' ', '_', $nameB).'.'.$imageName[1];
			}

			// if new name is empty and picture is exists create a new name
			if(file_exists($path.$name) && $newName == NULL)
				$name = $imageName[0].date('-i-s').'.'.$imageName[1];

			// Check image size and format
			if($error = ImageManager::validateUpload($image, $this->maxImageSize)){
				$this->smarty->assign('error', $error);
				return;
			}

			// Move image
			if(!ImageManager::resize($image['tmp_name'], dirname(__FILE__).'/uploads/'.$name)){
				$this->smarty->assign('error', $this->l('An error occured during the upload, please check the permissions.'));
				unlink($tmpName);
				return;
			}			

			// Create thumbnail for slider
			$imgSize = getimagesize($path.$name);
			if($imgSize[0] >= $imgSize[1]){
				// Resize based on width
				$imgWidth = 50;
				$imgHeight = ($imgSize[1]/100)*(5000/$imgSize[0]);
			} else {
				// Resize based on height
				$imgHeight = 50;
				$imgWidth = ($imgSize[0]/100)*(5000/$imgSize[1]);
			}

			// Actual resize
			if(!ImageManager::resize($path.$name, $pathThumb.$name, (int)$imgWidth, (int)$imgHeight)){
				$this->smarty->assign('error', $this->l('An error occurred during the image upload. Please check the upload directory permission in the module folder.'));
				return;
			}

			return $name;
		}	
	
	private function _deleteImages($image)
		{
			$path = '../modules/'.$this->name.'/uploads/';
			$pathThumb = '../modules/'.$this->name.'/uploads/thumbs/';		
			
			if(file_exists($path.$image)){
				if(!unlink($path.$image) || !unlink($pathThumb.$image))
					$this->smarty->assign('error', $this->l('Cant delete images, please check permissions!'));			
			}else{
				$this->smarty->assign('error', $this->l('Image doesn`t exists!'));
			}
		}
	
	public function hookDisplayBackOfficeHeader()
	{
		// Check if module is loaded
		if (Tools::getValue('configure') != $this->name)
			return false;

		// CSS
		$this->context->controller->addCSS($this->_path.'views/css/elusive-icons/elusive-webfont.css');
		$this->context->controller->addCSS($this->_path.'views/js/plugins/tipsy/tipsy.css');
		$this->context->controller->addCSS($this->_path.'views/css/style.css');
		$this->context->controller->addCSS($this->_path.'views/css/admin.css');
		// JS
		$this->context->controller->addJquery();
		$this->context->controller->addJS($this->_path.'views/js/plugins/jquery.transit/jquery.transit-0.9.9.min.js');
		$this->context->controller->addJS($this->_path.'views/js/plugins/tipsy/jquery.tipsy.js');
		$this->context->controller->addJS($this->_path.'views/js/jquery-ui-1.9.0.custom.min.js');
		$this->context->controller->addJS($this->_path.'views/js/admin.js');

	}

	public function hookDisplayHeader() {
		if ($this->context->controller->php_self == "index") {
	 		$status_home = $this->getModuleState("displayHome");
	 		$status_top = $this->getModuleState("narrow_top");
	 		$footer_top = $this->getModuleState("footer_top");
			//if ($status_home == 1 || $status_top == 1 || $footer_top == 1)
				$this->context->controller->addCSS($this->_path.'views/js/plugins/jquery.bxslider/jquery.bxslider.css');
		}
	}

	public function getmoduledata($position) {
		$id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$id_shop = $this->context->shop->id;

		$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'pk_bannercarousel_options` WHERE id_shop = '.$id_shop);

		if($options['single'] == 1)
			$id_lang = $this->context->language->id;
		$slides = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'pk_bannercarousel` WHERE (id_lang ='.$id_lang.' AND id_shop = '.$id_shop.' AND active = 1) ORDER BY id_order ASC');					

		$this->smarty->assign('slides', $slides);		
		$this->smarty->assign('minicSlider', array(
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
				'front' => $options['front']
			),
			'path' => array(
				'images' => '//'.Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/uploads/',
				'thumbs' => '//'.Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/uploads/thumbs/'		
			),
			'position' => $position
		));
	}
 	
 	public function hookdisplayHome($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("displayHome");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
		 	}
		}
	}

	public function hooknarrow_top($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function hooknarrow_middle($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function hooknarrow_bottom($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function hookhook_home_01($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function hookhook_home_02($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function hookhook_home_03($position = '') {
 		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_03ss");
			if ($status == 1) {
				$this->getmoduledata($position);
		 		return $this->display(__FILE__, 'views/templates/front/front-top.tpl');
		 	}
		}
	}

	public function getModuleState($hook)	{  // get module state from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}
}