<?php

if (!defined('_PS_VERSION_'))
  exit;

class pk_sequenceminicslider extends Module
{
	protected $maxImageSize = 1048576;

	public function __construct()
	    {
		    $this->name = 'pk_sequenceminicslider';
		    $this->tab = 'advertising_marketing';
		    $this->version = '3.4';
		    $this->author = 'promokit.eu';
		    $this->need_instance = 1;
			$this->secure_key = Tools::encrypt($this->name);
			$this->bootstrap = true;
	
		    parent::__construct();
	
		    $this->displayName = $this->l('Sequence Slider');
		    $this->description = $this->l('Powerfull image slider for advertising.');
	    }
	
	private function installDB()
		{
	
			Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_slider_adv`');
    		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_options_adv`');
	
			if (!Db::getInstance()->Execute('
				CREATE TABLE `'._DB_PREFIX_.'minic_slider_adv` (
					`id_slide` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`id_lang` int(10) unsigned NOT NULL,
					`id_order` int(10) unsigned NOT NULL,
					`lang_iso` VARCHAR(5),
					`title` VARCHAR(100),
					`url` VARCHAR(100),
					`target` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`image` VARCHAR(100),
					`subimages` TEXT(255),
					`subimages_sett` TEXT(255),
					`slide_text_sett` TEXT(255),					
					`text_width` VARCHAR(5),
					`alt` VARCHAR(100),
					`caption` VARCHAR(300),
					`active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					PRIMARY KEY (`id_slide`, `id_shop`)
			    ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;'))
				return false;
	
			if (!Db::getInstance()->Execute('
				CREATE TABLE `'._DB_PREFIX_.'minic_options_adv` (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`effect` VARCHAR(300),
					`current` VARCHAR(300),
					`slices` int(3) NOT NULL DEFAULT \'15\',
					`cols` int(3) NOT NULL DEFAULT \'8\',
					`rows` int(3) NOT NULL DEFAULT \'4\',
					`speed` int(4) NOT NULL DEFAULT \'500\',
					`pause` int(4) NOT NULL DEFAULT \'3000\',
					`manual` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`hover` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`buttons` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`control` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`thumbnail` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`random` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`start_slide` int(2) unsigned NOT NULL DEFAULT 0,
					`single` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					`width` int(4) unsigned NOT NULL DEFAULT \'0\',
					`height` int(4) unsigned NOT NULL DEFAULT \'0\',
					`front` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					PRIMARY KEY (`id`, `id_shop`)
		        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;'))
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
				$this->registerHook('wide_top') && 
				$this->registerHook('displayHeader')){
				return true;
			}else{
				$this->uninstall();
				return false;
			}
		}
	
	public function uninstall()
		{
			$image = Db::getInstance()->ExecuteS('SELECT image FROM `'._DB_PREFIX_.'minic_slider_adv`');
			
			foreach($image as $img){
				$this->_deleteImages($img['image']);
			}
	
			if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_slider_adv`') OR
	    		!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'minic_options_adv`') OR
				!parent::uninstall())
				return false;
			return true;	
		}
	private function insertOptions()
		{
			$id_shop = (int)$this->context->shop->id;
			$iso = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
			$activeLanguages = Language::getLanguages(true);

			if (!Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'minic_options_adv` (
					`id_shop`, `effect`, `speed`, `pause`, `manual`, `hover`, `buttons`, `control`, `thumbnail`, `random`, `start_slide`, `single`, `width`, `height`, `front`
				) VALUES (
					1,
					"sliceDown,sliceDownLeft,sliceUp,sliceUpLeft,sliceUpDown,sliceUpDownLeft,fold,slideInRight,slideInLeft,boxRandom,boxRain,boxRainReverse,boxRainGrow,boxRainGrowReverse,fade", 500, 3000, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0);'))
				return false;
			
			foreach($activeLanguages as $lang) {
				$sql = "INSERT INTO `"._DB_PREFIX_."minic_slider_adv` (`id_shop`, `id_lang`, `id_order`, `lang_iso`, `title`, `url`, `target`, `image`, `subimages`, `subimages_sett`, `slide_text_sett`, `text_width`, `alt`, `caption`, `active`) VALUES
				(".$id_shop.", ".$lang['id_lang'].", 1, '".$lang['iso_code']."', '100%<h4>Fully Responsive</h4><h5>& Adaptive Layout</h5>', '', 0, 'slide01_bg.png', 'slide01_item01.png,slide01_item02.png,slide01_item03.png,slide01_item04.png,slide01_item05.png', '0{before[duration:.5,rotation:0,top:-110,left:16,opacity:1]in[duration:.5,rotation:0,top:15,left:16,opacity:1]after[duration:.5,rotation:0,top:-110,left:16,opacity:1]}1{before[duration:.5,rotation:0,top:74,left:15,opacity:0]in[duration:.5,rotation:0,top:74,left:15,opacity:1]after[duration:.5,rotation:0,top:74,left:15,opacity:0]}2{before[duration:.7,rotation:0,top:142,left:8,opacity:1]in[duration:.7,rotation:0,top:41,left:8,opacity:1]after[duration:.7,rotation:0,top:142,left:8,opacity:1]}3{before[duration:.8,rotation:180,top:52,left:-31,opacity:1]in[duration:.8,rotation:0,top:75,left:2,opacity:1]after[duration:.8,rotation:180,top:52,left:-31,opacity:1]}4{before[duration:1,rotation:180,top:132,left:61,opacity:1]in[duration:1,rotation:0,top:61,left:51,opacity:1]after[duration:1,rotation:180,top:132,left:61,opacity:1]}', 'before[duration:0,rotation:0,top:17,left:65,opacity:0]in[duration:0,rotation:0,top:17,left:65,opacity:1]after[duration:0,rotation:0,top:17,left:65,opacity:0]', '34', '', '“Alysum” – is a great solution for small and medium businesses. Due to compact, modern and stylish design as well as “Theme settings module” embedded tool.', 1),
				(".$id_shop.", ".$lang['id_lang'].", 2, '".$lang['iso_code']."', '<h4>Unlimited Color Options</h4><h5>Over 500 Google Fonts</h5>', '', 0, 'slide02_bg.png', 'slide02_item01.png,slide02_item02.png,slide02_item03.png,slide02_item04.png,slide02_item05.png,slide02_item06.png,slide02_item05.png', '0{before[duration:.5,rotation:90,top:46,left:-51,opacity:1]in[duration:.5,rotation:0,top:46,left:1,opacity:1]after[duration:.5,rotation:0,top:-87,left:1,opacity:1]}1{before[duration:.6,rotation:180,top:26,left:-51,opacity:1]in[duration:.6,rotation:0,top:26,left:0,opacity:1]after[duration:.6,rotation:0,top:-87,left:0,opacity:1]}2{before[duration:.5,rotation:200,top:12,left:-51,opacity:1]in[duration:.5,rotation:0,top:12,left:10,opacity:1]after[duration:.5,rotation:0,top:-87,left:10,opacity:1]}3{before[duration:.5,rotation:250,top:17,left:-51,opacity:1]in[duration:.5,rotation:0,top:17,left:19,opacity:1]after[duration:.5,rotation:0,top:-87,left:19,opacity:1]}4{before[duration:.5,rotation:270,top:27,left:-51,opacity:1]in[duration:.5,rotation:0,top:27,left:25,opacity:1]after[duration:.5,rotation:0,top:-87,left:25,opacity:1]}5{before[duration:.5,rotation:300,top:37,left:-51,opacity:1]in[duration:.5,rotation:0,top:37,left:36,opacity:1]after[duration:.5,rotation:0,top:-87,left:36,opacity:1]}6{before[duration:.5,rotation:200,top:46,left:-51,opacity:1]in[duration:.5,rotation:0,top:46,left:46,opacity:1]after[duration:.5,rotation:0,top:-87,left:46,opacity:1]}', 'before[duration:0,rotation:0,top:17,left:65,opacity:0]in[duration:0,rotation:0,top:17,left:65,opacity:1]after[duration:0,rotation:0,top:17,left:65,opacity:0]', '34', '', 'You can create your own style for your e-shop without any knowledge of WEB technologies. It will take less than one minute.', 1),
				(".$id_shop.", ".$lang['id_lang'].", 3, '".$lang['iso_code']."', '31<h4>Email Templates</h4><h5>& 12 Additional Modules</h5>', '', 0, 'slide03_bg.png', 'slide03_item01.png,slide03_item02.png', '0{before[duration:.8,rotation:0,top:142,left:0,opacity:1]in[duration:.8,rotation:0,top:0,left:0,opacity:1]after[duration:.8,rotation:0,top:142,left:0,opacity:1]}1{before[duration:.5,rotation:0,top:142,left:52,opacity:1]in[duration:.5,rotation:0,top:25,left:25,opacity:1]after[duration:.5,rotation:0,top:142,left:52,opacity:1]}', 'before[duration:0,rotation:0,top:17,left:65,opacity:0]in[duration:0,rotation:0,top:17,left:65,opacity:1]after[duration:0,rotation:0,top:17,left:65,opacity:0]', '34', '', 'We are changed design of all 31 email templates and now they are looks the same as Alysum theme.', 1);";
				
				if (!Db::getInstance()->Execute($sql))
					return false;

			}
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
			} elseif (Tools::isSubmit('saveNumbers')){
				$this->_handleSaveNumbers();
			} elseif (Tools::isSubmit('loadNumbers')){
				$this->_handleLoadSlide();
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
			$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'minic_options_adv`');
			$slides = $subImages = $slideEffects = $slidesText = $text_width = $slidesTextP = array();
	
			foreach($activeLanguages as $lang){
				$slides[$lang['iso_code']] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE id_lang ='.$lang['id_lang'].' AND id_shop = '.$id_shop.' ORDER BY id_order ASC');				
			}

			foreach ($slides as $lang => $data) {
				foreach ($data as $id => $values) {					
					$text_width[$lang][$values["id_slide"]] = $values['text_width'];
					$imgNames = explode(",", $values["subimages"]);
					$subImages[$lang][$values["id_slide"]] = $imgNames;
					$slideEffects[$lang][$values["id_slide"]] = $this->parseProperties($values["subimages_sett"]);
					$slidesTextPos = explode("]", $values["slide_text_sett"]);
					if ($slidesTextPos[0] != "") {						
						foreach ($slidesTextPos as $propertyName => $value) {
							$explodeData = explode("[", $value);
							if ($explodeData[0] != "") {
								$slidesTextP[$explodeData[0]] = $explodeData[1];						
								foreach ($slidesTextP as $key => $val) {							
									$explodeData2 = explode(",", $val);							
									foreach ($explodeData2 as $k => $v) {
										$explodeData3 = explode(":", $v);
										$slidesText[$lang][$values["id_slide"]][$key][$explodeData3[0]] = $explodeData3[1];		
									}	
								}
							}
						}
					} else {								
						$slidesText[$lang][$values["id_slide"]] = 0;		
					}
				}				
			}	

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
				'subImages' => $subImages,
				'slideEffects' => $slideEffects,
				'slidesText' => $slidesText,
				'text_width' => $text_width,
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
				'firstStart' => 0,
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
	public function addCSSFiles() {
		$slides = Db::getInstance()->ExecuteS('SELECT id_slide FROM `'._DB_PREFIX_.'minic_slider_adv`');
		foreach ($slides as $id => $slide) {
			$settingsFile = $_SERVER["DOCUMENT_ROOT"].$this->_path."views/css/slidersettings".(int)Context::getContext()->shop->id."_".$slide["id_slide"].".css";
			if (!file_exists($settingsFile)) {
			    if (!$fileHolder = @fopen($settingsFile, 'w')) {
					$this->smarty->assign('error', $this->l('Can\'t create css file!'));
				}
			}			
		}

	}
	public function cssWriter($data) {
		
		$settingsFile = $_SERVER["DOCUMENT_ROOT"].$this->_path."views/css/slidersettings".(int)Context::getContext()->shop->id."_".Tools::getValue('slideId').".css";

		$this->addCSSFiles();
		if (!$fileHolder = @fopen($settingsFile, 'w')) {
			$this->smarty->assign('error', $this->l('Can\'t open css file!'));
		} else {				
			if (fwrite($fileHolder, $data) === FALSE) {
				$this->smarty->assign('error', $this->l('Can\'t write settings!'));
			}
			fclose($fileHolder);
		}

	}

	private function _handleOptions()
		{		
			$id_shop = (int)$this->context->shop->id;
			$effect = $current = "";
			if (Tools::getValue('nivo_effect')) { 
				$effect = implode(',', $nivo_effect); 
			}
			if(Tools::getValue('nivo_current') != '')
				$current = implode(',', Tools::getValue('nivo_current'));			
			if(!Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'minic_options_adv` SET 
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
				WHERE id = 1
					')){
				$this->smarty->assign('error', $this->l('An error occurred while saving data. I`m sure this is a DataBase error.'));
				return false;
			}
		
			return true;
		}
	
	private function _handleNewSlide()
		{
			$languages = Language::getLanguages(false);		
			$id_lang = (int)Tools::getValue('language');
			$lang = Language::getLanguage($id_lang);
			$id_shop = (int)$this->context->shop->id;
			$lastSlideID = Db::getInstance()->ExecuteS('SELECT id_slide, id_order FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE id_lang = '.$id_lang.' AND id_shop = '.$id_shop.' ORDER BY id_slide DESC LIMIT 1');
			$currentSlideID = ($lastSlideID) ? $lastSlideID[0]['id_slide']+1 : 1;
			$currentOrderID = ($lastSlideID) ? $lastSlideID[0]['id_order']+1 : 1 ;
			
			if(empty($_FILES['image']['name'])){
				$this->smarty->assign('error', $this->l('Image needed, please choose one.'));
				return false;
			}
			
			$image = $this->_resizer($_FILES['image'], Tools::getValue('imageName'));
		
			if(!$image)
				return false;
		
			$insert = Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'minic_slider_adv` ( 
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

	private function cssPropertyWrite($style, $val) {
		$css = "";
		if ($style == "duration") {
			$css .= "-webkit-transition-duration: ".$val."s;\n";
			$css .= "-moz-transition-duration: ".$val."s;\n";
			$css .= "-ms-transition-duration: ".$val."s;\n";
			$css .= "-o-transition-duration: ".$val."s;\n";
			$css .= "transition-duration: ".$val."s;\n";
		}
		if ($style == "rotation") {
			$css .= "-webkit-transform: rotate(".$val."deg);\n";
			$css .= "-moz-transform: rotate(".$val."deg);\n";
			$css .= "-ms-transform: rotate(".$val."deg);\n";
			$css .= "-o-transform: rotate(".$val."deg);\n";
			$css .= "transform: rotate(".$val."deg);\n";
		}
		if ($style == "opacity") {			
			$ieVal = $val*100;
			$css .= "filter: alpha(opacity = ".$ieVal.");\n";
			$css .= $style.": ".$val.";\n";
		}
		if ($style == "left" or $style == "top" or $style == "width") {
			$css .= $style.": ".$val."%;\n";
		}		

		return $css;
	}

	private function parseProperties($data) {
		//print_r($data);
		$result = array();
		$lev_3 = explode("}", $data); /* divide subimages */
		foreach ($lev_3 as $key_3 => $val_3) {	if ($val_3 == "") unset($lev_3[$key_3]); }					
		foreach ($lev_3 as $key_3 => $val_3) {
			$lev_4 = explode("{", $val_3); /* extract subimage ID */						
			$data_4[$lev_4[0]] = $lev_4[1]; /* apply to subimages ID his properties */						
			foreach ($data_4 as $key_4 => $val_4) { /* for each subimages */ 
				$lev_5 = explode("]", $val_4); /* divide properties */	
				foreach ($lev_5 as $key_5 => $val_5) {	if ($val_5 == "") unset($lev_5[$key_5]); }					
				foreach ($lev_5 as $key_5 => $val_5) { /* for each properties */ 
					$lev_6 = explode("[", $val_5); /* extract property ID */						
					$lev_7 = explode(",", $lev_6[1]); /* extract parameter name */	 
					foreach ($lev_7 as $key_7 => $val_7) {	if ($val_7 == "") unset($lev_7[$key_7]); }					
					foreach ($lev_7 as $key_7 => $val_7) { /* for each properties */ 
						$lev_8 = explode(":", $val_7); /* extract property ID */						 
						$result[$lev_4[0]][$lev_6[0]][$lev_8[0]] = $lev_8[1];	
					}								
				}
			}
		}
		return $result;
	}

	private function removeSubImageSettings($dt, $id) {

		$propArr = $this->parseProperties($dt);		
		foreach ($propArr as $key => $value) {
			if ($id == $key) {
				unset($propArr[$key]);
			}
		}
		return $propArr;
		
	}
	private function _handleSaveNumbers() {
		
		$langIso = Tools::getValue('slideIso');
		$slideId = Tools::getValue('slideId');
		$id_shop = (int)$this->context->shop->id;
	
		$data = Db::getInstance()->ExecuteS('SELECT subimages_sett FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')');
		$dataText = Db::getInstance()->ExecuteS('SELECT slide_text_sett FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')');
		
		$slidesProperties = $data[0]["subimages_sett"];
		$slideTextProperties = $dataText[0]["slide_text_sett"];
				
		$file = _PS_MODULE_DIR_.$this->name."/views/DBbackup/slidersettings_shop".(int)Context::getContext()->shop->id."_".$langIso."_slide".$slideId.".txt";
		$file_text = _PS_MODULE_DIR_.$this->name."/views/DBbackup/textsettings_shop".(int)Context::getContext()->shop->id."_".$langIso."_slide".$slideId.".txt";

		$fp = fopen($file, 'w');
		$fp_text = fopen($file_text, 'w');
		fwrite($fp, $slidesProperties);	
		fwrite($fp_text, $slideTextProperties);
		fclose($fp);		
		fclose($fp_text);

	}
	private function _handleLoadSlide() {

		$langIso = Tools::getValue('slideIso');
		$slideId = Tools::getValue('slideId');
		$id_shop = (int)$this->context->shop->id;
		
		$file = _PS_MODULE_DIR_.$this->name."/views/DBbackup/slidersettings_shop".(int)Context::getContext()->shop->id."_".$langIso."_slide".$slideId.".txt";
		$file_text = _PS_MODULE_DIR_.$this->name."/views/DBbackup/textsettings_shop".(int)Context::getContext()->shop->id."_".$langIso."_slide".$slideId.".txt";

		if(!$data = file_get_contents($file)) {
			$this->smarty->assign('error', $this->l('There is no backup file '.$file));	
			return false;			
		}
		if(!$data_text = file_get_contents($file_text)) {
			$this->smarty->assign('error', $this->l('There is no backup file '.$file_text));	
			return false;			
		}		
		
		$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages_sett = "'.$data.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));
		//print_r($update);echo "asdasd";
		if(!$update){
			$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
			return false;			
		}
		$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET slide_text_sett = "'.$data_text.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

		if(!$update){
			$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
			return false;			
		}

	}
	private function _handleEditSlide()
		{	
			$langIso = Tools::getValue('slideIso');
			$id_shop = (int)$this->context->shop->id;
			$newImage = $subimage = '';
			
			if(!empty($_FILES['newImage']['name'])){
				$image = $this->_resizer($_FILES['newImage']);
				if(empty($image))
					return false;
				$newImage = 'image = "'.$image.'",';
			}
			/*	Add subimages	*/
			if(!empty($_FILES['subimage']['name'])){
				$subimage = $this->_resizer($_FILES['subimage']);
				$allSubImagesRAW = Db::getInstance()->ExecuteS('SELECT subimages FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.(int)Tools::getValue('slideId').')');	

				if ($allSubImagesRAW[0]["subimages"] != "") {
					$subimage = $allSubImagesRAW[0]["subimages"].",".$subimage;
				} 
				$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages = "'.$subimage.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.(int)Tools::getValue('slideId').')'));

				if(!$update){
					$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
					return false;			
				}
			}

			/* remove subimage */			
				/* collect all subimages */

			$allLang = Language::getLanguages(true);
			foreach ($allLang as $key => $langData) {

				$allSlides = Db::getInstance()->ExecuteS('SELECT id_slide FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langData["iso_code"].'" AND id_shop = '.$id_shop.')');	

				foreach ($allSlides as $k => $id) {
					$arrSubImages = Db::getInstance()->ExecuteS('SELECT subimages FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langData["iso_code"].'" AND id_shop = '.$id_shop.' AND id_slide = '.$id["id_slide"].')');
					$allSubImages[$langData["iso_code"]][$id["id_slide"]] = explode(",", $arrSubImages[0]["subimages"]);		

					$arrTextSett = Db::getInstance()->ExecuteS('SELECT slide_text_sett FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$langData["iso_code"].'" AND id_shop = '.$id_shop.' AND id_slide = '.$id["id_slide"].')');	

					$arrTextSettSeparated = explode(",", $arrTextSett[0]["slide_text_sett"]);										
					foreach ($arrTextSettSeparated as $propertyName => $value) {
						$expldeData = explode(":", $value);
						if ($expldeData[0] != "") {
							$slidesTextP[$expldeData[0]] = $expldeData[1];	/* get slide text position parameters */
						}
					}					
				}					
			}			

				/* check if subimage should be removed */
			foreach ($allSubImages as $lang => $subimagesArray) {
				foreach ($subimagesArray as $slideId => $subimagesNames) {
					foreach ($subimagesNames as $k => $subimgName) {
						if (Tools::getValue('remove_subimage_'.$slideId.'_'.$k)) {							

							$allSubImagesRAW = Db::getInstance()->ExecuteS('SELECT subimages FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$lang.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')');
							$allSubImagesSETT = Db::getInstance()->ExecuteS('SELECT subimages_sett FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (lang_iso = "'.$lang.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')');							

							$imgList = explode(",", $allSubImagesRAW[0]["subimages"]);
							$settList = explode(";", $allSubImagesSETT[0]["subimages_sett"]);														
							unset($imgList[$k]);
							unset($settList[$k]);

							$removedExcessItem = $this->removeSubImageSettings($settList[0], $k);							

							$imgStr = implode(",", $imgList);
							//$settStr = implode(";", $settList);

							$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages = "'.$imgStr.'" WHERE (lang_iso = "'.$lang.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));
							$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages_sett = "'.$removedExcessItem.'" WHERE (lang_iso = "'.$lang.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

							if(!$update){
								$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
								return false;			
							} else {
								$this->_deleteImages($subimgName);
							}					
							
						}
					}
				}
			}

			/*	update css settings for each slide	*/			
			$css = "";					
			foreach ($allSubImages as $lang => $subimagesArray) { /* remove properties for empty subimages */				
				foreach ($subimagesArray as $slideId => $subimagesNames) {
					if ((int)Tools::getValue('slideId') == $slideId) {				
						if ($subimagesNames[0] == "") {
							unset($subimagesArray[$slideId]);					
							$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages_sett = "" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

							if(!$update){
								$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
								return false;			
							}
						}					
						$css .= ".slide-text-".$slideId." {\n";
						if ($value = Tools::getValue('text_section_width_'.$lang.'_'.$slideId) or ((int)$value == 0)) {					
							$css .= $this->cssPropertyWrite("width", $value);
							$text_width = $value; 
						} else {
							$text_width = '300'; 
							$css .= $this->cssPropertyWrite("width", 300);
						}
						
						$css .= "}\n";
						$css .= ".slide-text-".$slideId." {\n";
						$text_toDB = "before[";					
						if ($value = Tools::getValue('text-before-duration_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("duration", $value);
							$text_toDB .= 'duration:'.$value.','; 
							//echo $value;echo " tr\n";
						} else {
							//echo $value;echo " fa\n";
							$text_toDB .= 'duration:.5,'; 
							$css .= $this->cssPropertyWrite("duration", .5);
							}
						if ($value = Tools::getValue('text-before-rotation_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("rotation", $value);
							$text_toDB .= 'rotation:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("rotation", 0);
							$text_toDB .= 'rotation:0,'; 
						}
						if ($value = Tools::getValue('text-before-top_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("top", $value);
							$text_toDB .= 'top:'.$value.','; 
						}else {
							$css .= $this->cssPropertyWrite("top", -400);
							$text_toDB .= 'top:-400,'; 
						}
						if ($value = Tools::getValue('text-before-left_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("left", $value);		
							$text_toDB .= 'left:'.$value.','; 
						}else {
							$css .= $this->cssPropertyWrite("left", 0);		
							$text_toDB .= 'left:0,'; 
						}
						if ($value = Tools::getValue('text-before-opacity_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("opacity", $value);
							$text_toDB .= 'opacity:'.$value; 
						}else {
							$css .= $this->cssPropertyWrite("opacity", 1);
							$text_toDB .= 'opacity:1,'; 
						}	
						$text_toDB .= "]";
						$text_toDB .= "in[";
						$css .= "}\n";
						$css .= ".animate-in .slide-text-".$slideId." {\n";		
						if ($value = Tools::getValue('text-on-duration_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("duration", $value);
							$text_toDB .= 'duration:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("duration", .5);
							$text_toDB .= 'duration:.5,'; 
						}
						if ($value = Tools::getValue('text-on-rotation_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("rotation", $value);
							$text_toDB .= 'rotation:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("rotation", 0);
							$text_toDB .= 'rotation:0,'; 
							}
						if ($value = Tools::getValue('text-on-top_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("top", $value);
							$text_toDB .= 'top:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("top", 0);
							$text_toDB .= 'top:0,'; 
							}
						if ($value = Tools::getValue('text-on-left_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("left", $value);	
							$text_toDB .= 'left:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("left", 0);
							$text_toDB .= 'left:0,'; 
							}
						if ($value = Tools::getValue('text-on-opacity_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("opacity", $value);
							$text_toDB .= 'opacity:'.$value; 
						} else {
							$css .= $this->cssPropertyWrite("opacity", 1);
							$text_toDB .= 'opacity:1,'; 
							}
						$text_toDB .= "]";
						$text_toDB .= "after[";
						$css .= "}\n";
						$css .= ".animate-out .slide-text-".$slideId." {\n";						
						if ($value = Tools::getValue('text-after-duration_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("duration", $value);
							$text_toDB .= 'duration:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("duration", .5);
							$text_toDB .= 'duration:.5,'; 
							}
						if ($value = Tools::getValue('text-after-rotation_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("rotation", $value);
							$text_toDB .= 'rotation:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("rotation", 0);
							$text_toDB .= 'rotation:0,'; 
							}
						if ($value = Tools::getValue('text-after-top_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("top", $value);
							$text_toDB .= 'top:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("top", 800);
							$text_toDB .= 'top:800,'; 
							}
						if ($value = Tools::getValue('text-after-left_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("left", $value);
							$text_toDB .= 'left:'.$value.','; 
						} else {
							$css .= $this->cssPropertyWrite("left", 0);
							$text_toDB .= 'left:0,'; 
							}
						if ($value = Tools::getValue('text-after-opacity_'.$lang.'_'.$slideId) or ((int)$value == 0)) {
							$css .= $this->cssPropertyWrite("opacity", $value);
							$text_toDB .= 'opacity:'.$value; 
						} else {
							$css .= $this->cssPropertyWrite("opacity", 1);
							$text_toDB .= 'opacity:1,'; 
							}
						$text_toDB .= "]";						
						$css .= "}\n";	
						//print_r($text_toDB);
						$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET slide_text_sett = "'.$text_toDB.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

						if(!$update){
							$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
							return false;			
						}
						$update2 = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET text_width = "'.$text_width.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

						if(!$update2){
							$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
							return false;			
						}
					}
				}
				if (is_array($subimagesArray)) {									
					foreach ($subimagesArray as $slideId => $subimagesNames) {
						if ((int)Tools::getValue('slideId') == $slideId) {				
							$toDB = "";
							foreach ($subimagesNames as $k => $subimgName) {
								$toDB .= $k."{";
								$toDB .= "before[";
								$css .= "#slide".$slideId." .slideImg".$k." {\n";			
								if ($value = Tools::getValue('before-duration_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("duration", $value);
									$toDB .= 'duration:'.$value.','; 
									//echo $value;echo " tr\n";
								} else {
									//echo $value;echo " fa\n";
									$toDB .= 'duration:.5,'; 
									$css .= $this->cssPropertyWrite("duration", .5);
									}
								if ($value = Tools::getValue('before-rotation_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("rotation", $value);
									$toDB .= 'rotation:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("rotation", 0);
									$toDB .= 'rotation:0,'; 
								}
								if ($value = Tools::getValue('before-top_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("top", $value);
									$toDB .= 'top:'.$value.','; 
								}else {
									$css .= $this->cssPropertyWrite("top", -400);
									$toDB .= 'top:-400,'; 
								}
								if ($value = Tools::getValue('before-left_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("left", $value);		
									$toDB .= 'left:'.$value.','; 
								}else {
									$css .= $this->cssPropertyWrite("left", 0);		
									$toDB .= 'left:0,'; 
								}
								if ($value = Tools::getValue('before-opacity_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("opacity", $value);
									$toDB .= 'opacity:'.$value; 
								}else {
									$css .= $this->cssPropertyWrite("opacity", 1);
									$toDB .= 'opacity:1,'; 
								}
								$toDB .= "]";
								$toDB .= "in[";
								$css .= "}\n";
								$css .= ".animate-in#slide".$slideId." .slideImg".$k." {\n";						
								if ($value = Tools::getValue('on-duration_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("duration", $value);
									$toDB .= 'duration:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("duration", .5);
									$toDB .= 'duration:.5,'; 
								}
								if ($value = Tools::getValue('on-rotation_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("rotation", $value);
									$toDB .= 'rotation:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("rotation", 0);
									$toDB .= 'rotation:0,'; 
									}
								if ($value = Tools::getValue('on-top_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("top", $value);
									$toDB .= 'top:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("top", 0);
									$toDB .= 'top:0,'; 
									}
								if ($value = Tools::getValue('on-left_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("left", $value);	
									$toDB .= 'left:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("left", 0);
									$toDB .= 'left:0,'; 
									}
								if ($value = Tools::getValue('on-opacity_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("opacity", $value);
									$toDB .= 'opacity:'.$value; 
								} else {
									$css .= $this->cssPropertyWrite("opacity", 1);
									$toDB .= 'opacity:1,'; 
									}
								$toDB .= "]";
								$toDB .= "after[";
								$css .= "}\n";
								$css .= ".animate-out#slide".$slideId." .slideImg".$k." {\n";						
								if ($value = Tools::getValue('after-duration_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("duration", $value);
									$toDB .= 'duration:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("duration", .5);
									$toDB .= 'duration:.5,'; 
									}
								if ($value = Tools::getValue('after-rotation_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("rotation", $value);
									$toDB .= 'rotation:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("rotation", 0);
									$toDB .= 'rotation:0,'; 
									}
								if ($value = Tools::getValue('after-top_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("top", $value);
									$toDB .= 'top:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("top", 800);
									$toDB .= 'top:800,'; 
									}
								if ($value = Tools::getValue('after-left_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("left", $value);
									$toDB .= 'left:'.$value.','; 
								} else {
									$css .= $this->cssPropertyWrite("left", 0);
									$toDB .= 'left:0,'; 
									}
								if ($value = Tools::getValue('after-opacity_'.$lang.'_'.$slideId.'_'.$k) or ((int)$value == 0)) {
									$css .= $this->cssPropertyWrite("opacity", $value);
									$toDB .= 'opacity:'.$value; 
								} else {
									$css .= $this->cssPropertyWrite("opacity", 1);
									$toDB .= 'opacity:1,'; 
									}
								$toDB .= "]";						
								$toDB .= "}";
								$css .= "}\n";										
								
							}
						
							$update = (Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET subimages_sett = "'.$toDB.'" WHERE (lang_iso = "'.$langIso.'" AND id_shop = '.$id_shop.' AND id_slide = '.$slideId.')'));

							if(!$update){
								$this->smarty->assign('error', $this->l('An error occured while saving data.'));	
								return false;			
							}
						}
					}										
				}
			}			
			$this->cssWriter($css);

			$update = Db::getInstance()->Execute('
				UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET 
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
		
			if(!empty($_FILES['newImage']['name'])){
				$this->_deleteImages(Tools::getValue('oldImage'));
			}
		
			$this->smarty->assign('confirmation', $this->l('Saved succsessfull.'));
		}
	
	public function _handleDeleteSlide()
		{
			$id_shop = (int)$this->context->shop->id;
			Db::getInstance()->delete(_DB_PREFIX_.'minic_slider_adv', 'id_slide = '.(int)Tools::getValue('slideId'));
		
			if(Db::getInstance()->Affected_Rows() == 1){
				Db::getInstance()->Execute('
					UPDATE `'._DB_PREFIX_.'minic_slider_adv` 
					SET id_order = id_order-1 
					WHERE (
						id_order > '.Tools::getValue('orderId').' AND 
						lang_iso = "'.Tools::getValue('slideIso').'" AND 
						id_shop = '.$id_shop.')
				');
		
				$this->_deleteImages(Tools::getValue('oldImage'));
				$this->smarty->assign('confirmation', $this->l('Deleted succsessfull.'));
			}else{
				$this->smarty->assign('error', $this->l('Cant delete slide data from database.'));
			}
		}
	
	private function _resizer($image, $newName = NULL)
		{
			//$path = $_SERVER['DOCUMENT_ROOT'].$this->_path.'uploads/';
			//$pathThumb = $_SERVER['DOCUMENT_ROOT'].$this->_path.'uploads/thumbs/';

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
			if(file_exists($path.$name) && $newName == NULL){
				$name = $imageName[0].date('-i-s').'.'.$imageName[1];
			}

			// Check image size and format
			if($error = ImageManager::validateUpload($image, $this->maxImageSize)){
				$this->context->smarty->assign('error', $error);
				return;
			}

			// Move image
			if(!ImageManager::resize($image['tmp_name'], dirname(__FILE__).'/uploads/'.$name)){
				$this->context->smarty->assign('error', $this->l('An error occured during the upload, please check the permissions.'));
				unlink($tmpName);
				return;
			}			

			// Create thumbnail for slider
			$imgSize = getimagesize($path.$name);
			if($imgSize[0] >= $imgSize[1]){
				// Resize based on width
				$imgWidth = 50;
				$imgHeight = ($imgSize[1]/100)*(5000/$imgSize[0]);
			}else{
				// Resize based on height
				$imgHeight = 50;
				$imgWidth = ($imgSize[0]/100)*(5000/$imgSize[1]);
			}

			// Actual resize
			if(!ImageManager::resize($path.$name, $pathThumb.$name, (int)$imgWidth, (int)$imgHeight)){
				$this->context->smarty->assign('error', $this->l('An error occurred during the image upload. Please check the upload directory permission in the module folder.'));
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
					$this->context->smarty->assign('error', $this->l('Cant delete images, please check permissions!'));			
			}else{
				//$this->context->smarty->assign('error', $this->l('Image doesn`t exists!'));
			}
		}
	
	public function hookDisplayHeader() {

		$this->context->controller->addCSS($this->_path.'views/css/sequencejs.css');
		$this->context->controller->addCSS($this->_path.'views/css/customsequencesettings.css');
		$slides = Db::getInstance()->ExecuteS('SELECT id_slide FROM `'._DB_PREFIX_.'minic_slider_adv`');
		foreach ($slides as $id => $slide)
			$this->context->controller->addCSS($this->_path . 'views/css/slidersettings'.(int)$this->context->shop->id.'_'.$slide["id_slide"].'.css');

		$this->context->controller->addJS($this->_path.'views/js/sequence.jquery-min.js');

	}
 	
 	public function getData($position) {

 		$defLanguages = (int)Configuration::get('PS_LANG_DEFAULT');
		$activeLanguages = Language::getLanguages(true);
		$allLanguages = Language::getLanguages(false);
		foreach ($allLanguages as $key => $lang) {
			if ($lang["id_lang"] == $defLanguages)
				$defLangIso = $allLanguages[$key]['iso_code'];			
		}						
		$id_shop = (int)$this->context->shop->id;
		// $this->smarty->assign('defaultLangIso', $defLangIso);
		$options = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'minic_options_adv`');
		$tpl = 'single.tpl';
		if($options['single'] == 1)
			$tpl = 'multiple.tpl';
		$width = array();
		$slides = array();

		if($options['single'] == 0)
			$slides[$defLangIso] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (id_lang ='.$defLanguages.' AND id_shop = '.$id_shop.' AND active = 1) ORDER BY id_order ASC');			
		else
			foreach ($activeLanguages as $lang)
				$slides[$lang['iso_code']] = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'minic_slider_adv` WHERE (id_lang ='.$lang['id_lang'].' AND id_shop = '.$id_shop.' AND active = 1) ORDER BY id_order ASC');
		
		$subImages = array();
		foreach ($slides as $lang => $data) {
			if (isset($data)) {				
				foreach ($data as $id => $values) {					
					if (isset($values)) {		
						$imgNames = explode(",", $values["subimages"]);
						$subImages[$lang][$values["id_slide"]] = $imgNames;					
					} else
						$subImages[$lang][$values["id_slide"]] = "";
				}				
			} else
				$subImages[$lang] = "";
		}			

		$this->smarty->assign('slides', $slides);
		$this->smarty->assign('subImages', $subImages);		
		$this->smarty->assign('defLang', $defLangIso);
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
				//'images' => $this->_path.'uploads/',
				//'thumbs' => $this->_path.'uploads/thumbs/front_'
				'images' => '//'.Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/uploads/',
				'thumbs' => '//'.Tools::getMediaServer($this->name)._MODULE_DIR_.$this->name.'/uploads/thumbs/front_'
			),
			'tpl' => _PS_MODULE_DIR_.$this->name.'/views/templates/front/'.$tpl,
			'position' => $position

		));
 	}

 	public function hookwide_top($params)
	{
 		$status = $this->getModuleState("wide_top");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}

 	public function hookhook_home_01($params)
	{
 		$status = $this->getModuleState("hook_home_01");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}
	
	public function hookHook_home_02($params)
	{
		$status = $this->getModuleState("hook_home_02");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}
	public function hookHook_home_03($params)
	{
		$status = $this->getModuleState("hook_home_03");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}
	public function hookHook_home_04($params)
	{
		$status = $this->getModuleState("hook_home_04");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}
	public function hookHook_home_05($params)
	{
		$status = $this->getModuleState("hook_home_05");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}	
	public function hookHook_home_06($params)
	{
		$status = $this->getModuleState("hook_home_06");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}	
	public function hookHook_home_07($params)
	{
		$status = $this->getModuleState("hook_home_07");
 		if ($status == 1) {
	 		$this->getData($params);
	 		return $this->display(__FILE__, 'views/templates/front/front.tpl');
	 	}
	}		

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}
}

?>