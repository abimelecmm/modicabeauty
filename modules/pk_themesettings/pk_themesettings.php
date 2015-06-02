<?php
/**
 * $ModDesc
 * 
 * @version		1.6.3
 * @package		modules
 * @copyright	Copyright (C) February 2013 http://promokit.eu <@email:support@promokit.eu>. All rights reserved.
 * @license		GNU General Public License version 2
 */
if (!defined('_PS_VERSION_'))
	exit;

class pk_themesettings extends Module
{

	public $pattern = '/^([A-Z_]*)[0-9]+/';
	public $page_name = '';

	public function __construct()
	{
		$this->displayName = $this->l('Theme Settings');
		$this->description = $this->l('Add extended theme settings');
		$this->version = "1.6.3";
		$this->theme_version = "4.3";
		$this->theme_name = "Alysum";
		$this->versions = 'TS v.'.$this->version.' | '.$this->theme_name.' v.'.$this->theme_version.' | PS v.'._PS_VERSION_;		
		$this->name = 'pk_themesettings';		
		$this->tab = 'front_office_features';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;
		$this->maxSize = 81;
		$this->bootstrap = false;		

		parent::__construct();		

		$this->config = $this->local_path.'presets/0.json';
		$this->mailArchive = "http://promokit.eu/share/".strtolower($this->theme_name)."/"._PS_VERSION_."/Mail.zip";

		$this->patternsQuantity = 24;
		$this->mdb = _DB_PREFIX_.'pk_theme_settings';
		$this->hdb = _DB_PREFIX_.'pk_theme_settings_hooks';

		$string = file_get_contents($this->config);
		$json_arr = json_decode($string, true);	

		$this->defaultFonts = array(
			"logo" => $json_arr["logo_font"],
			"slogan" => $json_arr["slogan_font"],
			"buttons" => $json_arr["buttons_font"],
			"headings" => $json_arr["heading_font"],
			"text" => $json_arr["text_font"],
			"price" => $json_arr["price_font"]
		);
		$this->systemFonts = array("Arial", "Tahoma", "Georgia", "Times New Roman", "Verdana", "FontAwesome", "LMRoman10Regular", "LMRomanDunhill10Regular", "TrajanProRegular", "LMRomanCaps10-Regular", "Serif12BetaRgRegular", "lm_roman_demi_10regular");

		$this->selectors = array(
			"logo_container" => "body #header #header_logo",
			"logo" => "#header_logo .logo",
			"slogan" => "#header_logo .slogan",			
			"headings" => "h1, #white_bg .lmroman",
			"subheadings" => "h6, #white_bg .lmromancaps",
			"price" => ".price, .our_price_display",
			"text" => "#page p, #page section .rte p, #page section .rte",
			"links" => "a",
			"bg_image" => "#white_bg",
			"bg" => "#white_bg",
			"buttons" => "input.button_mini,input.button_small,input.button,input.button_large,input.button_mini_disabled,input.button_small_disabled,input.button_disabled,input.button_large_disabled,input.exclusive_mini,input.exclusive_small,
input.exclusive,input.exclusive_large,input.exclusive_mini_disabled,input.exclusive_small_disabled,input.exclusive_disabled,input.exclusive_large_disabled,a.button_mini,a.button_small,a.button,a.button_large,a.exclusive_mini,a.exclusive_small,a.exclusive,a.exclusive_large,span.button_mini,span.button_small,span.button,span.button_large,span.exclusive_mini,span.exclusive_small,span.exclusive,span.exclusive_large,span.exclusive_large_disabled,.compare-form .button,button.button, #id_new_comment_form .button",
			"buttons_hover" => "input.button_mini:hover,input.button_small:hover,input.button:hover,input.button_large:hover,input.button_mini_disabled:hover,input.button_small_disabled:hover,input.button_disabled:hover,input.button_large_disabled:hover,input.exclusive_mini:hover,input.exclusive_small:hover,input.exclusive:hover,input.exclusive_large:hover,input.exclusive_mini_disabled:hover,input.exclusive_small_disabled:hover,input.exclusive_disabled:hover,input.exclusive_large_disabled:hover,a.button_mini:hover,a.button_small:hover,a.button:hover,a.button_large:hover,a.exclusive_mini:hover,a.exclusive_small:hover,a.exclusive:hover,a.exclusive_large:hover,span.button_mini:hover,span.button_small:hover,span.button:hover,span.button_large:hover,span.exclusive_mini:hover,span.exclusive_small:hover,span.exclusive:hover,span.exclusive_large:hover,span.exclusive_large_disabled:hover,.compare-form .button:hover,button.button:hover, #id_new_comment_form .button:hover",
			"first_color_back" => "#white_bg .main_bg, #white_bg .main_bg_hvr:hover, ul.camera_pag_ul li:hover span, ul.camera_pag_ul li.cameracurrent span, ::selection, .flexmenuitem > a span:before, .ui-slider-range, .ui-slider-handle",
			"first_border_color" => "#white_bg .main_bord, #white_bg .main_bord_hvr:hover, .preset4 .carousel-title .flexisel-nav:hover",
			"first_color" => "#white_bg .main_color, #white_bg .main_color_hvr:hover, body .flexmenu > ul > li > a:hover, .pk_aboutus_text ul li:before, body .flexmenu > ul > li:hover > a, body .flexmenu > ul > li.current > a",
			"first_color_shadow" => "#white_bg .bshadow, #white_bg .bshadow_hvr:hover",
			"second_color_shadow" => "#white_bg .sec_bshadow, #white_bg .sec_bshadow_hvr:hover",
			"second_color" => "#white_bg .second_color, #footer #twitter-feed ul li a",
			"second_color_back" => "#white_bg .sec_bg, #white_bg .sec_bg_hvr:hover, #sequence .slide-text h4",
			"second_border_color" => "#white_bg .sec_bord_hvr:hover, #white_bg .sec_bord_hvr",
			"third_color_back" => ".third_color_back",
			"third_color" => ".third_color",
			"logo_container_admin" => ".logo-container",
			"logo_admin" => "#logofont_example",
			"slogan_admin" => "#sloganfont_example",
			"headings_admin" => ".tabcontent #heading-example h5",
			"subheadings_admin" => ".tabcontent #heading-example h6",
			"text_admin" => ".tabcontent #text-example",
			"price_admin" => ".tabcontent #price-example",
			"links_admin" => ".tabcontent #link-example a",
			"custom_heading" => ".custom_heading",
			"additionalBg" => ".additionalBg",
		);
		$this->tabs = array("General Settings", "Background","Logo","Buttons","Typography & Colors","Home Page", "Category page", "Product Page", "Custom CSS", "Email Settings", "Social Accounts", "Coming Soon Page", "Payment Icons");

		$this->bImage = $this->errors = "";
		$this->noImage = "No image selected";
		$this->customcssFile = $this->local_path."css/customercss".(int)Context::getContext()->shop->id.".css";
		$this->generatedFile = $this->local_path."css/generatedcss".(int)Context::getContext()->shop->id.".css";		

	}

	public function install() {
	
    	$allLanguages = Language::getLanguages(false);				
		$id_shop = (int)Shop::getContextShopID();
		$tablesfile = 'sql/install.sql';
		$datafile = 'sql/installData.sql';			
		$msg = '<div class="conf confirm">'.$this->l('Demo Data Installed Successfully').'</div>';
		if (!file_exists(dirname(__FILE__).'/'.$tablesfile)) 
			$msg = '<div class="conf error">'.$this->l('There is no sql file.').'</div>';
		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.$tablesfile)) {
			$msg = '<div class="conf error">'.$this->l('There is no sql code.').'</div>';
		} else {
			$queries = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
			$queries = preg_split("/;\s*[\r\n]+/", $queries);
			foreach ($queries AS $query)
				if($query)
					if(!Db::getInstance()->execute(trim($query)))
						$msg = '<div class="conf error">'.$this->l('Error in SQL syntax of Tables').'</div>';

		}
		if (!file_exists(dirname(__FILE__).'/'.$datafile)) 
			$msg = '<div class="conf error">'.$this->l('There is no sql file.').'</div>';
		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.$datafile)) {
			$msg = '<div class="conf error">'.$this->l('There is no sql code.').'</div>';
		} else {
			$queries = "";
			$id1 = 1;$id2 = 2;$id3 = 3;$id4 = 4;$id5 = 5;
			foreach ($allLanguages as $key => $lang) {					
				$queries .= str_replace(array('PREFIX_', 'IDLANG', 'LANGISO', 'IDSHOP', 'ID1', 'ID2', 'ID3', 'ID4', 'ID5'), array(_DB_PREFIX_, $lang["id_lang"], $lang["iso_code"], $id_shop, $id1, $id2, $id3, $id4, $id5), $sql);
				$id1=$id1+5;$id2=$id2+5;$id3=$id3+5;$id4=$id4+5;$id5=$id5+5;
			}

			$queries = preg_split("/;\s*[\r\n]+/", $queries);
			foreach ($queries AS $query)
				if($query)
					if(!Db::getInstance()->execute(trim($query)))
						$msg = '<div class="conf error">'.$this->l('Error in SQL syntax of Data').'</div>';

		}

		// change global prestashop settings
		Configuration::updateValue('PS_ALLOW_MOBILE_DEVICE', 0); // disable default mobile theme
		Configuration::updateValue('PS_NAVIGATION_PIPE', ">"); // change default pipe in breadcrumb
		Configuration::updateValue('FOOTER_POWEREDBY', 0); // disable powerby footer option
		Configuration::updateValue('FOOTER_CMS', NULL);	// disable all cms links in footer
		Configuration::updateValue('PS_JS_DEFER', 1);	// move js to the end of html document
		Configuration::updateValue('PS_CSS_THEME_CACHE', 1);	// enable combine all css files to one
		Configuration::updateValue('HOMESLIDER_WIDTH', '870'); // width of default homepage slider
		Configuration::updateValue('HOMESLIDER_SPEED', '550'); // height of default homepage slider
		Configuration::updateValue('FIRST_START', 0); // variable determine first start
		Configuration::updateValue('PS_ALLOW_HTML_IFRAME', 1); // required for video posts in blog
		Configuration::updateValue('PS_QUICK_VIEW', 1); // enable quick view button
		Configuration::updateValue('MANUFACTURER_DISPLAY_FORM', 0); // disable dropdown list in manufacturers module

		if (parent::install() && 
			$this->registerHook('displayHeader') &&
			$this->registerHook('productVideo') &&
			$this->registerHook('displayAdminProductsExtra') &&
			$this->registerHook('actionProductUpdate') &&
            $this->registerHook('productTab') &&
            $this->registerHook('productTabContent') &&
            $this->registerHook('comingsoon') &&
            $this->registerHook('footer_top') &&            
            $this->registerHook('footer_bottom') &&
            $this->updateDBsettigs(false, true)) {
            $this->emailFilesUpdate();
        	$this->installQuickAccess();
			return true;	
		} else {			
			$this->uninstall();
			return false;		
		}
	}

	public function uninstall() {
        $sql = array();
        $sql[] = 'DELETE FROM `'._DB_PREFIX_.'quick_access` WHERE link = "index.php?controller=AdminModules&configure=pk_themesettings&tab_module=front_office_features&module_name=pk_themesettings"';
        $sql[] = 'DELETE FROM `'._DB_PREFIX_.'quick_access_lang` WHERE name = "'.$this->theme_name.' Settings"';
		$sql[] = 'DROP TABLE IF EXISTS `'.$this->mdb.'`';
		$sql[] = 'DROP TABLE IF EXISTS `'.$this->hdb.'`';
        
        if (!parent::uninstall() OR
            !$this->runSql($sql)) {
            return FALSE;
        } else {
        	unlink(_PS_OVERRIDE_DIR_.'classes/Mail.php');
        	$this->clearCache(_PS_CACHE_DIR_.'class_index.php');
        	return TRUE;
        }        
    }

    public function array_diff_key_recursive($aArray1, $aArray2) {
	  $aReturn = array();
	  if (is_array($aArray2)) {
		  foreach ($aArray1 as $mKey => $mValue)	  	
		    if (array_key_exists($mKey, $aArray2)) {		    	
		      if (is_array($mValue)) {
		        $aRecursiveDiff = $this->array_diff_key_recursive($mValue, $aArray2[$mKey]);
		        if (count($aRecursiveDiff))
		        	$aReturn[$mKey] = $aRecursiveDiff; 
		      } else {
		        if ($mValue != $aArray2[$mKey])
		          $aReturn[$mKey] = $mValue;		          
		      }
		    } else {
		      $aReturn[$mKey] = $mValue;
		    }
	  	return $aReturn;
	  } else {
	  	return false;
	  }
	} 

    public function updateDBsettigs($new_config = false, $start = false) {       	
    	// $new_config - used when you need to change preset 
    	
    	if ($new_config != false)
    		$config_file = $this->local_path.'presets/'.$new_config.'.json';
    	else
    		$config_file = $this->config;

    	$sql = $sql_diff = array();
    	$sid = (int)Shop::getContextShopID();    	    	
		$string = file_get_contents($config_file);

		//$json_arr = json_decode(html_entity_decode($string), true);
		$json_arr = json_decode($string, true);
		if($json_arr === null)
			return $this->displayError($this->l('Config seems has syntax error'));
		
		$s = $this->getOptions("updateDBsettigs");
    	$s["modules"] = $this->getModulesState();

    	if ($start == true) {
    		$sqlPart = $sqlHookPart = '';
    		foreach ($json_arr as $name => $value)
    			if ($name == "modules")
					foreach ($value as $hook => $modules) {
						foreach ($modules as $module => $val) {
							$vals = explode(".", $val);
						    $sqlHookPart .= 'INSERT INTO `'.$this->hdb.'` (`id_shop`, `hook`, `module`, `ordr`, `value`) VALUES ('.$sid.', "'.$hook.'", "'.$module.'", "'.$vals[0].'", "'.$vals[1].'");';
						}
					}
				else
					$sqlPart .= 'INSERT INTO `'.$this->mdb.'` (`id_shop`, `name`, `value`) VALUES ('.$sid.', "'.$name.'", "'.$value.'");';

			$sql[] = $sqlPart;
			$sql[] = $sqlHookPart;

			if (!$this->runSql($sql))
				return false;
			return true;
    			
    	} else {	    	

			if($json_arr === null)		
				return false;

			else {
	    		
	    		$json_arr_diff = $this->array_diff_key_recursive($json_arr, $s);	// checking for new options
	    		
	    		$check_main = Db::getInstance()->ExecuteS('SELECT id_setting FROM `'.$this->mdb.'` WHERE name = "column" AND id_shop = '.$sid.';');		    	

		    	if (!empty($json_arr_diff)) { // add new options if exist

		    		$sqlPart_diff = $sqlHookPart_diff = '';
		    		foreach ($json_arr_diff as $name => $value) {		    			
	    				if ($name == "modules")	{
							if (!empty($value))						
								foreach ($value as $hook => $modules)
									foreach ($modules as $module => $val) {
										$vals = explode(".", $val);
										$check_hooks = Db::getInstance()->ExecuteS('SELECT id_setting FROM `'.$this->hdb.'` WHERE hook = "'.$hook.'" AND module = "'.$module.'" AND id_shop = '.$sid.';'); // check DB if settings for current shop exist
										if (empty($check_hooks))
									    	$sqlHookPart_diff .= 'INSERT INTO `'.$this->hdb.'` (`id_shop`, `hook`, `module`, `ordr`, `value`) VALUES ('.$sid.', "'.$hook.'", "'.$module.'", "'.$vals[0].'", "'.$vals[1].'");';
									    else
									    	$sqlHookPart_diff .= "UPDATE `".$this->hdb."` SET `value` = '".$vals[1]."', `ordr` = '".$vals[0]."' WHERE `hook` = '".$hook."' AND `module` = '".$module."' AND `id_shop` = '".$sid."';";
									}
						}
		    			if (empty($check_main)) {
		    				if ($name != "modules")
								$sqlPart_diff .= 'INSERT INTO `'.$this->mdb.'` (`id_shop`, `name`, `value`) VALUES ('.$sid.', "'.$name.'", "'.$value.'");';
						}
		    			else {
		    				if ($name != "modules")
		    					$sqlPart_diff .= "UPDATE `".$this->mdb."` SET `value` = '".$value."' WHERE `name` = '".$name."' AND `id_shop` = '".$sid."';";
		    			}

					}
					$sql_diff[] = $sqlPart_diff;
					$sql_diff[] = $sqlHookPart_diff;					

					$this->runSql($sql_diff);
		    	}	    	
		    
				return true;
			}
		}
	}

    public function clearCache($name) {
    	
		if (!unlink($name))
			$cleared = "<div class=\"conf error\">Cache doesn't cleared</div>";
        else
			$cleared = "<div class=\"conf confirm\">Cache cleared</div>";	            	

		return $cleared;
	}	

	public function runSql($sql) {
		//Db::getInstance()->query("FLUSH QUERY CACHE");
        foreach ($sql as $s) {
        	if (!empty($s))
				if (!Db::getInstance()->Execute($s)) 
					return false;
        }
        return true;
    }

	public function fontslist() {
		include_once(dirname(__FILE__).'/fontlist.php');
		return get_fonts();
	}

	public function savePosition($num) {
		$sql = array();
		$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "'.$num.'" WHERE name = "tab_number" AND id_shop = '.(int)Context::getContext()->shop->id.';';
		$this->runSql($sql);
	}

	public function getContent()
	{					
	    $s = $this->getOptions("getContent");
	    $sid = (int)Context::getContext()->shop->id;
		$lid = $this->context->language->id;	    
				
		$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.colorpicker.js');
		$this->context->controller->addjqueryPlugin('sortable');		
		$this->context->controller->addJS(($this->_path).'js/ts_scripts.js'); // add JS to back office
		$this->context->controller->addCSS(($this->_path).'css/themesettings_admin.css'); // add CSS to back office
	
		$msg = $err = $output = '';

		if (Tools::isSubmit('submitDeleteImgConf'))
			$msg .= $this->deleteImg($s["back_image"], "back_image", (int)(Tools::getValue("tab_number")), $sid);


		if (Tools::isSubmit('submitDeleteEmailImg'))
			$msg .= $this->deleteImg($s["email_image"], "email_image", (int)(Tools::getValue("tab_number")), $sid);


		if (Tools::isSubmit('resetThemeSettings'))	{	

			$remove_sett = Db::getInstance()->Execute('DELETE FROM `'.$this->mdb.'` WHERE id_shop =  '.(int)Context::getContext()->shop->id);
			$remove_hooks = Db::getInstance()->Execute('DELETE FROM `'.$this->hdb.'` WHERE id_shop =  '.(int)Context::getContext()->shop->id);
			if (!$this->updateDBsettigs(false, true)) {
				$msg = '<div class="conf error">'.$this->l('Can not write to DB');
			} else {
				$this->cssWriter($this->readCustomCSS(), $s);
				$msg = '<div class="conf confirm">'.$this->l('Settings reseted').'</div>';	
			}

		}			
		
		if (Tools::isSubmit('back_image_upload')) {	

			$img = $this->addImage($_FILES, "back_image", $sid, $lid, (int)(Tools::getValue('tab_number')));				
			$msg .= $img["error"].'<div class="conf confirm">'.$this->l('Settings updated').'</div>';	

		}

		if (Tools::isSubmit('cat_image')) {	
			foreach ($_FILES["cat"] as $key => $value) {
				$i = 0;				
				foreach ($value as $k => $v)
					if ($_FILES['cat']['size'][$k] != 0) {
						$image[$i][$k][$key] = $v;
						$i++;
					}						
			}
			
			foreach ($image as $id => $data)
				foreach ($data as $catID => $im) {
					$this->addImage($data, $catID, $sid, $lid, (int)(Tools::getValue('tab_number')));
				}

		}

		if (Tools::isSubmit('email_image_upload')) {	

			$img = $this->addImage($_FILES, "email_image", $sid, $lid, (int)(Tools::getValue('tab_number')));				
			$msg .= $img["error"].'<div class="conf confirm">'.$this->l('Settings updated').'</div>';	

		}

		if (Tools::isSubmit('submitThemeSettings')) {

			parent::_clearCache('views/frontend/products.tpl');
			// check if no data for current shop - import it.
			$check = Db::getInstance()->ExecuteS('SELECT id_setting FROM `'.$this->mdb.'` WHERE name = "responsive" AND id_shop = '.$sid.';');
			
    		if (empty($check)) 
    			$this->updateDBsettigs();

			$sql = array();

			foreach ($_POST as $key => $value) {	

				if (($key != "submitThemeSettings") && ($key != "preset")) {
				
					if (($key != "maintenance") && ($key != "custom_css") && ($key != "modules") && ($key != "ordr")) {
						$value = str_replace('"', '\'', $value);
						$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "'.$value.'" WHERE name = "'.$key.'" AND id_shop = '.$sid.';';

					} elseif ($key == "maintenance") {
						Configuration::updateValue('PS_SHOP_ENABLE', $value);						

					} elseif ($key == "custom_css") {
						$this->cssWriter($value);

					} elseif ($key == "modules") {
						foreach ($value as $hook => $modules) {
							foreach ($modules as $module => $val) {
								$sql[] = 'UPDATE `'.$this->hdb.'` SET `ordr` = "'.(int)$_POST["ordr"][$hook][$module].'", value = '.(int)$val.' WHERE hook = "'.$hook.'" AND module = "'.$module.'" AND id_shop = '.$sid.';';
							}
						}
					}

					if (($key == "ready_year") || ($key == "ready_month") || ($key == "ready_day") || ($key == "ready_hour") || ($key == "ready_min"))
						if ($s[$key] != $value)
							$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "'.round(microtime(true)).'" WHERE name = "date_set" AND id_shop = '.$sid.';';

				}

			}
			
			$this->runSql($sql);	
			$msg2 = "";
			foreach ($_POST as $key => $value)			
				if ($key == "preset")
					if ($s[$key] != $value)
						$msg2 = $this->updateDBsettigs($value);

			$this->updateModulesState();
		
			// ######## clear tpl cache of some modules
			$modules = array('editorial', 'blockcmsinfo', 'pk_reinsurance', 'pk_featuredcategories', 'blockcategories');
			foreach ($modules as $key => $module)
				Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath($module));

			// ######## write settings to file	
			$this->cssWriter($this->readCustomCSS());									
			if ($this->errors) { $errors = '<div class="conf error">'.$this->errors.'</div>'; } else $errors = "";
			$msg .= $errors.'<div class="conf confirm">'.$this->l('Settings updated').'</div>';	

		}

		if (Tools::isSubmit('fixEmails')) {	
				
			$msg = $this->emailFilesUpdate();		
			$this->savePosition((int)(Tools::getValue('tab_number')));

		}

		if (Tools::isSubmit('aupdate')) {			
			$list = $this->checkupdates();
			$versions = explode(",", $list);
			foreach ($versions as $version)
				$msg .= $this->themeUpdate($version);
		}	

		if (Tools::isSubmit('sendnotification')) {
			$readyEmails = $this->getEmails();

			if (Configuration::get('PS_LOGO_MAIL') !== false && file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL', null, null, $sid)))
				$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO_MAIL', null, null, $sid);
			else {
				if (file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $sid)))
					$logo = _PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $sid);
				else
					$vars['{shop_logo}'] = '';
			}
			ShopUrl::cacheMainDomainForShop((int)$sid);
			/* don't attach the logo as */

			if (isset($logo))
				$vars['{shop_logo}'] = ImageManager::getMimeTypeByExtension($logo);
			$vars['{email_menu_item}'] = 'color:#ffffff; font-size:15px; line-height:46px; mso-line-height-rule:exactly; font-family: \'Times new roman\'; text-transform:uppercase; text-decoration:none;';
			$vars["{shop_name}"] = Configuration::get('PS_SHOP_NAME');
			$vars["{shop_url}"] = Context::getContext()->link->getPageLink('index', true, Context::getContext()->language->id);
			Mail::Send(
				(int)$lid,
				'opening',
				Mail::l('Shop Opening', (int)$lid),
				$vars,
				$readyEmails,
				null,
				null,
				null,
				null,
				null,
				_PS_MODULE_DIR_.$this->name."/mails/",
				false,
				(int)$sid
			);
			
		}
		return $output.$this->displayForm($msg);
		
	}


	public function updateModulesState() {
		$mState = $this->getModulesState();
		$skip = array("pk_themesettings", "ph_simpleblog", "ph_recentposts", "pk_testimonials");
		$disabledList = $enabledList = array();
		// move module to necessary hooks if it is not there
		foreach ($mState as $hook => $modules)
			foreach ($modules as $module => $state) {
				$val = explode(".", $state);
				if ($val[1] == 1) { // if module is enabled
					if (($id_hook = Hook::getIdByName($hook)) !== false) {
						$mInstance = Module::getInstanceByName($module);
						if (Validate::isLoadedObject($mInstance)) {
							$position = $mInstance->getPosition($id_hook);
							if (!$position) // if module is not in our hook
								if ($mInstance->isHookableOn($hook))
									$mInstance->registerHook($hook);
						}
					}
				}
			}

		// order modules like in config
		foreach ($mState as $hook => $modules)
			foreach ($modules as $module => $state) {
				$val = explode(".", $state);
				if ($val[1] == 1) {
					if (($id_hook = Hook::getIdByName($hook)) !== false) {
						$mInstance = Module::getInstanceByName($module);
						if (Validate::isLoadedObject($mInstance)) {
							$position = $mInstance->getPosition($id_hook);
							$way = (($val[0] >= $position) ? 1 : 0);
							if ($position)
								$res = $mInstance->updatePosition($id_hook, $way, $val[0]);
						}
					}
				}
				if ($val[1] == 0)
					$disabledList[$module] = $val[1];
				else
					$enabledList[$module] = $val[1];
			}

		foreach ($disabledList as $name => $state)
			if (array_key_exists($name, $enabledList))
				unset($disabledList[$name]);

		foreach ($disabledList as $name => $state)
			if (($this->isEn($name) == "enabled") && (!in_array($name, $skip))) {
				$mInstance = Module::getInstanceByName($name);
				if (Validate::isLoadedObject($mInstance))
					$mInstance->disable();
			}

		foreach ($enabledList as $name => $state)
			if ($this->isEn($name) == "disabled") {
				$mInstance = Module::getInstanceByName($name);
				if (Validate::isLoadedObject($mInstance))
					$mInstance->enable();
			}

			// put theme settings to last position
		if (($id_hook = Hook::getIdByName("displayHeader")) !== false) {
			$mInstance = Module::getInstanceByName($this->name);
			if (Validate::isLoadedObject($mInstance)) {
				$position = $mInstance->getPosition($id_hook);
				$sql = 'SELECT MAX(`position`) AS position FROM `'._DB_PREFIX_.'hook_module` WHERE `id_hook` = '.(int)$id_hook.' AND `id_shop` = '.(int)Context::getContext()->shop->id;
				if (!$max_pos = Db::getInstance()->getValue($sql))
					$max_pos = 0;
				if (($position) && ($position < $max_pos))
					$res = $mInstance->updatePosition($id_hook, 1, $max_pos+1);
			}
		}

	}

	public function checkupdates() {
		$update_list = "http://promokit.eu/share/updates/".strtolower($this->theme_name)."/update_list.dat";			
		$msg = "";		
		if (!$update = @file_get_contents($update_list)) {
			$msg = false;
		} else {			
			$versions = explode(",", $update);				
			$i = 1;
			foreach ($versions as $key => $version) {
				if ($this->theme_version < $version) {
					$msg .= (($i == 1) ? "" : ",").$version;
					$i++;
				}
			}			
		}
		return $msg;
	}

	private function themeUpdate($ver) {

		$err = false;
		$msg = "";
		$archive = "http://promokit.eu/share/updates/".strtolower($this->theme_name)."/".$ver.".zip";
		$filecontents = file_get_contents($archive);

		if ($filecontents == false) {
			$msg .= "<div class=\"conf error\">There is no file to update!</div>"; 
		} else {		
			$file = _PS_MODULE_DIR_.$this->name.'/update'.$ver.'.zip';			
			if (!@copy($archive, $file)) {
				$msg .= "<div class=\"conf error\">Can't download the file</div>"; 
			} else {
				$err = true;
				if (!Tools::ZipTest($file)) {
					$msg .= "<div class=\"conf error\">Zip file seems to be broken</div>";
				} else {
					$err = true;
					$zip = new ZipArchive;
					$res = $zip->open($file);
					if ($res === TRUE) {
					  $zip->extractTo(_PS_ROOT_DIR_.'/');
					  $zip->close();
					  unlink($file);

					  $update_folder = _PS_MODULE_DIR_.$this->name.'/upgrade';
					  if ($handle = opendir($update_folder)) {
						    while (false !== ($entry = readdir($handle)))
						        if ($entry != "." && $entry != ".." && $entry != "index.php")
						            $versions[] = str_replace(".php", "", $entry);
						    closedir($handle);
					  }
					  foreach ($versions as $version)
							if ($version > $this->version)
								include $update_folder.'/'.$version.'.php';

					} else {
					  $msg .= "<div class=\"conf error\">Unable to unzip updated files!</div>";
					}
				}
			}				

			if ($err == true)
				$msg .= "<div class=\"conf confirm\" style=\"width:300px; display: block !important\">Theme has been updated! Click to <strong>\"Apply Settings\"</strong> button →</div>";	
		}

		return $msg;

	}

	private function getEmails() {

		if (!$fileHolder = @fopen(_PS_MODULE_DIR_.$this->name."/maintenance/emails.txt", 'r')) {
			$readyEmails = "Cant open storage file";
		} else {	
			$readyEmails = "";
			$filecontents = file_get_contents(_PS_MODULE_DIR_.$this->name."/maintenance/emails.txt");
			$emails = explode(";", $filecontents);
			foreach ($emails as $email)
				if (Validate::isEmail($email))
					$filtered[] = $email;

			if (!empty($filtered))
				$readyEmails = array_unique($filtered);
			fclose($fileHolder);
		}
		return $readyEmails;

	}

	private function emailFilesUpdate() {

		$err = false;
		$msg = "";
		$file_headers = @get_headers($source);

		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$msg .= "<div class=\"conf error\">There is no file to update!</div>"; 
		} else {		
			$file = _PS_MODULE_DIR_.$this->name.'/mail.zip';			
			if (!@copy($this->mailArchive, $file)) {
				$msg .= "<div class=\"conf error\">Can't download the file</div>"; 
			} else {
				$err = true;
				if (!Tools::ZipTest($file)) {
					$msg .= "<div class=\"conf error\">Zip file seems to be broken</div>";
				} else {
					$err = true;
					$zip = new ZipArchive;
					$res = $zip->open($file);
					if ($res === TRUE) {
					  $zip->extractTo(_PS_OVERRIDE_DIR_.'/classes/');
					  $zip->close();
					  unlink($file);
					  if (file_exists(_PS_CACHE_DIR_.'class_index.php'))
					  	$msg .= $this->clearCache(_PS_CACHE_DIR_.'class_index.php');
					} else
					  	$msg .= "<div class=\"conf error\">Unable to unzip Mail.php file to \"override\" folder</div>";
				}
			}				

			if ($err == true)
				$msg .= "<div class=\"conf confirm\">Mail.php updated</div>";	
		}

		return $msg;

	}

	private function addImage($image, $name, $sid, $lid, $tab_num) {	
		$errors = "";
		if (isset($image[$name]) && isset($image[$name]['tmp_name']) && !empty($image[$name]['tmp_name']))
		{			
			if ($error = ImageManager::validateUpload($image[$name], Tools::convertBytes(ini_get('upload_max_filesize')))) 
				$errors = $error;

				if ($errors == "Image format not recognized, allowed formats are: .gif, .jpg, .png") {

					$errors = "Images extension wrong!";				

				} elseif ($dot_pos = strrpos($image[$name]['name'], '.')) {

					$imgname = $name;
					$ext = substr($image[$name]['name'], $dot_pos + 1);
					$newname = $name.'-'.(int)$this->context->shop->id;

					if (!move_uploaded_file($image[$name]['tmp_name'], _PS_MODULE_DIR_.$this->name.'/images/upload/'.$newname.'.'.$ext)) {
						$result["error"] .= $this->l('Error move uploaded file');
					}
					else {
						$imgname = $newname;
					}

					$sql = array();
					$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "'.$imgname.'.'.$ext.'" WHERE name = "'.$name.'" AND id_shop = '.$sid.';';
					$this->runSql($sql);					

				}				

		} else
			$errors = "No image to upload";

		$this->savePosition($tab_num);

		if ($errors)
			$errors = '<div class="conf error">'.$errors.'</div>'; 
		else 
			$errors = "";			
		$result["error"] = $errors;	

		return $result;

	}

	private function deleteImg($img, $name, $tab_num, $sid) {

		if (file_exists(_PS_MODULE_DIR_.$this->name.'/images/upload/'.$img)) {

			unlink(_PS_MODULE_DIR_.$this->name.'/images/upload/'.$img);

			$sql = array();
			$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "" WHERE name = "'.$name.'" AND id_shop = '.$sid.';';
			$sql[] = 'UPDATE `'.$this->mdb.'` SET value = "'.$tab_num.'" WHERE name = "tab_number" AND id_shop = '.$sid.';';
			$this->runSql($sql);
			$msg = '<div class="conf confirm">'.$this->l('Image removed').'</div>';

		} else
			$msg = '<div class="conf error">'.$this->l('No image to delete').'</div>';

		return $msg;

	}

	private function fontNameAdaptation($name) {
		return str_replace( ' ', '+', $name ); 
	}

	public function cssWriter($data) {
		
		$files = array();
		$files[0] = $this->customcssFile;
		$files[1] = $this->generatedFile;

		foreach ($files as $key => $file) {
			if ($key == 0)				
				$styles = $data;
			if ($key == 1)
				$styles = $this->cssGenerator();				
			if (!$fileHolder = @fopen($file, 'w')) {
				$this->errors .= $this->l('Cant open settings file!');
			} else {	
				if (fwrite($fileHolder, $styles) === FALSE)
					$this->errors .= $this->l('Cant write settings!');
				fclose($fileHolder);
			}
		}		

	}

	public function cssGenerator() {			

		$id_shop = (int)$this->context->shop->id;
		$s = $this->getOptions("cssGenerator");					
		$pattern = $bg_image = "";
		$bg = "background-color: ".$s['back_color'];
		if ($s["pattern"] != "" && $s["pattern"] != 0) {
			$pattern = "
			background-image: url(../images/patterns/back_".$s["pattern"].".png) !important; 
			background-repeat: repeat !important; 
			background-position:50% top !important;";
		}
		if ($s["back_image"] != "") {
			$bg_image = "
			background-image: url(../images/upload/".$s["back_image"].") !important; 
			background-repeat:".$s['back_repeat']." !important; 
			background-attachment: fixed;
			background-position:".$s['back_position']." top !important;";
		}

		$css = "";
		$css .= $this->selectors["first_color"]." {
				color:".$s['first_color']."}\n";
		$css .= $this->selectors["first_color_back"]." {
				background-color:".$s['first_color']."}\n";
		$css .= $this->selectors["first_border_color"]." {
				border-color:".$s['first_color']."}\n";
		$css .= $this->selectors["first_color_shadow"]." {
				box-shadow: 0 0 0 1px ".$s['first_color'].",0 0 0 2px ".$s['first_color']."}\n";
		$css .= $this->selectors["second_color"]." {
				color:".$s['second_color']."}\n";
		$css .= $this->selectors["second_color_back"]." {
				background-color:".$s['second_color']."}\n";
		$css .= $this->selectors["second_color_shadow"]." {
				box-shadow: 0 0 0 1px ".$s['second_color'].",0 0 0 2px ".$s['second_color']."}\n";
		$css .= $this->selectors["second_border_color"]." {
				border-color:".$s['second_color']."}\n";
		$css .= $this->selectors["price"]." {
				color:".$s['price_color']."}\n";
		/*$css .= $this->selectors["third_color"]." {
				color:".$s['third_color']."}\n";
		$css .= $this->selectors["third_color_back"]." {
				background-color:".$s['third_color']."}\n";*/
		$css .= $this->selectors["logo_container"].", ".$this->selectors["logo_container_admin"]. "{
				left:".$s['logo_left']."px; top:".$s['logo_top']."px; }\n";
		$css .= $this->selectors["logo"].", ".$this->selectors["logo_admin"] ." {
				font: normal ".$s['logo_size']."px/".$s['logo_lh']."px \"".$s['logo_font']."\";				
				color:".$s['logo_color']."\n}\n";
		$css .= $this->selectors["slogan"].", ".$this->selectors["slogan_admin"] ." {
				font-size:".$s['slogan_size']."px; 
				font-family:\"".$s['slogan_font']."\"; 
				color:".$s['slogan_color']."}\n";
		$css .= $this->selectors["buttons"]." {
				background-color: ".$s['buttons_color']."; 
				color: ".$s['buttons_text_color']."; 
				font-family: \"".$s['buttons_font']."\"}\n";
		$css .= $this->selectors["buttons_hover"]." {
				background-color: ".$s['buttons_hover_color']."; 
				color: ".$s['buttons_hover_text_color']."}\n";
		$css .= $this->selectors["headings"].", ".$this->selectors["headings_admin"]." {
				font-family: \"".$s['heading_font']."\";}\n";
		$css .= $this->selectors["subheadings"].", ".$this->selectors["subheadings_admin"]." {
				font-family: \"".$s['subheading_font']."\";}\n";
		$css .= $this->selectors["text"].", ".$this->selectors["text_admin"]." {
				font-family: \"".$s['text_font']."\";}\n";
		$css .= $this->selectors["price"]." {
				font-family: \"".$s['price_font']."\";}\n";
		/*$css .= $this->selectors["links"].", ".$this->selectors["links_admin"]." {
					color: ".$s['links_color']."}\n"
				.$this->selectors["links_admin"].":hover{
					color: ".$s['hover_links_color']."}\n";*/
		/*		##### 		temporary styles 		####	*/
		$css .= $this->selectors["bg"]." {".$bg."}\n";
		$css .= $this->selectors["bg_image"]." {".$bg_image."}\n";
		$css .= $this->selectors["additionalBg"]." {".$pattern."}\n";
		if ($s["search_pos"] != "" && $s["search_pos"] == 1)
			$css .= "body #search_block_top {right:auto; left:0}";

		return $css;
	}

	public function maintenanceDate($s)	{  // get sizes

		$ready_date = "";		
		$el = array('min', 'hour', 'day', 'month', 'year');
		$monthes = array("0"=>"January","1"=>"February","2"=>"March","3"=>"April","4"=>"May","5"=>"June","6"=>"July","7"=>"August","8"=>"September","9"=>"October","10"=>"November","11"=>"December");

		foreach ($el as $key => $value) {
			$from = 0;
			$ready_date .= '<select name="ready_'.$value.'">';
			switch($value){
				case 'min':	
					$until = 59;
					$ready_date .= "<option disabled='disabled'>".$value."</option>";
					break;
				case 'hour':
					$until = 23;
					$ready_date .= "<option disabled='disabled'>".$value."</option>";
					break;
				case 'day':	
					$until = 31;
					$ready_date .= "<option disabled='disabled'>".$value."</option>";
					break;
				case 'month':
					$until = 11;
					$ready_date .= "<option disabled='disabled'>".$value."</option>";
					break;
				case 'year':
					$from = date("Y");$until = (date("Y")+1);
					$ready_date .= "<option disabled='disabled'>".$value."</option>";
					break;
				default:
					break;
			}						
			for ($time=$from; $time <= $until; $time++) {				
				if ($value == "month") $tm = $monthes[$time]; else $tm = $time;
				$ready_date .= '<option '.(($time == $s["ready_".$value.""]) ? 'selected' : '').' value="'.$time.'">'.$tm.'</option>';
			}					
			$ready_date .= '</select>';
		}					

		return $ready_date;

	}

	public function optionList($fontType)	{  // create a <option> list for <select>

		$fontsOption = "";
		foreach ($this->fontslist() as $key => $font) { // get fonts list
			if ($fontType == $font)
				$selected = "selected";
			else
				$selected = "";

			$fontsOption .= '<option '.$selected.' value="'.$font.'">'.$font.'</option>';					   
		}
		return $fontsOption;

	}

	public function getOptions($who = false)	{  // get options from database
		$query = 'SELECT * FROM `'.$this->mdb.'` WHERE id_shop = '.(int)$this->context->shop->id.';';		
		if (!$sett = Db::getInstance()->ExecuteS($query)) return false;
		
		foreach ($sett as $key => $item)		
			foreach ($item as $k => $value) {				
				if ($k == "name") $n = $value;
				if ($k == "value") $v = $value;
				if (isset($v) && isset($n)) $s[$n] = $v;				
			}

		return $s;
	}

	public function getModulesState()	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT * FROM `'.$this->hdb.'` WHERE id_shop = '.$this->context->shop->id.';')) return false;

		foreach ($sett as $key => $section)
			$s[$section["hook"]][$section["module"]] = $section["ordr"].".".$section["value"];

		return $s;
	}

	public function getSizes($list, $type)	{  // get sizes

		$sizes = "";
		for ($i = 10; $i < $this->maxSize; $i++) {
			if ($list[$type] == $i)
				$selected = "selected";
			else
				$selected = "";
			$sizes .= '<option '.$selected.' value="'.$i.'">'.$i.'px</option>';	
		}
		return $sizes;

	}

	public function readCustomCSS()	{  // get sizes

		if (!$fileHolder = @fopen($this->customcssFile, 'r')) {
			$this->errors .= "Can't open css file!";
			return false;
		} else {	
			if (filesize($this->customcssFile) > 0) {
				$custom_css = fread($fileHolder, filesize($this->customcssFile));
				fclose($fileHolder);
			} else $custom_css = "";
			return $custom_css;
		}

	}

	public function menuIcons() {
		
		$homeCategories = Category::getHomeCategories(Context::getContext()->language->id, true, (int)Context::getContext()->shop->id);
		$iconsPath = $this->_path."images/upload/";
		$html = "<ul class='menu-icons'>";
		$rev = date("H").date("i").date("s")."\n";
		clearstatcache();	
		foreach ($homeCategories as $category) {
			$icon = $iconsPath.$category["id_category"]."-".(int)Context::getContext()->shop->id;
			if (file_exists($_SERVER['DOCUMENT_ROOT'].$icon.".jpg"))
				$icon = $icon.".jpg";
			elseif (file_exists($_SERVER['DOCUMENT_ROOT'].$icon.".png"))
				$icon = $icon.".png";
			elseif (file_exists($_SERVER['DOCUMENT_ROOT'].$icon.".gif"))
				$icon = $icon.".gif";
			else
				$icon = $iconsPath."default-icon.jpg";
			$html .= "<li><img src='".$icon."?".$rev."' alt='' width='19' height='19' /><span>".$category["name"]."</span><input type='file' name='cat[".$category["id_category"]."]' /></li>";
		}
		$html .= "</ul><input type='submit' class='button button-inner' name='cat_image' />";
		return $html;

	}

	public function getModules()	{  // get sizes		

		$mState = $this->getModulesState();		
		
		$token = Tools::getAdminToken('AdminModules'.(Tab::getIdFromClassName('AdminModules')).$this->context->employee->id);
		$preview = $this->_path."images/backoffice/shop-bg/";

		if (!empty($mState))
			foreach ($mState as $key => $value)
				$hooks[] = $key;

		if (isset($hooks))
			foreach ($hooks as $hook)
				if ($id_hook = Hook::getIdByName($hook)) {
					$modules = Hook::getModulesFromHook($id_hook);
					if ($modules)
						$moduleList[$hook] = $modules;
				}		

		$html = "<div>";
		if (isset($moduleList))
			foreach ($moduleList as $hook_name => $modules) {
				$html .= "<div class='hook-section hook-".$hook_name." hook-".Hook::getIdByName($hook_name)."'>";
				$html .= "<div class='hook-name'>".$this->l('Hook (Position): ').$hook_name."</div><div class='sortable'>";
					$counter = 1;
					foreach ($modules as $id => $module) {
						$curr = array(0,0);
						if (isset($mState[$hook_name][$module['name']]))
							$curr = explode(".", $mState[$hook_name][$module['name']]);
						$html .= "<div draggable='true' id='".$module['id_hook']."_".$module['id_module']."' data-modid='".$module['id_module']."' data-hookid='".$module['id_hook']."' data-modnum='".$counter."' class='draggable module-section variant switch module-".$module['name']." num-".$counter." ".(($curr[1] == 0) ? "mod-disabled " : "")."'>";
						$html .= "<div class='counter'>".$counter.".</div><img class='module-icon' src='../modules/".$module['name']."/logo.png' alt=".stripslashes($module['name'])." />";
						$html .= "<div class='module-name dragHandle' title='Drag and Drop to change order'> ".$module['name']."</div>";						
						$html .= "<div class='module-action'>";
						if (isset($mState[$hook_name][$module['name']]))
							$html .= "<input type='hidden' name='ordr[".$hook_name."][".$module['name']."]' value='".$counter."' /><input type='radio' name='modules[".$hook_name."][".$module['name']."]' id='".$hook_name."_".$module['name']."-on' value='1' ".(($curr[1] == 1) ? "checked " : "")."/><input type='radio' name='modules[".$hook_name."][".$module['name']."]' id='".$hook_name."_".$module['name']."-off' value='0' ".(($curr[1] == 0) ? "checked " : "")."/><label for='".$hook_name."_".$module['name']."-on' class='cb-enable".(($curr[1] == 1) ? " sel" : "")."'><span>".$this->l('Show')."</span></label><label for='".$hook_name."_".$module['name']."-off' class='cb-disable".(($curr[1] == 0) ? " sel" : "")."'><span>".$this->l('Hide')."</span></label>";
						$html .= "<a target='_blank' class='btn btn-default' href='index.php?controller=AdminModules&amp;configure=".$module['name']."&amp;token=".$token."'><i class='icon-pencil'></i>Edit</a></div>";
						if ($this->isEn($module['name']) == "disabled")
							$html .= "<div class='not-active module-state' title='".$this->l('This module is disabled. Click to "Show" button to enable it')."'>".$this->l('Not active')."</div>";
						if (file_exists($_SERVER['DOCUMENT_ROOT'].$preview.$module['name'].".jpg"))
							$html .= "<div class='module-preview hide'><img src='".$preview.$module['name'].".jpg' alt=''></div>";
						$html .= "<br class='clear' /></div>";
						$counter++;
					}
				$html .= "</div></div>";
			}
		$html .= "</div>";

		return $html;

	}

	public function ajaxProcessUpdateModPositions($id_module, $id_hook, $way, $positions)
	{
		$position = (is_array($positions)) ? array_search($id_hook.'_'.$id_module, $positions) : null;
		$module = Module::getInstanceById($id_module);
		if (Validate::isLoadedObject($module))
			if ($module->updatePosition($id_hook, $way, $position))
				die(true);
			else
				die('{"hasError" : true, "errors" : "Cannot update module position."}');
		else
			die('{"hasError" : true, "errors" : "This module cannot be loaded."}');
	}

	public function displayForm($message)
	{						

		//$fnts = json_decode(file_get_contents( 'https://www.googleapis.com/webfonts/v1/webfonts?key=YOURKEY' ));
		//foreach ($fnts->items as $key => $font) echo $font->family."\n";	
		$s = $this->getOptions();
		$mState = $this->getModulesState();
		$modules_list = $this->getModules();

		$id_shop = (int)Context::getContext()->shop->id;
		$rev = date("H").date("i").date("s")."\n";	
		$imgPath = $_SERVER['DOCUMENT_ROOT'].$this->_path.'/images/upload/';					
		$available_updates = "<span>".(($this->checkupdates() == "") ? "No available updates at the moment." : "Available versions: ".$this->checkupdates())."</span>";
		$email_list = $this->getEmails();
		$emails = "";
		if ($email_list)
			foreach ($email_list as $email)
				$emails .= "<span>".$email."</span>";
			
		$sett_str = $styles = "";
		for ($i = 0; $i < $this->patternsQuantity; $i++) { // get patterns

			if ($s["pattern"] == $i) {
				$checked = "checked=\"maxchecked\"";
				$ptClass = "selected";
			} else {
				$checked = "";
				$ptClass = "";
			}
			if ($i == 0) {
				$title = "title=\"No pattern\"";
			} else {$title = "";}
			$sett_str .= "<label ".$title." for=\"back_".$i."\" class=\"cell back_".$i." ".$ptClass."\" onclick=\"$('#ptrns label').removeClass('selected');$(this).addClass('selected');\">							
			<input type=\"radio\" id=\"back_".$i."\" name=\"pattern\" value=\"".$i."\" class=\"var\" $checked /></label>";
		}

		$fonts = $this->optionList($s["logo_font"]);
		$sl_fonts = $this->optionList($s["slogan_font"]);
		$h_fonts = $this->optionList($s["heading_font"]);
		$sh_fonts = $this->optionList($s["subheading_font"]);		
		$text_fonts = $this->optionList($s["text_font"]);
		$price_fonts = $this->optionList($s["price_font"]);
		$buttons_fonts = $this->optionList($s["buttons_font"]);

		$logo_sizes = $this->getSizes($s, "logo_size");
		$logo_lh = $this->getSizes($s, "logo_lh");
		$slogan_sizes = $this->getSizes($s, "slogan_size");
		$protocol = Tools::getShopProtocol();
		
		$link_params = "rel=\"stylesheet\" type=\"text/css\" href=\"".$protocol."fonts.googleapis.com/css?family=";
		$fontFiles = "";

		/*	####	if not default font, include it ####	*/
		if (!(in_array($s["logo_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"logofont_link\" ".$link_params.$this->fontNameAdaptation($s["logo_font"])."\">";
		if (!(in_array($s["slogan_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"sloganfont_link\" ".$link_params.$this->fontNameAdaptation($s["slogan_font"])."\">";
		if (!(in_array($s["heading_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"headingsfont_style\" ".$link_params.$this->fontNameAdaptation($s["heading_font"])."\">";
		if (!(in_array($s["subheading_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"subheadingsfont_style\" ".$link_params.$this->fontNameAdaptation($s["subheading_font"])."\">";
		if (!(in_array($s["text_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"textfont_style\" ".$link_params.$this->fontNameAdaptation($s["text_font"])."\">";
		if (!(in_array($s["price_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"pricefont_style\" ".$link_params.$this->fontNameAdaptation($s["price_font"])."\">";
		if (!(in_array($s["buttons_font"], $this->defaultFonts)))
			$fontFiles .= "<link id=\"buttonsfont_style\" ".$link_params.$this->fontNameAdaptation($s["buttons_font"])."\">";

		$ready_date = $this->maintenanceDate($s); // get maintenance date		

		$tabs = "";
		foreach ($this->tabs as $num => $name)
			$tabs .= '<div class="tab '.(($s["tab_number"] == ($num+1)) ? 'selected' : '').'" id="tab_menu_'.($num+1).'"><div class="link">'.$name.'</div><div class="arrow"></div></div>';

		$logo = "";
		if (file_exists(_PS_IMG_DIR_.Configuration::get('PS_LOGO', null, null, $id_shop)))
			$logo = __PS_BASE_URI__."img/".Configuration::get('PS_LOGO', null, null, $id_shop);

		$options = $this->getCMSOptions(1, 1, (int)$s["maintanance_cms_page"]);
		return $fontFiles.'
		<style type="text/css">
			#tab_content_4 .button:hover {background:'.$s["buttons_hover_color"].'; color:'.$s["buttons_hover_text_color"].'}
			#tab_content_4 .button {background:'.$s["buttons_color"].'; color:'.$s["buttons_text_color"].'; font-family:'.$s["buttons_font"].'}
			#heading-example {font-family:'.$s["heading_font"].'; color: '.$s["headings_color"].'}
			#text-example {font-family:'.$s["text_font"].'; color: '.$s["text_color"].'}
			#price-example {font-family:'.$s["price_font"].'}
			#link-example a { color: '.$s["links_color"].'}
			#link-example a:hover { color: '.$s["hover_links_color"].'}
		</style>
		<script>var tsDir = "'.$this->_path.'"</script>
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data" class="list_options" id="themesettings ts-prefix" method="post">
			<fieldset>
				<div class="tabscontainer">
					<div class="heading">
						<div class="module-title">
						<img src="'.$this->_path.'logo.png" width="16" height="16" alt="" title="" />'.$this->l('Theme Settings').'
						</div>
						<div class="buttons_section">
							'.$message.'							
							<input type="submit" name="submitThemeSettings" value="'.$this->l('Apply Settings').'" class="button" />
						</div>
					</div>
					<div class="tabs">						
						'.$tabs.'
			        </div>			        
					<div class="curvedContainer">					
						<div class="tabcontent" id="tab_content_1" '.(($s["tab_number"] == 1) ? 'style="display:block"' : '').'>
						<input type="radio" class="hide" name="tab_number" id="tab_1" value="1" '.(($s["tab_number"] == 1) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<!--<div class="margin form">
								<h4>'.$this->l('Settings Panel on the front page').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="sett_panel" id="settpanel_on" value="1" '.(($s["sett_panel"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="sett_panel" id="settpanel_off" value="0" '.(($s["sett_panel"] == 0) ? 'checked ' : '').'/>
								    <label for="settpanel_on" class="cb-enable'.(($s["sett_panel"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="settpanel_off" class="cb-disable'.(($s["sett_panel"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>								
							</div>-->
							<div class="margin form">
								<h4>'.$this->l('Load Presets').'</h4>								
								<div class="variant switch clear">
								    <label title="Preset 1" class="preset-image preset1 '.(($s["preset"] == 0) ? 'selected' : '').'" for="preset1" id="preset1_lbl"><span>Alysum</span></label>
								    <label title="Preset 2" class="preset-image preset2 '.(($s["preset"] == 1) ? 'selected' : '').'" for="preset2" id="preset2_lb2"><span>Sequence</span></label>
								    <label title="Preset 3" class="preset-image preset3 '.(($s["preset"] == 2) ? 'selected' : '').'" for="preset3" id="preset3_lb2"><span>Classic</span></label>
								    <label title="Preset 4" class="preset-image preset4 '.(($s["preset"] == 3) ? 'selected' : '').'" for="preset4" id="preset4_lb2"><span>Complex</span></label>
								    <label title="Preset 5" class="preset-image preset5 '.(($s["preset"] == 4) ? 'selected' : '').'" for="preset5" id="preset5_lb2"><span>Column</span></label>
								    <label title="Preset 6" class="preset-image preset6 '.(($s["preset"] == 5) ? 'selected' : '').'" for="preset6" id="preset6_lb2"><span>Awkward</span></label>
								    <label title="Preset 7" class="preset-image preset7 '.(($s["preset"] == 6) ? 'selected' : '').'" for="preset7" id="preset7_lb2"><span>Full Page</span></label>
									<input class="invisible" type="radio" name="preset" id="preset1" value="0" '.(($s["preset"] == 0) ? 'checked="checked" ' : '').'/>									
									<input class="invisible" type="radio" name="preset" id="preset2" value="1" '.(($s["preset"] == 1) ? 'checked="checked" ' : '').'/>
									<input class="invisible" type="radio" name="preset" id="preset3" value="2" '.(($s["preset"] == 2) ? 'checked="checked" ' : '').'/>
									<input class="invisible" type="radio" name="preset" id="preset4" value="3" '.(($s["preset"] == 3) ? 'checked="checked" ' : '').'/>
									<input class="invisible" type="radio" name="preset" id="preset5" value="4" '.(($s["preset"] == 4) ? 'checked="checked" ' : '').'/>
									<input class="invisible" type="radio" name="preset" id="preset6" value="5" '.(($s["preset"] == 5) ? 'checked="checked" ' : '').'/>
									<input class="invisible" type="radio" name="preset" id="preset7" value="6" '.(($s["preset"] == 6) ? 'checked="checked" ' : '').'/>
									<div class="clear"></div>
								</div>								
							</div>
							<div class="margin form">
								<h4>'.$this->l('Icons for main categories').'</h4>
								<p class="variant switch">
								    <input type="radio" name="cat_icons" id="scroll_cat_icons_on" value="1" '.(($s["cat_icons"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_icons" id="scroll_cat_icons_off" value="0" '.(($s["cat_icons"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_cat_icons_on" class="cb-enable'.(($s["cat_icons"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="scroll_cat_icons_off" class="cb-disable'.(($s["cat_icons"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>										
								<p class="preference_description">* '.$this->l('This feature allows you to upload icons for main (top level) categories, and show them in the "Category block" module or in Vertical Flex Menu').'</p>					
								<div class="variant switch clear'.(($s["cat_icons"] == 0) ? ' hide' : '').'">
								    '.$this->menuIcons().'
								</div>								
							</div>
							<div class="margin form">
								<h4>'.$this->l('Legacy Images').'</h4>
								<p class="variant switch">
								    <input type="radio" name="legacy_img_name" id="scroll_legacy_img_name_on" value="alysum" '.(($s["legacy_img_name"] == "alysum") ? 'checked ' : '').'/>
								    <input type="radio" name="legacy_img_name" id="scroll_legacy_img_name_off" value="default" '.(($s["legacy_img_name"] == "default") ? 'checked ' : '').'/>
								    <label for="scroll_legacy_img_name_on" class="cb-enable'.(($s["legacy_img_name"] == "alysum") ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="scroll_legacy_img_name_off" class="cb-disable'.(($s["legacy_img_name"] == "default") ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>	
								<p class="preference_description">* '.$this->l('If you have updated Alysum from 3.x version choose "Yes". That allows you to use images with "_alysum" suffix').'</p>						
							</div>
							<div class="margin form">
								<h4>'.$this->l('Show a message about using cookies').' (<a target="_blank" href="http://www.cookielaw.org/the-cookie-law/">'.$this->l('For EU shops').'</a>).</h4>
								<p class="variant switch">
								    <input type="radio" name="use_cookie" id="scroll_use_cookie_on" value="1" '.(($s["use_cookie"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="use_cookie" id="scroll_use_cookie_off" value="0" '.(($s["use_cookie"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_use_cookie_on" class="cb-enable'.(($s["use_cookie"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="scroll_use_cookie_off" class="cb-disable'.(($s["use_cookie"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
								<p class="preference_description">* '.$this->l('If you have updated Alysum from 3.x version choose "Yes"').'</p>											
							</div>
							<div class="margin form">
								<h4>'.$this->l('Cascade modules appearing during page scroling').'</h4>
								<p class="variant switch">
								    <input type="radio" name="load_effect" id="scroll_load_effect_on" value="1" '.(($s["load_effect"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="load_effect" id="scroll_load_effect_off" value="0" '.(($s["load_effect"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_load_effect_on" class="cb-enable'.(($s["load_effect"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="scroll_load_effect_off" class="cb-disable'.(($s["load_effect"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>						
							</div>
							<div class="margin form" id="page-view">
								<h4>'.$this->l('Page width').'</h4>
								<div class="variant tt-wrapper">								
									<label class="illustrated '.(($s["widescreen"] == 1) ? 'selected' : '').'" for="widescreen_on" onclick="$(\'#page-view label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="widescreen_on_lbl">
										<img src="'.$this->_path.'/images/backoffice/widescreen_on.gif" alt="" />
										<span class="tt">'.$this->l('Full width').'</span>
									</label>
									<input class="invisible" type="radio" id="widescreen_on" name="widescreen" value="1" '.(($s["widescreen"] == 1) ? 'checked="checked" ' : '').'/>
									<label class="illustrated '.(($s["widescreen"] == 0) ? 'selected' : '').'" for="widescreen_off" onclick="$(\'#page-view label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="widescreen_off_lbl">
											<img src="'.$this->_path.'/images/backoffice/widescreen_off.gif" alt="" />
											<span class="tt">'.$this->l('Fixed width').'</span>
									</label>
									<input class="invisible" type="radio" name="widescreen" id="widescreen_off" value="0" '.(($s["widescreen"] == 0) ? 'checked="checked" ' : '').'/>
									<div class="clear"></div>
								</div>
							</div>											
							<div class="margin form" id="page-layout">
								<h4>'.$this->l('Page Layout').'</h4>
								<div class="variant tt-wrapper">
									<label class="illustrated_medium '.(($s["column"] == "left") ? 'selected' : '').'" for="left_col" onclick="$(\'#page-layout label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
										<img src="'.$this->_path.'/images/backoffice/col_left.gif" alt="" />
										<input class="invisible" type="radio" id="left_col" name="column" value="left" '.(($s["column"] == "left") ? 'checked="checked" ' : '').'/>
										<span class="tt">'.$this->l('Left Column + Center Column').'</span>
									</label>
									<label class="illustrated_medium '.(($s["column"] == "right") ? 'selected' : '').'" for="right_col" onclick="$(\'#page-layout label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
										<img src="'.$this->_path.'/images/backoffice/col_right.gif" alt="" />
										<input class="invisible" type="radio" name="column" id="right_col" value="right" '.(($s["column"] == "right") ? 'checked="checked" ' : '').'/>
										<span class="tt">'.$this->l('Center Column + Right Column').'</span>
									</label>
									<!--<label class="illustrated_medium nomargin '.(($s["column"] == "nocol") ? 'selected' : '').'" for="nocol" onclick="$(\'#page-layout label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
										<img src="'.$this->_path.'/images/backoffice/col_no.gif" alt="" />
										<input class="invisible" type="radio" name="column" id="nocol" value="nocol" '.(($s["column"] == "nocol") ? 'checked="checked" ' : '').'/>
										<span class="tt">'.$this->l('No columns').'</span>
									</label>-->
									<div class="clear"></div>
								</div>															
							</div>
							<div class="margin form">
								<h4>'.$this->l('"Sticky" menu').'</h4>
								<p class="variant switch">
								    <input type="radio" name="sticky_menu" id="scroll_sticky_menu_on" value="1" '.(($s["sticky_menu"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="sticky_menu" id="scroll_sticky_menu_off" value="0" '.(($s["sticky_menu"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_sticky_menu_on" class="cb-enable'.(($s["sticky_menu"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="scroll_sticky_menu_off" class="cb-disable'.(($s["sticky_menu"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>							
							</div>
							<div class="margin form">
								<h4>'.$this->l('Contact block in the header').'</h4>
								<p class="variant switch">
								    <input type="radio" name="c_block" id="scroll_c_block_on" value="1" '.(($s["c_block"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="c_block" id="scroll_c_block_off" value="0" '.(($s["c_block"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_c_block_on" class="cb-enable'.(($s["c_block"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="scroll_c_block_off" class="cb-disable'.(($s["c_block"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>							
							</div>
							<div class="margin form">
								<h4>'.$this->l('Position of search block').'</h4>
								<p class="variant switch">
								    <input type="radio" name="search_pos" id="scroll_search_pos_on" value="1" '.(($s["search_pos"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="search_pos" id="scroll_search_pos_off" value="0" '.(($s["search_pos"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_search_pos_on" class="cb-enable'.(($s["search_pos"] == 1) ? ' sel' : '').'"><span>'.$this->l('Left').'</span></label>
								    <label for="scroll_search_pos_off" class="cb-disable'.(($s["search_pos"] == 0) ? ' sel' : '').'"><span>'.$this->l('Right').'</span></label>
								</p>							
							</div>
							<div class="margin form">
								<h4>'.$this->l('"Scroll to Top" Button').'</h4>
								<p class="variant switch">
								    <input type="radio" name="toTop" id="scroll_totop_on" value="1" '.(($s["toTop"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="toTop" id="scroll_totop_off" value="0" '.(($s["toTop"] == 0) ? 'checked ' : '').'/>
								    <label for="scroll_totop_on" class="cb-enable'.(($s["toTop"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="scroll_totop_off" class="cb-disable'.(($s["toTop"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>							
							</div>
							<div class="margin form">
								<h4>'.$this->l('Stylized Tooltips').'</h4>
								<p class="variant switch">
								    <input type="radio" name="tooltips" id="tooltips_on" value="1" '.(($s["tooltips"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="tooltips" id="tooltips_off" value="0" '.(($s["tooltips"] == 0) ? 'checked ' : '').'/>
								    <label for="tooltips_on" class="cb-enable'.(($s["tooltips"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="tooltips_off" class="cb-disable'.(($s["tooltips"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>								
							</div>
							<!--<div class="margin form">
								<h4>'.$this->l('Allow to hide modules content on mobile devices').'</h4>
								<p class="variant switch">
								    <input type="radio" name="mobileBlocks" id="mobileBlocks_on" value="1" '.(($s["mobileBlocks"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="mobileBlocks" id="mobileBlocks_off" value="0" '.(($s["mobileBlocks"] == 0) ? 'checked ' : '').'/>
								    <label for="mobileBlocks_on" class="cb-enable'.(($s["mobileBlocks"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="mobileBlocks_off" class="cb-disable'.(($s["mobileBlocks"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<p class="preference_description">* '.$this->l('This feature can be usefull to make your shop more compact on mobile devices. You will get "+" buttons near the module block title which allows your customers show or hide module content.').'</p>
							</div>
							<div class="margin form">
								<h4>'.$this->l('Duplicate slides to background').'</h4>
								<p class="variant switch">
								    <input type="radio" name="sliderbg" id="sliderbg_on" value="1" '.(($s["sliderbg"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="sliderbg" id="sliderbg_off" value="0" '.(($s["sliderbg"] == 0) ? 'checked ' : '').'/>
								    <label for="sliderbg_on" class="cb-enable'.(($s["sliderbg"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="sliderbg_off" class="cb-disable'.(($s["sliderbg"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<p class="preference_description">* '.$this->l('This feature works only with default prestashop "HomeSlider"').'</p>
							</div>-->
							<div class="margin form" id="coordinates">
								<h4>'.$this->l('Map on the bottom of the shop').'</h4>
								<p class="variant switch">
								    <input type="radio" name="show_map_bottom" id="show_map_bottom_on" value="1" '.(($s["show_map_bottom"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="show_map_bottom" id="show_map_bottom_off" value="0" '.(($s["show_map_bottom"] == 0) ? 'checked ' : '').'/>
								    <label for="show_map_bottom_on" class="cb-enable'.(($s["show_map_bottom"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="show_map_bottom_off" class="cb-disable'.(($s["show_map_bottom"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p><br/>
								<h4>'.$this->l('Map on the Contact Page').'</h4>
								<p class="variant switch">
								    <input type="radio" name="show_map" id="show_map_on" value="1" '.(($s["show_map"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="show_map" id="show_map_off" value="0" '.(($s["show_map"] == 0) ? 'checked ' : '').'/>
								    <label for="show_map_on" class="cb-enable'.(($s["show_map"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="show_map_off" class="cb-disable'.(($s["show_map"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p><br/>
								<div class="variant" id="coordinates-info">
									<label class="t textinput" for="location">'.$this->l('Your Location').':</label>
									<input type="text" size="30" name="location" id="location" value="'.$s["location"].'" /><br/>
									&nbsp;&nbsp;'.$this->l('Latitude').': <span class="lat">'.$s["location_lat"].'</span>;&nbsp;&nbsp;'.$this->l('Longtitude').': <span class="lon">'.$s["location_lng"].'</span>
									<input type="hidden" name="location_lng" id="location_lng" value="'.$s["location_lng"].'"/>
									<input type="hidden" name="location_lat" id="location_lat" value="'.$s["location_lat"].'"/>
									<br/>
								</div>								
							</div>
							<div class="margin form">
								<h4>'.$this->l('Text in the footer section').'</h4>
								<textarea id="footer_text" name="footer_text" cols="26" rows="3">'.$s["footer_text"].'</textarea>
								<p class="preference_description">* '.$this->l('You can use html tags in this field').'</p>
							</div>								
							<div class="margin form">
								<h4>'.$this->l('Theme Updater (Update theme to latest version in one click)').'</h4>
								<input class="button'.(($this->checkupdates() == "") ? " hide" : "").'" type="submit" name="aupdate" value="'.$this->l('Update Theme').'" />
								<div class="getupdate-result">'.$available_updates.'</div>
								<p class="note'.(($this->checkupdates() == "") ? " hide" : "").'">'.$this->l('Attention! Theme update will override some files of theme and modules. Do not forget to backup your modifications and translations before the start!').'</p><br/>							
							</div>
						</div>
					</div>
					<div class="tabcontent" id="tab_content_2" '.(($s["tab_number"] == 2) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_2" value="2" '.(($s["tab_number"] == 2) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<div class="margin form">
								<h4>'.$this->l('Background Color').'</h4>								
								<div class="variant">
									<input type="color" name="back_color" id="back_color" class="colorpicker" data-hex="true" value="'.$s["back_color"].'" />									
									<label class="t textinput" for="back_color"></label><br />
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Background Image').'</h4>
								<div class="variant">
									<div class="back_image_container">
										'.(($s["back_image"] != "") ? '<img class="back_image" src="'.$this->_path.'images/upload/'.$s["back_image"].'?'.$rev.'">' : 'No Image').'
									</div>									
									<input id="back_image" type="file" name="back_image">
									<input id="bimage" type="submit" class="button" name="back_image_upload">
									<input class="button'.(($s["back_image"] == "") ? ' hide' : '').'" type="submit" name="submitDeleteImgConf" value="'.$this->l('Delete image').'" />
								</div>								
								<div class="'.(($s["back_image"] == "") ? 'hide' : '').'">
								<div class="option-separator"></div>
									<h4>'.$this->l('Background Position').'</h4>
									<div class="labels-wrapper variant tt-wrapper" id="backpos">
										<label class="illustrated_medium '.(($s["back_position"] == "left") ? 'selected' : '').'" for="back_position_left" onclick="$(\'#backpos label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/back_left.gif" alt="" />
											<input class="invisible" type="radio" name="back_position" id="back_position_left" value="left" '.(($s["back_position"] == "left") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Left Top').'</span>
										</label>
										<label class="illustrated_medium '.(($s["back_position"] == "center") ? 'selected' : '').'" for="back_position_center" onclick="$(\'#backpos label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/back_center.gif" alt="" />
											<input class="invisible" type="radio" name="back_position" id="back_position_center" value="center" '.(($s["back_position"] == "center") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Center Top').'</span>
										</label>
										<label class="illustrated_medium '.(($s["back_position"] == "right") ? 'selected' : '').'" for="back_position_right" onclick="$(\'#backpos label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/back_right.gif" alt="" />
											<input class="invisible" type="radio" name="back_position" id="back_position_right" value="right" '.(($s["back_position"] == "right") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Right Top').'</span>
										</label>
										<div class="clear"></div>
									</div>
									<h4>'.$this->l('Background Repeat').'</h4>
									<div class="labels-wrapper variant tt-wrapper" id="backrepeat">
										<label class="illustrated_small '.(($s["back_repeat"] == "no-repeat") ? 'selected' : '').'" for="back_rpt_no" onclick="$(\'#backrepeat label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/rpt-no.gif" alt="" />
											<input class="invisible" type="radio" name="back_repeat" id="back_rpt_no" value="no-repeat" '.(($s["back_repeat"] == "no-repeat") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('No Repeat').'</span>
										</label>
										<label class="illustrated_small '.(($s["back_repeat"] == "repeat-x") ? 'selected' : '').'" for="back_rpt_x" onclick="$(\'#backrepeat label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/rpt_x.gif" alt="" />
											<input class="invisible" type="radio" name="back_repeat" id="back_rpt_x" value="repeat-x" '.(($s["back_repeat"] == "repeat-x") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Repeat Horizontally').'</span>
										</label>
										<label class="illustrated_small '.(($s["back_repeat"] == "repeat-y") ? 'selected' : '').'" for="back_rpt_y" onclick="$(\'#backrepeat label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/rpt_y.gif" alt="" />
											<input class="invisible" type="radio" name="back_repeat" id="back_rpt_y" value="repeat-y" '.(($s["back_repeat"] == "repeat-y") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Repeat Vertically').'</span>
										</label>
										<label class="illustrated_small '.(($s["back_repeat"] == "repeat") ? 'selected' : '').'" for="back_rpt" onclick="$(\'#backrepeat label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
											<img src="'.$this->_path.'/images/backoffice/rpt.gif" alt="" />
											<input class="invisible" type="radio" name="back_repeat" id="back_rpt" value="repeat" '.(($s["back_repeat"] == "repeat") ? 'checked="checked" ' : '').'/>
											<span class="tt">'.$this->l('Repeat All').'</span>
										</label>									
										<div class="clear"></div>
									</div>
									<div class="info">'.$this->l('If you want to use predefined pattern, please remove background image').'</div>
								</div>
								<br/>
								<div class="'.(($s["back_image"] != "") ? 'hide' : '').'">
									<div class="option-separator"></div>
									<h4>'.$this->l('Patterns').'</h4>
									<div class="sect labels-wrapper" id="ptrns">
										'.( $sett_str ).'
										<div class="clear"></div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<div class="tabcontent" id="tab_content_3" '.(($s["tab_number"] == 3) ? 'style="display:block"' : '').'>
						<input type="radio" class="hide" name="tab_number" id="tab_3" value="3" '.(($s["tab_number"] == 3) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<div class="margin form">
								<h4>'.$this->l('Logo type').'</h4>		
								<p class="variant switch">
								    <input type="radio" name="logo_type" id="logo_type_image" value="0" '.(($s["logo_type"] == 0) ? 'checked ' : '').'/>
								    <input type="radio" name="logo_type" id="logo_type_text" value="1" '.(($s["logo_type"] == 1) ? 'checked ' : '').'/>
								    <label for="logo_type_image" class="cb-enable'.(($s["logo_type"] == 0) ? ' sel' : '').'"><span>'.$this->l('Image').'</span></label>
								    <label for="logo_type_text" class="cb-disable'.(($s["logo_type"] == 1) ? ' sel' : '').'"><span>'.$this->l('Text').'</span></label>
								</p>
								<div class="option-separator"></div>
								<div id="logotype-text" '.(($s["logo_type"] == 0) ? 'class="hide" ' : '').'>
									<h4>'.$this->l('Logo options').'</h4>
									<div class="variant">
										<input type="text" size="20" name="logo_text" id="logo_text" value="'.$s["logo_text"].'" />
										<label class="t textinput" for="logo_text">'.$this->l('Shop Name').'</label>
									</div>
									<div class="variant">
										<input type="color" name="logo_color" id="logo_color" class="colorpicker" data-hex="true" value="'.$s["logo_color"].'" />
									</div>
									<label class="t" for="logo_font">'.$this->l('Font').':</label>
									<select name="logo_font" id="logofont">'.( $fonts ).'</select>
									<label class="t" for="logo_size">'.$this->l('Size').':</label>
									<select name="logo_size" id="logosize">'.( $logo_sizes ).'</select>			
									<label class="t" for="logo_lh">'.$this->l('Line-height').':</label>
									<select name="logo_lh" id="logolh">'.( $logo_lh ).'</select><br/><br/>
									<div class="preference_description">'.$this->l('Logo used in the header and footer').'</div>
									<div class="option-separator"></div>									
									<h4>'.$this->l('Slogan options').'</h4>
									<div class="variant">
										<input type="text" size="20" name="slogan" id="slogan" value="'.$s["slogan"].'" />
										<label class="t textinput" for="slogan">'.$this->l('Shop Slogan').'</label>
										<br/>
									</div>
									<div class="variant">
										<input type="color" name="slogan_color" id="slogan_color" class="colorpicker" data-hex="true" value="'.$s["slogan_color"].'" />
									</div>
									<label class="t" for="slogan_font">'.$this->l('Font').':</label>
									<select name="slogan_font" id="sloganfont">'.( $sl_fonts ).'</select>
									<label class="t" for="slogan_size">'.$this->l('Size').':</label>
									<select name="slogan_size" id="slogansize">'.( $slogan_sizes ).'</select><br/><br/>
									<div class="preference_description">'.$this->l('Slogan used in the header and footer').'</div>
									<div class="option-separator"></div>	
								</div>								
								<div id="logotype-image" '.(($s["logo_type"] == 1) ? 'class="hide" ' : '').'>	
									Upoad your logo <a href="index.php?controller=AdminThemes&token='.Tools::getAdminTokenLite('AdminThemes').'#conf_id_PS_LOGO" target="_blank">here:</a>					
									<div class="option-separator"></div>
								</div>								
								<h4>'.$this->l('Align center').'</h4>
								<div class="margin">									
									<p class="variant switch">
									    <input type="radio" name="logo_center" id="scroll_logo_center_on" value="1" '.(($s["logo_center"] == 1) ? 'checked ' : '').'/>
									    <input type="radio" name="logo_center" id="scroll_logo_center_off" value="0" '.(($s["logo_center"] == 0) ? 'checked ' : '').'/>
									    <label for="scroll_logo_center_on" class="cb-enable'.(($s["logo_center"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
									    <label for="scroll_logo_center_off" class="cb-disable'.(($s["logo_center"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
									</p>							
									<div class="option-separator"></div>
								</div>
								<h4>'.$this->l('Logo position').'</h4>								
								<div class="position_buttons">
									<div class="logo_top"></div>
									<div class="logo_left"></div>
									<div class="logo_right"></div>
									<div class="logo_bottom"></div>
									<input type="hidden" name="logo_top" id="logo_top" value="'.$s["logo_top"].'">
									<input type="hidden" name="logo_left" id="logo_left" value="'.$s["logo_left"].'">
									<span>logo</span>
								</div>	
								<div class="option-separator"></div>	
								<div class="font-sample">
									<div class="font-wrapper'.(($s["logo_center"] == 1) ? ' alcenter' : '').'">
										<div class="logo-container" style="top:'.$s["logo_top"].'px; left:'.$s["logo_left"].'px;">
											<span '.(($s["logo_type"] == 0) ? 'class="hide" ' : '').' id="logofont_example" style="font-size:'.$s["logo_size"].'px; line-height:'.$s["logo_lh"].'px; font-family:'.$s["logo_font"].'; color:'.$s["logo_color"].'">'.$s["logo_text"].'</span>
											<span '.(($s["logo_type"] == 0) ? 'class="hide" ' : '').' id="sloganfont_example" style="font-size:'.$s["slogan_size"].'px; line-height:18px; font-family:'.$s["slogan_font"].'; color:'.$s["slogan_color"].'">'.$s["slogan"].'</span>
											<img '.(($s["logo_type"] == 1) ? 'class="hide" ' : '').' src="'.$logo.'" alt="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tabcontent" id="tab_content_4" '.(($s["tab_number"] == 4) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_4" value="4" '.(($s["tab_number"] == 4) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<div class="margin form">
								<h4>'.$this->l('Buttons Colors').'</h4>
								<div class="show_area" id="page">										
									<a class="button">'.$this->l('Example').'</a>
								</div>								
								<div class="sect funct_area">
									<div class="variant">										
										<input type="color" name="buttons_color" class="colorpicker" data-hex="true" id="buttons_color" value="'.$s["buttons_color"].'" />
										<label class="t textinput" for="buttons_color">'.$this->l('Normal').'</label>
									</div>
									<div class="variant">										
										<input type="color" name="buttons_text_color" class="colorpicker" data-hex="true" id="buttons_text_color" value="'.$s["buttons_text_color"].'" />
										<label class="t textinput" for="buttons_text_color">'.$this->l('Normal text').'</label>
									</div>
									<div class="variant">										
									<input type="color" name="buttons_hover_color" class="colorpicker" data-hex="true" id="buttons_hover_color" value="'.$s["buttons_hover_color"].'" />
									<label class="t textinput" for="buttons_hover_color">'.$this->l('Hover').'</label>
									</div>
									<div class="variant">										
										<input type="color" name="buttons_hover_text_color" class="colorpicker" data-hex="true" id="buttons_hover_text_color" value="'.$s["buttons_hover_text_color"].'" />
										<label class="t textinput" for="buttons_hover_text_color">'.$this->l('Hover text').'</label><br>
									</div>
								</div>	
								<div class="option-separator"></div>
								<h4>'.$this->l('Buttons Font').'</h4>
								<select name="buttons_font" id="buttons_font">'.( $buttons_fonts ).'</select>
							</div>
						</div>
					</div>
					<div class="tabcontent" id="tab_content_5" '.(($s["tab_number"] == 5) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_5" value="5" '.(($s["tab_number"] == 5) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<div class="margin form">
								<h4>'.$this->l('Main font').'</h4>
								<div class="variant">
									<div class="show_area" id="heading-example"><h5>Text example</h5></div>
									<div class="funct_area">		
										<label class="t" for="heading_font">Font name:</label>	
										<select name="heading_font" id="heading_font">'.( $h_fonts ).'</select>						
										<!--<input type="color" name="headings_color" class="colorpicker" data-hex="true" id="headings_color" value="'.$s["headings_color"].'" />-->
									</div>
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Secondary Font').'</h4>
								<div class="variant">
									<div class="show_area" id="subheading-example"><h6>Text example</h6></div>
									<div class="funct_area">		
										<label class="t" for="subheading_font">Font name:</label>	
										<select name="subheading_font" id="subheading_font">'.( $sh_fonts ).'</select>
										<!--<input type="color" name="subheadings_color" class="colorpicker" data-hex="true" id="subheadings_color" value="'.$s["subheadings_color"].'" />-->
									</div>
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Normal Text Font').'</h4>
								<div class="variant">
									<div class="show_area" id="text-example">Text example</div>
									<div class="funct_area">		
										<label class="t" for="text_font">'.$this->l('Font name').':</label>
										<select name="text_font" id="text_font">'.( $text_fonts ).'</select>						
										<!--<input type="color" name="text_color" class="colorpicker" data-hex="true" id="text_color" value="'.$s["text_color"].'" />-->
									</div>
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Price Font').'</h4>
								<div class="variant">
									<div class="show_area" id="price-example">$199.75</div>
									<div class="funct_area">		
										<label class="t" for="price_font">'.$this->l('Font name').':</label>
										<select name="price_font" id="price_font">'.( $price_fonts ).'</select>						
										<!--<input type="color" name="price_color" class="colorpicker" data-hex="true" id="price_color" value="" />-->
									</div>
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Use cyrillic symbols').'</h4>
								<div class="margin">									
									<p class="variant switch">
									    <input type="radio" name="cyrillic" id="scroll_cyrillic_on" value="1" '.(($s["cyrillic"] == 1) ? 'checked ' : '').'/>
									    <input type="radio" name="cyrillic" id="scroll_cyrillic_off" value="0" '.(($s["cyrillic"] == 0) ? 'checked ' : '').'/>
									    <label for="scroll_cyrillic_on" class="cb-enable'.(($s["cyrillic"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
									    <label for="scroll_cyrillic_off" class="cb-disable'.(($s["cyrillic"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
									</p>																
								</div>
								<div class="option-separator"></div>
								<h4>'.$this->l('Use latin extended symbols').'</h4>
								<div class="margin">									
									<p class="variant switch">
									    <input type="radio" name="latin_ext" id="scroll_latin_ext_on" value="1" '.(($s["latin_ext"] == 1) ? 'checked ' : '').'/>
									    <input type="radio" name="latin_ext" id="scroll_latin_ext_off" value="0" '.(($s["latin_ext"] == 0) ? 'checked ' : '').'/>
									    <label for="scroll_latin_ext_on" class="cb-enable'.(($s["latin_ext"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
									    <label for="scroll_latin_ext_off" class="cb-disable'.(($s["latin_ext"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
									</p>							
								</div>
								<!--<div class="option-separator"></div>
								<h4>'.$this->l('Links').'</h4>
								<div class="variant">
									<div class="show_area" id="link-example"><a>'.$this->l('Just a link').'</a></div>
									<div class="funct_area">
										<div class="color_holder">											
											<div>'.$this->l('Normal').'</div>
											<input type="color" name="links_color" class="colorpicker" data-hex="true" id="links_color" value="'.$s["links_color"].'" />
										</div>
										<div class="color_holder">											
											<div>'.$this->l('Hover').'</div>
											<input type="color" name="hover_links_color" class="colorpicker" data-hex="true" id="hover_links_color" value="'.$s["hover_links_color"].'" />
										</div>
									</div>
								</div>-->
								<div class="option-separator"></div>
								<h4>'.$this->l('Theme Colors').':</h4>
								<div class="variant">
									<div class="funct_area">
										<div class="color_holder">											
											<div>'.$this->l('Main Color').'</div>
											<input type="color" name="first_color" class="colorpicker" data-hex="true" id="first_color" value="'.$s["first_color"].'" />
										</div>
										<div class="color_holder">											
											<div>'.$this->l('Secondary Color').'</div>
											<input type="color" name="second_color" class="colorpicker" data-hex="true" id="second_color" value="'.$s["second_color"].'" />
										</div>
										<div class="color_holder">											
											<div>'.$this->l('Price Color').'</div>
											<input type="color" name="price_color" class="colorpicker" data-hex="true" id="price_color" value="'.$s["price_color"].'" />
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>					
					<div class="tabcontent'.((($s["column"] != "nocol") && (($s["homepage_column"] == 1) ? ' sel' : '')) ? ' columns' : '').(($s["column"] == "right") ? ' right_column' : '').(($s["column"] == "left") ? ' left_column' : '').'" id="tab_content_6" '.(($s["tab_number"] == 6) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_6" value="6" '.(($s["tab_number"] == 6) ? 'checked="checked"' : '').' />
						<div class="va-content site-structure">
							<div class="margin form homepage_column'.(($s["column"] == "nocol") ? ' hide' : '').'">
								<h4>'.$this->l('Homepage Column').'</h4>
								<p class="variant switch">
								    <input type="radio" name="homepage_column" id="homepage_column_on" value="1" '.(($s["homepage_column"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="homepage_column" id="homepage_column_off" value="0" '.(($s["homepage_column"] == 0) ? 'checked ' : '').'/>
								    <label for="homepage_column_on" class="cb-enable'.(($s["homepage_column"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="homepage_column_off" class="cb-disable'.(($s["homepage_column"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>								
							</div>
							<div class="section-header"></div>
							'.$modules_list.'
							<div class="section-footer module-wrapper'.(($s["footer"] == 0) ? ' hidden-module' : '').'">
								<div class="module-bg">								
								<p class="variant switch">
								    <input type="radio" name="footer" id="footeron" value="1" '.(($s["footer"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="footer" id="footeroff" value="0" '.(($s["footer"] == 0) ? 'checked ' : '').'/>
								    <label for="footeron" class="cb-enable'.(($s["footer"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="footeroff" class="cb-disable'.(($s["footer"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								    <span class="module-name">'.$this->l('Footer Section').'</span>
								</p><br/>							
								</div>
							</div>
							<div class="section-footer_bottom module-wrapper'.(($s["footer_bottom"] == 0) ? ' hidden-module' : '').'">
								<div class="module-bg">								
								<p class="variant switch">
								    <input type="radio" name="footer_bottom" id="footer_bottomon" value="1" '.(($s["footer_bottom"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="footer_bottom" id="footer_bottomoff" value="0" '.(($s["footer_bottom"] == 0) ? 'checked ' : '').'/>
								    <label for="footer_bottomon" class="cb-enable'.(($s["footer_bottom"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="footer_bottomoff" class="cb-disable'.(($s["footer_bottom"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								    <span class="module-name">'.$this->l('Footer Bottom Section').'</span>
								</p><br/>							
								</div>
							</div>							
						</div>
					</div>
					<div class="tabcontent" id="tab_content_7" '.(($s["tab_number"] == 7) ? 'style="display:block"' : '').'>		
					<input type="radio" class="hide" name="tab_number" id="tab_7" value="7" '.(($s["tab_number"] == 7) ? 'checked="checked"' : '').' />
						<div class="va-content tt-wrapper">
							<div class="margin form" id="product-listing">
								<h4>'.$this->l('Listing View').'</h4>								
								<label class="illustrated '.(($s["view"] == 1) ? 'selected' : '').'" for="view_list" onclick="$(\'#product-listing label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="view_list_lbl">
									<img src="'.$this->_path.'/images/backoffice/view_list.gif" alt="" />
									<span class="tt">'.$this->l('List View').'</span>
								</label>
								<input class="invisible" type="radio" id="view_list" name="view" value="1" '.(($s["view"] == 1) ? 'checked="checked" ' : '').'/>
								<label class="illustrated '.(($s["view"] == 0) ? 'selected' : '').'" for="view_grid" onclick="$(\'#product-listing label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="view_grid_lbl">
										<img src="'.$this->_path.'/images/backoffice/view_grid.gif" alt="" />
										<span class="tt">'.$this->l('Grid View').'</span>
								</label>
								<input class="invisible" type="radio" name="view" id="view_grid" value="0" '.(($s["view"] == 0) ? 'checked="checked" ' : '').'/>
								<div class="clear"></div>
								<p class="preference_description">'.$this->l('Show the product listing as a grid or a list view').'</p>
							</div>
							<!--<div class="margin form '.(($s["view"] == 1) ? 'hide' : '').'" id="images">
							<h4>'.$this->l('Product Image Size').'</h4>								
								<label class="illustrated '.(($s["productImageSize"] == 1) ? 'selected' : '').'" for="images_big" onclick="$(\'#images label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
									<img src="'.$this->_path.'/images/backoffice/image_big.gif" alt="" />
									<span class="tt">'.$this->l('Big Product Images / 3 per row').'</span>
								</label>
								<input class="invisible" type="radio" id="images_big" name="productImageSize" value="1" '.(($s["productImageSize"] == 1) ? 'checked="checked" ' : '').'/>
								<label class="illustrated '.(($s["productImageSize"] == 0) ? 'selected' : '').'" for="images_small" onclick="$(\'#images label\').removeClass(\'selected\');$(this).addClass(\'selected\');">
										<img src="'.$this->_path.'/images/backoffice/image_small.gif" alt="" />
										<span class="tt">'.$this->l('Small Product Images / 4 per row').'</span>
								</label>
								<input class="invisible" type="radio" name="productImageSize" id="images_small" value="0" '.(($s["productImageSize"] == 0) ? 'checked="checked" ' : '').'/>
								<div class="clear"></div>
								<p class="preference_description">'.$this->l('Choose between 3 or 4 products per row').'</p>
							</div>-->
							<div class="margin form">
								<h4>'.$this->l('Category title and image on the category page').'</h4>
								<p class="variant switch">
								    <input type="radio" name="cat_title" id="cat_title_on" value="1" '.(($s["cat_title"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_title" id="cat_title_off" value="0" '.(($s["cat_title"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_title_on" class="cb-enable'.(($s["cat_title"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="cat_title_off" class="cb-disable'.(($s["cat_title"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>							
						</div>
						<div class="va-content tt-wrapper">
							<div class="margin form" id="subcategories">
								<h4>'.$this->l('Show subcategories').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="subcategories" id="show_subcat" value="1" '.(($s["subcategories"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="subcategories" id="hide_subcat" value="0" '.(($s["subcategories"] == 0) ? 'checked ' : '').'/>
								    <label for="show_subcat" class="cb-enable'.(($s["subcategories"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="hide_subcat" class="cb-disable'.(($s["subcategories"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="lc_buttons">
								<h4>'.$this->l('Show layout control buttons (list/grid, big/small)').'</h4>
								<p class="variant switch">
								    <input type="radio" name="lc_buttons" id="show_lc_buttons" value="1" '.(($s["lc_buttons"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="lc_buttons" id="hide_lc_buttons" value="0" '.(($s["lc_buttons"] == 0) ? 'checked ' : '').'/>
								    <label for="show_lc_buttons" class="cb-enable'.(($s["lc_buttons"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="hide_lc_buttons" class="cb-disable'.(($s["lc_buttons"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="ts_countdown">
								<h4>'.$this->l('Countdown timer for products with special price').'</h4>
								<p class="variant switch">
								    <input type="radio" name="ts_countdown" id="show_ts_countdown" value="1" '.(($s["ts_countdown"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="ts_countdown" id="hide_ts_countdown" value="0" '.(($s["ts_countdown"] == 0) ? 'checked ' : '').'/>
								    <label for="show_ts_countdown" class="cb-enable'.(($s["ts_countdown"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="hide_ts_countdown" class="cb-disable'.(($s["ts_countdown"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="reduced_label">
								<h4>'.$this->l('"Reduced Price" label').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="reduced_label" id="reduced_label_on" value="1" '.(($s["reduced_label"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="reduced_label" id="reduced_label_off" value="0" '.(($s["reduced_label"] == 0) ? 'checked ' : '').'/>
								    <label for="reduced_label_on" class="cb-enable'.(($s["reduced_label"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="reduced_label_off" class="cb-disable'.(($s["reduced_label"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="color_label">
								<h4>'.$this->l('Color labels').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="color_label" id="color_label_on" value="1" '.(($s["color_label"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="color_label" id="color_label_off" value="0" '.(($s["color_label"] == 0) ? 'checked ' : '').'/>
								    <label for="color_label_on" class="cb-enable'.(($s["color_label"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="color_label_off" class="cb-disable'.(($s["color_label"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="cat_wishlist_butt">
								<h4>'.$this->l('Show "Add to Wishlist" button').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="cat_wishlist_butt" id="cat_wishlist_butt_on" value="1" '.(($s["cat_wishlist_butt"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_wishlist_butt" id="cat_wishlist_butt_off" value="0" '.(($s["cat_wishlist_butt"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_wishlist_butt_on" class="cb-enable'.(($s["cat_wishlist_butt"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="cat_wishlist_butt_off" class="cb-disable'.(($s["cat_wishlist_butt"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="cat_favorite_butt">
								<h4>'.$this->l('Show "Add to Favorites" button').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="cat_favorite_butt" id="cat_favorite_butt_on" value="1" '.(($s["cat_favorite_butt"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_favorite_butt" id="cat_favorite_butt_off" value="0" '.(($s["cat_favorite_butt"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_favorite_butt_on" class="cb-enable'.(($s["cat_favorite_butt"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="cat_favorite_butt_off" class="cb-disable'.(($s["cat_favorite_butt"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="cat_quickview_butt">
								<h4>'.$this->l('Show "Quick view" button').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="cat_quickview_butt" id="cat_quickview_butt_on" value="1" '.(($s["cat_quickview_butt"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_quickview_butt" id="cat_quickview_butt_off" value="0" '.(($s["cat_quickview_butt"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_quickview_butt_on" class="cb-enable'.(($s["cat_quickview_butt"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="cat_quickview_butt_off" class="cb-disable'.(($s["cat_quickview_butt"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
							<div class="margin form" id="cat_addtocart_as_icon">
								<h4>'.$this->l('Show "Add to cart" button as icon').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="cat_addtocart_as_icon" id="cat_addtocart_as_icon_on" value="1" '.(($s["cat_addtocart_as_icon"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_addtocart_as_icon" id="cat_addtocart_as_icon_off" value="0" '.(($s["cat_addtocart_as_icon"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_addtocart_as_icon_on" class="cb-enable'.(($s["cat_addtocart_as_icon"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="cat_addtocart_as_icon_off" class="cb-disable'.(($s["cat_addtocart_as_icon"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form" id="cat_rating">
								<h4>'.$this->l('Show rating stars').'</h4>								
								<p class="variant switch">
								    <input type="radio" name="cat_rating" id="cat_rating_on" value="1" '.(($s["cat_rating"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="cat_rating" id="cat_rating_off" value="0" '.(($s["cat_rating"] == 0) ? 'checked ' : '').'/>
								    <label for="cat_rating_on" class="cb-enable'.(($s["cat_rating"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="cat_rating_off" class="cb-disable'.(($s["cat_rating"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								</p>
							</div>
						</div>
					</div>		
					<div class="tabcontent" id="tab_content_8" '.(($s["tab_number"] == 8) ? 'style="display:block"' : '').'>		
					<input type="radio" class="hide" name="tab_number" id="tab_8" value="8" '.(($s["tab_number"] == 8) ? 'checked="checked"' : '').' />
						<!--<div class="va-content tt-wrapper">
							<div class="margin form" id="product-appearance">
							<h4>'.$this->l('Product Image Appearance').'</h4>								
								<label class="illustrated '.(($s["product_page"] == 1) ? 'selected' : '').'" for="view_serialscroll" onclick="$(\'#product-appearance label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="view_serialscroll_lbl">
									<img src="'.$this->_path.'/images/backoffice/view_serialscroll.gif" alt="" />
									<span class="tt">'.$this->l('SerialScroll carousel').'</span>
								</label>
								<input class="invisible" type="radio" id="view_serialscroll" name="product_page" value="1" '.(($s["product_page"] == 1) ? 'checked="checked" ' : '').'/>
								<label class="illustrated '.(($s["product_page"] == 0) ? 'selected' : '').'" for="view_feat_carousel" onclick="$(\'#product-appearance label\').removeClass(\'selected\');$(this).addClass(\'selected\');" id="view_grid_lbl">
										<img src="'.$this->_path.'/images/backoffice/view_feat_carousel.gif" alt="" />
										<span class="tt">'.$this->l('Feature Carousel').'</span>
								</label>
								<input class="invisible" type="radio" name="product_page" id="view_feat_carousel" value="0" '.(($s["product_page"] == 0) ? 'checked="checked" ' : '').'/>
								<div class="clear"></div>
								<p class="preference_description">'.$this->l('Show product images as "Feature Carousel" or "serialScroll carousel"').'</p>
							</div>
						</div>-->
						<div class="margin form" id="product_video">
						<h4>'.$this->l('Product Video').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_video" id="show_product_video" value="1" '.(($s["product_video"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_video" id="hide_product_video" value="0" '.(($s["product_video"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_video" class="cb-enable'.(($s["product_video"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_product_video" class="cb-disable'.(($s["product_video"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('Show product video on the product page').'</p>
							</div>
						</div>
						<div class="margin form" id="custom_tab">
						<h4>'.$this->l('Custom Tab').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="custom_tab" id="show_custom_tab" value="1" '.(($s["custom_tab"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="custom_tab" id="hide_custom_tab" value="0" '.(($s["custom_tab"] == 0) ? 'checked ' : '').'/>
							    <label for="show_custom_tab" class="cb-enable'.(($s["custom_tab"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_custom_tab" class="cb-disable'.(($s["custom_tab"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('Display tab with custom information. You can manage content of this tab in the product section of your back office.').'</p>
							</div>
						</div>
						<div class="margin form" id="product_rating">
						<h4>'.$this->l('Product Rating (stars)').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_rating" id="show_product_rating" value="1" '.(($s["product_rating"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_rating" id="hide_product_rating" value="0" '.(($s["product_rating"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_rating" class="cb-enable'.(($s["product_rating"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_product_rating" class="cb-disable'.(($s["product_rating"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							</div>
						</div>
						<div class="margin form" id="product_payment_logos">
						<h4>'.$this->l('Product Payment Logos').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_payment_logos" id="show_product_payment_logos" value="1" '.(($s["product_payment_logos"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_payment_logos" id="hide_product_payment_logos" value="0" '.(($s["product_payment_logos"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_payment_logos" class="cb-enable'.(($s["product_payment_logos"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_product_payment_logos" class="cb-disable'.(($s["product_payment_logos"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('"Product payment logos block" module must be installed').'</p>
							</div>
						</div>
						<div class="margin form" id="product_print">
						<h4>'.$this->l('"Print" button').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_print" id="show_product_print" value="1" '.(($s["product_print"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_print" id="hide_product_print" value="0" '.(($s["product_print"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_print" class="cb-enable'.(($s["product_print"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_product_print" class="cb-disable'.(($s["product_print"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							</div>
						</div>
						<div class="margin form" id="product_send">
						<h4>'.$this->l('"Send to a Friend" Button').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_send" id="show_product_send" value="1" '.(($s["product_send"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_send" id="hide_product_send" value="0" '.(($s["product_send"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_send" class="cb-enable'.(($s["product_send"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_product_send" class="cb-disable'.(($s["product_send"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('"Send to a Friend" module must be installed').'</p>
							</div>
						</div>
						<div class="margin form" id="product_colorpicker">
						<h4>'.$this->l('"Colorpicker Type').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_colorpicker" id="show_product_colorpicker" value="1" '.(($s["product_colorpicker"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_colorpicker" id="hide_product_colorpicker" value="0" '.(($s["product_colorpicker"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_colorpicker" class="cb-enable'.(($s["product_colorpicker"] == 1) ? ' sel' : '').'"><span>'.$this->l('Dropdown').'</span></label>
							    <label for="hide_product_colorpicker" class="cb-disable'.(($s["product_colorpicker"] == 0) ? ' sel' : '').'"><span>'.$this->l('List').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('Show colors like dropdown or a list').'</p>
							</div>
						</div>
						<div class="margin form" id="share_buttons">
						<h4>'.$this->l('Share buttons').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="share_buttons" id="show_share_buttons" value="1" '.(($s["share_buttons"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="share_buttons" id="hide_share_buttons" value="0" '.(($s["share_buttons"] == 0) ? 'checked ' : '').'/>
							    <label for="show_share_buttons" class="cb-enable'.(($s["share_buttons"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_share_buttons" class="cb-disable'.(($s["share_buttons"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							</div>
						</div>
						<div class="margin form" id="short_desc">
						<h4>'.$this->l('Short Product Description').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="short_desc" id="show_short_desc" value="1" '.(($s["short_desc"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="short_desc" id="hide_short_desc" value="0" '.(($s["short_desc"] == 0) ? 'checked ' : '').'/>
							    <label for="show_short_desc" class="cb-enable'.(($s["short_desc"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
							    <label for="hide_short_desc" class="cb-disable'.(($s["short_desc"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
							</p>
							</div>
						</div>	
						<div class="margin form" id="product_select_type">
						<h4>'.$this->l('Size Selector Type:').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="product_select_type" id="show_product_select_type" value="1" '.(($s["product_select_type"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="product_select_type" id="hide_product_select_type" value="0" '.(($s["product_select_type"] == 0) ? 'checked ' : '').'/>
							    <label for="show_product_select_type" class="cb-enable'.(($s["product_select_type"] == 1) ? ' sel' : '').'"><span>'.$this->l('Radio Buttons').'</span></label>
							    <label for="hide_product_select_type" class="cb-disable'.(($s["product_select_type"] == 0) ? ' sel' : '').'"><span>'.$this->l('Custom View').'</span></label>
							</p>
							</div>
						</div>
						<!--<div class="margin form" id="maximize_image">
						<h4>'.$this->l('Maximize Product Image').'</h4>
							<div class="va-content tt-wrapper">
							<p class="variant switch">
							    <input type="radio" name="maximize_image" id="show_maximize_image" value="1" '.(($s["maximize_image"] == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="maximize_image" id="hide_maximize_image" value="0" '.(($s["maximize_image"] == 0) ? 'checked ' : '').'/>
							    <label for="show_maximize_image" class="cb-enable'.(($s["maximize_image"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
							    <label for="hide_maximize_image" class="cb-disable'.(($s["maximize_image"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
							</p>
							<p class="preference_description">'.$this->l('This function add a zoom button above main product image.').'</p>
							</div>
						</div>	-->				
					</div>
					<div class="tabcontent" id="tab_content_9" '.(($s["tab_number"] == 9) ? 'style="display:block"' : '').'>		
					<input type="radio" class="hide" name="tab_number" id="tab_9" value="9" '.(($s["tab_number"] == 9) ? 'checked="checked"' : '').' />
						<div class="va-content tt-wrapper">
							<div class="margin form">
								<h4>'.$this->l('Add your CSS styles').'</h4>
								<textarea id="customcss" name="custom_css" cols="26" rows="3">'.$this->readCustomCSS().'</textarea>
							</div>							
						</div>
					</div>
					<div class="tabcontent" id="tab_content_10" '.(($s["tab_number"] == 10) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_10" value="10" '.(($s["tab_number"] == 10) ? 'checked="checked"' : '').' />
						<div class="va-content">
							<div class="margin form emails">
								<h4>'.$this->l('Facebook').'</h4>
								<p class="variant switch">
								    <input type="radio" name="email_fb" id="email_fb_en" value="1" '.(($s["email_fb"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_fb" id="email_fb_dis" value="0" '.(($s["email_fb"] == 0) ? 'checked ' : '').'/>
								    <label for="email_fb_en" class="swt_on cb-enable'.(($s["email_fb"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_fb_dis" class="swt_off cb-disable'.(($s["email_fb"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<h4>'.$this->l('Twitter').'</h4>
								<p class="variant switch">
								    <input type="radio" name="email_tw" id="email_tw_en" value="1" '.(($s["email_tw"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_tw" id="email_tw_dis" value="0" '.(($s["email_tw"] == 0) ? 'checked ' : '').'/>
								    <label for="email_tw_en" class="swt_on cb-enable'.(($s["email_tw"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_tw_dis" class="swt_off cb-disable'.(($s["email_tw"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<h4>'.$this->l('Youtube').'</h4>
								<p class="variant switch">
								    <input type="radio" name="email_yt" id="email_yt_en" value="1" '.(($s["email_yt"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_yt" id="email_yt_dis" value="0" '.(($s["email_yt"] == 0) ? 'checked ' : '').'/>
								    <label for="email_yt_en" class="swt_on cb-enable'.(($s["email_yt"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_yt_dis" class="swt_off cb-disable'.(($s["email_yt"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<h4>'.$this->l('Google+').'</h4>
								<p class="variant switch">
								    <input type="radio" name="email_gp" id="email_gp_en" value="1" '.(($s["email_gp"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_gp" id="email_gp_dis" value="0" '.(($s["email_gp"] == 0) ? 'checked ' : '').'/>
								    <label for="email_gp_en" class="swt_on cb-enable'.(($s["email_gp"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_gp_dis" class="swt_off cb-disable'.(($s["email_gp"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<p class="preference_description">'.$this->l('Accounts of social networks above you can change in the "Social Accounts" section.').'</p><br/><br/>
								<h4>'.$this->l('Emails').'</h4>
								<p class="variant switch he80">
								    <input type="radio" name="email_em" id="email_em_en" value="1" '.(($s["email_em"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_em" id="email_em_dis" value="0" '.(($s["email_em"] == 0) ? 'checked ' : '').'/>
								    <label for="email_em_en" class="swt_on cb-enable'.(($s["email_em"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_em_dis" class="swt_off cb-disable'.(($s["email_em"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["email_em"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_em_acc" id="email_em_acc" value="'.$s["email_em_acc"].'" />
										<label class="t textinput" for="email_em_acc">'.$this->l('Your first email').'</label><br/>
									<input type="text" size="20" name="email_em_acc2" id="email_em_acc2" value="'.$s["email_em_acc2"].'" />
									<label class="t textinput" for="email_em_acc2">'.$this->l('Your second email').'</label>
									</span>
								</p>
								<h4>'.$this->l('Skype').'</h4>
								<p class="variant switch he80">
								    <input type="radio" name="email_sk" id="email_sk_en" value="1" '.(($s["email_sk"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_sk" id="email_sk_dis" value="0" '.(($s["email_sk"] == 0) ? 'checked ' : '').'/>
								    <label for="email_sk_en" class="swt_on cb-enable'.(($s["email_sk"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_sk_dis" class="swt_off cb-disable'.(($s["email_sk"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["email_sk"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_sk_acc" id="email_sk_acc" value="'.$s["email_sk_acc"].'" />
										<label class="t textinput" for="email_sk_acc">'.$this->l('Your first skype').'</label>
									<input type="text" size="20" name="email_sk_acc2" id="email_sk_acc2" value="'.$s["email_sk_acc2"].'" />
									<label class="t textinput" for="email_sk_acc2">'.$this->l('Your second skype').'</label>
									</span>
								</p>
								<h4>'.$this->l('Mobile Phones').'</h4>
								<p class="variant switch he80">
								    <input type="radio" name="email_ph" id="email_ph_en" value="1" '.(($s["email_ph"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_ph" id="email_ph_dis" value="0" '.(($s["email_ph"] == 0) ? 'checked ' : '').'/>
								    <label for="email_ph_en" class="swt_on cb-enable'.(($s["email_ph"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_ph_dis" class="swt_off cb-disable'.(($s["email_ph"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["email_ph"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_ph_acc" id="email_ph_acc" value="'.$s["email_ph_acc"].'" />
										<label class="t textinput" for="email_ph_acc">'.$this->l('Your first phone').'</label>
									<input type="text" size="20" name="email_ph_acc2" id="email_ph_acc2" value="'.$s["email_ph_acc2"].'" />
									<label class="t textinput" for="email_ph_acc2">'.$this->l('Your second phone').'</label>
									</span>
								</p>
								<h4>'.$this->l('Other Phones').'</h4>
								<p class="variant switch he80">
								    <input type="radio" name="email_oph" id="email_oph_en" value="1" '.(($s["email_oph"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_oph" id="email_oph_dis" value="0" '.(($s["email_oph"] == 0) ? 'checked ' : '').'/>
								    <label for="email_oph_en" class="swt_on cb-enable'.(($s["email_oph"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="email_oph_dis" class="swt_off cb-disable'.(($s["email_oph"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["email_oph"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_oph_acc" id="email_oph_acc" value="'.$s["email_oph_acc"].'" />
										<label class="t textinput" for="email_oph_acc">'.$this->l('Your first phone').'</label>
									<input type="text" size="20" name="email_oph_acc2" id="email_oph_acc2" value="'.$s["email_oph_acc2"].'" />
									<label class="t textinput" for="email_oph_acc2">'.$this->l('Your second phone').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4>'.$this->l('Advertising Image').'</h4>
								<div class="variant switch">
									<input type="radio" name="email_adv" id="email_adv_yes" value="1" '.(($s["email_adv"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_adv" id="email_adv_no" value="0" '.(($s["email_adv"] == 0) ? 'checked ' : '').'/>
								    <label for="email_adv_yes" class="swt_on cb-enable'.(($s["email_adv"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="email_adv_no" class="swt_off cb-disable'.(($s["email_adv"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>
								    <div class="swt_container'.(($s["email_adv"] == 0) ? ' hide' : '').'">
										<div class="back_image_container">
										<strong>'.$this->l('Recommended image dimension is 224x224px').'</strong><br/>
											'.(($s["email_image"] != "") ? '<img class="back_image" src="'.$this->_path.'images/upload/'.$s["email_image"].'?'.$rev.'">' : 'No Image').'
										</div>									
										<input id="email_image" type="file" name="email_image">
										<input id="eimage" type="submit" class="button" name="email_image_upload">
										<input class="button'.(($s["email_image"] == "") ? ' hide' : '').'" type="submit" name="submitDeleteEmailImg" value="'.$this->l('Delete image').'" />
										<br/><br/>
										<div style="clear:both">										
										<label for="email_adv_link" style="width:100px; text-align:right">'.$this->l('Advertising Link').':</label>
										<input type="text" size="40" name="email_adv_link" id="email_adv_link" value="'.$s["email_adv_link"].'" />
										</div>
									</div>
							    </div>							    
							</div>
							<div class="margin form">
								<h4>'.$this->l('Address').'</h4>
								<div class="variant switch">
									<input type="radio" name="email_addr" id="email_addr_yes" value="1" '.(($s["email_addr"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="email_addr" id="email_addr_no" value="0" '.(($s["email_addr"] == 0) ? 'checked ' : '').'/>
								    <label for="email_addr_yes" class="swt_on cb-enable'.(($s["email_addr"] == 1) ? ' sel' : '').'"><span>'.$this->l('Show').'</span></label>
								    <label for="email_addr_no" class="swt_off cb-disable'.(($s["email_addr"] == 0) ? ' sel' : '').'"><span>'.$this->l('Hide').'</span></label>								  
									<textarea id="email_addr" name="email_addr_text" class="swt_container'.(($s["email_addr"] == 0) ? ' hide' : '').'" cols="26" rows="3">'.$s["email_addr_text"].'</textarea>
							    </div>							    
							</div>								
							<div class="margin form">
								<h4>'.$this->l('Fix problems').'</h4>
								<p>'.$this->l('Use this function only if you have some problems with email templates (broken images, no confirmation emails, etc...)').'</p>
								<input class="button" type="submit" name="fixEmails" value="'.$this->l('Fix it').'" />
							</div>
						</div>
					</div>	
					<div class="tabcontent" id="tab_content_11" '.(($s["tab_number"] == 11) ? 'style="display:block"' : '').'>		
						<input type="radio" class="hide" name="tab_number" id="tab_11" value="11" '.(($s["tab_number"] == 11) ? 'checked="checked"' : '').' />
						<div class="margin">
							<div class="margin form">
							<h4>'.$this->l('Show icons in footer').'</h4>
								<p class="variant switch">
								    <input type="radio" name="socials_in_footer" id="socials_in_footer_en" value="1" '.(($s["socials_in_footer"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="socials_in_footer" id="socials_in_footer_dis" value="0" '.(($s["socials_in_footer"] == 0) ? 'checked ' : '').'/>
								    <label for="socials_in_footer_en" class="swt_on cb-enable'.(($s["socials_in_footer"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="socials_in_footer_dis" class="swt_off cb-disable'.(($s["socials_in_footer"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="facebook"><i></i>'.$this->l('Facebook').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_fb" id="soc_fb_en" value="1" '.(($s["soc_fb"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_fb" id="soc_fb_dis" value="0" '.(($s["soc_fb"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_fb_en" class="swt_on cb-enable'.(($s["soc_fb"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_fb_dis" class="swt_off cb-disable'.(($s["soc_fb"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_fb"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_fb_acc" id="email_fb_acc" value="'.$s["email_fb_acc"].'" />
										<label class="t textinput" for="email_fb_acc">'.$this->l('The link to your facebook page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="twitter"><i></i>'.$this->l('Twitter').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_tw" id="soc_tw_en" value="1" '.(($s["soc_tw"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_tw" id="soc_tw_dis" value="0" '.(($s["soc_tw"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_tw_en" class="swt_on cb-enable'.(($s["soc_tw"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_tw_dis" class="swt_off cb-disable'.(($s["soc_tw"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_tw"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_tw_acc" id="email_tw_acc" value="'.$s["email_tw_acc"].'" />
										<label class="t textinput" for="email_tw_acc">'.$this->l('The link to your twitter page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="youtube"><i></i>'.$this->l('Youtube').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_yt" id="soc_yt_en" value="1" '.(($s["soc_yt"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_yt" id="soc_yt_dis" value="0" '.(($s["soc_yt"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_yt_en" class="swt_on cb-enable'.(($s["soc_yt"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_yt_dis" class="swt_off cb-disable'.(($s["soc_yt"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_yt"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_yt_acc" id="email_yt_acc" value="'.$s["email_yt_acc"].'" />
										<label class="t textinput" for="email_yt_acc">'.$this->l('The link to your youtube page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="gplus"><i></i>'.$this->l('Google+').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_gp" id="soc_gp_en" value="1" '.(($s["soc_gp"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_gp" id="soc_gp_dis" value="0" '.(($s["soc_gp"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_gp_en" class="swt_on cb-enable'.(($s["soc_gp"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_gp_dis" class="swt_off cb-disable'.(($s["soc_gp"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_gp"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_gp_acc" id="email_gp_acc" value="'.$s["email_gp_acc"].'" />
										<label class="t textinput" for="email_gp_acc">'.$this->l('The link to your google+ page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="pinterest"><i></i>'.$this->l('Pinterest').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_pi" id="soc_pi_en" value="1" '.(($s["soc_pi"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_pi" id="soc_pi_dis" value="0" '.(($s["soc_pi"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_pi_en" class="swt_on cb-enable'.(($s["soc_pi"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_pi_dis" class="swt_off cb-disable'.(($s["soc_pi"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_pi"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_pi_acc" id="email_pi_acc" value="'.$s["email_pi_acc"].'" />
										<label class="t textinput" for="email_pi_acc">'.$this->l('The link to your pinterest page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="instagram"><i></i>'.$this->l('Instagram').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_ig" id="soc_ig_en" value="1" '.(($s["soc_ig"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_ig" id="soc_ig_dis" value="0" '.(($s["soc_ig"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_ig_en" class="swt_on cb-enable'.(($s["soc_ig"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_ig_dis" class="swt_off cb-disable'.(($s["soc_ig"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_ig"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_ig_acc" id="email_ig_acc" value="'.$s["email_ig_acc"].'" />
										<label class="t textinput" for="email_ig_acc">'.$this->l('The link to your instagram page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="linkedin"><i></i>'.$this->l('LinkedIn').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_li" id="soc_li_en" value="1" '.(($s["soc_li"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_li" id="soc_li_dis" value="0" '.(($s["soc_li"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_li_en" class="swt_on cb-enable'.(($s["soc_li"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_li_dis" class="swt_off cb-disable'.(($s["soc_li"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_li"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_li_acc" id="email_li_acc" value="'.$s["email_li_acc"].'" />
										<label class="t textinput" for="email_li_acc">'.$this->l('The link to your LinkedIn page').'</label>
									</span>
								</p>
							</div>
							<div class="margin form">
								<h4 class="flickr"><i></i>'.$this->l('Flickr').'</h4>
								<p class="variant switch">
								    <input type="radio" name="soc_fl" id="soc_fl_en" value="1" '.(($s["soc_fl"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="soc_fl" id="soc_fl_dis" value="0" '.(($s["soc_fl"] == 0) ? 'checked ' : '').'/>
								    <label for="soc_fl_en" class="swt_on cb-enable'.(($s["soc_fl"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="soc_fl_dis" class="swt_off cb-disable'.(($s["soc_fl"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								    <span class="swt_container'.(($s["soc_fl"] == 0) ? ' hide' : '').'">
								    <input type="text" size="20" name="email_fl_acc" id="email_fl_acc" value="'.$s["email_fl_acc"].'" />
										<label class="t textinput" for="email_fl_acc">'.$this->l('The link to your Flickr page').'</label>
									</span>
								</p>						    					
							</div>							
						</div>
					</div>
					<div class="tabcontent" id="tab_content_12" '.(($s["tab_number"] == 12) ? 'style="display:block"' : '').'>		
					<input type="radio" class="hide" name="tab_number" id="tab_12" value="12" '.(($s["tab_number"] == 12) ? 'checked="checked"' : '').' />
						<div class="va-content tt-wrapper">
							<div class="margin form">
								<h4>'.$this->l('Enable Shop').'</h4>	
								<p class="variant switch">
								<input type="radio" name="maintenance" id="maintenance_en" value="1" '.((Configuration::get('PS_SHOP_ENABLE') == 1) ? 'checked ' : '').'/>
							    <input type="radio" name="maintenance" id="maintenance_dis" value="0" '.((Configuration::get('PS_SHOP_ENABLE') == 0) ? 'checked ' : '').'/>
							    <label for="maintenance_en" class="swt_on cb-enable'.((Configuration::get('PS_SHOP_ENABLE') == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
							    <label for="maintenance_dis" class="swt_off cb-disable'.((Configuration::get('PS_SHOP_ENABLE') == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
								<p class="preference_description">'.$this->l('Activate or deactivate your shop (It is a good idea to deactivate your shop while you perform maintenance. Please note that the webservice will not be disabled).').'</p><br/>
								<h4>'.$this->l('The time when you will open your shop').'</h4>'.$ready_date.'
								<br/><br/>
								<h4>'.$this->l('Maintenance Message').'</h4>
								<textarea id="ready_text" name="ready_text" class="swt_container" style="height:80px; width:100%" cols="26" rows="3">'.$s["ready_text"].'</textarea>
								<br/><br/>
								<!--<h4>'.$this->l('Choose CMS page for About Us column').'</h4>												
								<select name="maintanance_cms_page">'.$options.'</select><br/><br/>-->
								<h4>'.$this->l('The list of subscribed emails').'<span class="show_the_list">  <span class="cshow">'.$this->l('Show').'</span><span class="chide">'.$this->l('Hide').'</span></span></h4>								
								<div class="email-list">'.$emails.'<br/><br/></div>
								<h4>'.$this->l('Notification').'</h4>											
								<input class="button" type="submit" name="sendnotification" value="'.$this->l('Send').'" />
								<p class="preference_description">'.$this->l('Send notification that your shop is open for all subscribed people. (List of subscribers is located here your_site\modules\pk_themesettings\maintenance\emails.txt)').'</p><br/>
							</div>							
						</div>
					</div>
					<div class="tabcontent pay" id="tab_content_13" '.(($s["tab_number"] == 13) ? 'style="display:block"' : '').'>
					<input type="radio" class="hide" name="tab_number" id="tab_13" value="13" '.(($s["tab_number"] == 13) ? 'checked="checked"' : '').' />
						<div class="va-content tt-wrapper">
							<div class="margin form">
								<h4>'.$this->l('Show payment icons in footer').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_in_footer" id="pay_in_footer_en" value="1" '.(($s["pay_in_footer"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_in_footer" id="pay_in_footer_dis" value="0" '.(($s["pay_in_footer"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_in_footer_en" class="swt_on cb-enable'.(($s["pay_in_footer"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_in_footer_dis" class="swt_off cb-disable'.(($s["pay_in_footer"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="am_exp"><i></i>'.$this->l('American Express').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_am_exp" id="pay_am_exp_en" value="1" '.(($s["pay_am_exp"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_am_exp" id="pay_am_exp_dis" value="0" '.(($s["pay_am_exp"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_am_exp_en" class="swt_on cb-enable'.(($s["pay_am_exp"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_am_exp_dis" class="swt_off cb-disable'.(($s["pay_am_exp"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>	
							<div class="margin form">
								<h4 class="cirrus"><i></i>'.$this->l('Cirrus').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_cirrus" id="pay_cirrus_en" value="1" '.(($s["pay_cirrus"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_cirrus" id="pay_cirrus_dis" value="0" '.(($s["pay_cirrus"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_cirrus_en" class="swt_on cb-enable'.(($s["pay_cirrus"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_cirrus_dis" class="swt_off cb-disable'.(($s["pay_cirrus"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>	
							<div class="margin form">
								<h4 class="mastercard"><i></i>'.$this->l('Mastercard').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_mastercard" id="pay_mastercard_en" value="1" '.(($s["pay_mastercard"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_mastercard" id="pay_mastercard_dis" value="0" '.(($s["pay_mastercard"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_mastercard_en" class="swt_on cb-enable'.(($s["pay_mastercard"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_mastercard_dis" class="swt_off cb-disable'.(($s["pay_mastercard"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="maestro"><i></i>'.$this->l('Maestro').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_maestro" id="pay_maestro_en" value="1" '.(($s["pay_maestro"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_maestro" id="pay_maestro_dis" value="0" '.(($s["pay_maestro"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_maestro_en" class="swt_on cb-enable'.(($s["pay_maestro"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_maestro_dis" class="swt_off cb-disable'.(($s["pay_maestro"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>	
							<div class="margin form">
								<h4 class="paypal"><i></i>'.$this->l('Paypal').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_paypal" id="pay_paypal_en" value="1" '.(($s["pay_paypal"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_paypal" id="pay_paypal_dis" value="0" '.(($s["pay_paypal"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_paypal_en" class="swt_on cb-enable'.(($s["pay_paypal"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_paypal_dis" class="swt_off cb-disable'.(($s["pay_paypal"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="discover"><i></i>'.$this->l('Discover').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_discover" id="pay_discover_en" value="1" '.(($s["pay_discover"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_discover" id="pay_discover_dis" value="0" '.(($s["pay_discover"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_discover_en" class="swt_on cb-enable'.(($s["pay_discover"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_discover_dis" class="swt_off cb-disable'.(($s["pay_discover"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>								
							<div class="margin form">
								<h4 class="direct"><i></i>'.$this->l('Direct').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_direct" id="pay_direct_en" value="1" '.(($s["pay_direct"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_direct" id="pay_direct_dis" value="0" '.(($s["pay_direct"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_direct_en" class="swt_on cb-enable'.(($s["pay_direct"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_direct_dis" class="swt_off cb-disable'.(($s["pay_direct"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>	
							<div class="margin form">
								<h4 class="solo"><i></i>'.$this->l('Solo').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_solo" id="pay_solo_en" value="1" '.(($s["pay_solo"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_solo" id="pay_solo_dis" value="0" '.(($s["pay_solo"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_solo_en" class="swt_on cb-enable'.(($s["pay_solo"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_solo_dis" class="swt_off cb-disable'.(($s["pay_solo"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="switch"><i></i>'.$this->l('Switch').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_switch" id="pay_switch_en" value="1" '.(($s["pay_switch"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_switch" id="pay_switch_dis" value="0" '.(($s["pay_switch"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_switch_en" class="swt_on cb-enable'.(($s["pay_switch"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_switch_dis" class="swt_off cb-disable'.(($s["pay_switch"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="visa"><i></i>'.$this->l('Visa').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_visa" id="pay_visa_en" value="1" '.(($s["pay_visa"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_visa" id="pay_visa_dis" value="0" '.(($s["pay_visa"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_visa_en" class="swt_on cb-enable'.(($s["pay_visa"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_visa_dis" class="swt_off cb-disable'.(($s["pay_visa"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>
							<div class="margin form">
								<h4 class="wu"><i></i>'.$this->l('Western Union').'</h4>
								<p class="variant switch">
								    <input type="radio" name="pay_wu" id="pay_wu_en" value="1" '.(($s["pay_wu"] == 1) ? 'checked ' : '').'/>
								    <input type="radio" name="pay_wu" id="pay_wu_dis" value="0" '.(($s["pay_wu"] == 0) ? 'checked ' : '').'/>
								    <label for="pay_wu_en" class="swt_on cb-enable'.(($s["pay_wu"] == 1) ? ' sel' : '').'"><span>'.$this->l('Yes').'</span></label>
								    <label for="pay_wu_dis" class="swt_off cb-disable'.(($s["pay_wu"] == 0) ? ' sel' : '').'"><span>'.$this->l('No').'</span></label>
								</p>
							</div>							
						</div>
					</div>					
				</div>				
				</div>
				<div class="footer_section">
					<input type="submit" name="resetThemeSettings" value="'.$this->l('Reset Settings').'" class="button" id="reset_sett" />
					<span>'.$this->versions.' | '.$this->l('Powered by').' <a target="_blank" href="http://promokit.eu">Promokit Co.</a></span></div>
			</fieldset>
		</form>';		
	}

	private function getCMSOptions($parent = 0, $depth = 1, $curr) {
		$id_lang = (int)Context::getContext()->language->id;		
		$pages = $this->getCMSPages((int)$parent, (int)$id_lang);

		$opts = "";
		foreach ($pages as $page) {
			$opts .= '<option '.(($page["id_cms"] == $curr) ? "selected": "").' value="'.$page['id_cms'].'">'.$page['meta_title'].'</option>';
		}
		return $opts;
	}
	private function getCMSPages($id_cms_category, $id_lang) {
		$id_shop = (int)Context::getContext()->shop->id;
		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `' . _DB_PREFIX_ . 'cms` c
			INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = ' . (int)$id_cms_category . '
			AND cs.`id_shop` = ' . (int)$id_shop . '
			AND cl.`id_lang` = ' . (int)$id_lang . '
			AND c.`active` = 1
			ORDER BY `position`';

		return Db::getInstance()->executeS($sql);
	}

	function prepareResult($control, $theme_settings=null) {
		
		if (!$theme_settings) $theme_settings = $this->getOptions("prepareResult");

		$view = $theme_settings['view'];
		$imageSize = $theme_settings['productImageSize'];
		
		$sizes = array();
		for ($i = 15; $i < $this->maxSize; $i++)
			$sizes[$i] = $i;

		if ($view == 0)
			$view = "view_grid";
		else
			$view = "view_list";

		if ($imageSize == 0)
			$imageSize = "view_small";
		else
			$imageSize = "view_big";

		$comments["install"] = $this->isInst("productcomments");
		$comments["enable"] = $this->isEn("productcomments");
		$wishlist["install"] = $this->isInst("blockwishlist");
		$wishlist["enable"] = $this->isEn("blockwishlist");
		$favorite["install"] = $this->isInst("favoriteproducts");
		$favorite["enable"] = $this->isEn("favoriteproducts");

		$i = 0;
		foreach ($this->defaultFonts as $type => $font) {
			$dFonts[$i] = $font;
			$i++;
		}
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) { $browser = "IE"; } else { $browser = ""; }

		$theme_settings["view"] = $view;
		$theme_settings['imageSize'] = $imageSize;
		$theme_settings['logo_font'] = $this->fontNameAdaptation($theme_settings['logo_font']);
		$theme_settings['fonts_list'] = $this->fontslist();
		$theme_settings['sizes'] = $sizes;
		$theme_settings['patternsQuantity'] = $this->patternsQuantity;
		$theme_settings['defaultFonts'] = $dFonts;
		$theme_settings['systemFonts'] = $this->systemFonts;		
		$theme_settings['selectors'] = $this->selectors;
		$theme_settings['browser'] = $browser;
		$theme_settings['protocol'] = Tools::getShopProtocol();
		$theme_settings['version'] = $this->versions;
		$theme_settings['comments_module'] = $comments;
		$theme_settings['wishlist_module'] = $wishlist;
		$theme_settings['favorite_module'] = $favorite;
		$theme_settings['theme_name'] = $this->theme_name;
		$theme_settings['allcookies'] = $_COOKIE;
		$theme_settings['modules'] = $this->getModulesState();
		$theme_settings['shopID'] = (int)Context::getContext()->shop->id;
		//$theme_settings['ts_path'] = rtrim($_SERVER['DOCUMENT_ROOT'], "/").$this->_path;
		$theme_settings['ts_path'] = _PS_ROOT_DIR_."/modules/".$this->name."/";	
		$theme_settings['cookie_page'] = Context::getContext()->link->getCMSLink(11);
		
		//FONTS
		$theme_settings['used_fonts'] = $this->getusedfonts($theme_settings);

		// assign cookie variables to see them in any place of the theme
    	$this->context->cookie->ts_cat_rating = $theme_settings["cat_rating"];
    	$this->context->cookie->cat_wishlist_butt = $theme_settings["cat_wishlist_butt"];
    	$this->context->cookie->cat_quickview_butt = $theme_settings["cat_quickview_butt"];  
    	$this->context->cookie->cat_favorite_butt = $theme_settings["cat_favorite_butt"];    	
    	$this->context->cookie->ts_wishlist_inst = $wishlist["install"];
    	$this->context->cookie->ts_wishlist_enab = $wishlist["enable"];
    	$this->context->cookie->ts_fav_inst = $favorite["install"];
    	$this->context->cookie->ts_fav_enab = $favorite["enable"];
    	$this->context->cookie->ts_countdown = $theme_settings["ts_countdown"];
    	$this->context->cookie->color_label = $theme_settings["color_label"];
    	$this->context->cookie->cat_addtocart_as_icon = $theme_settings["cat_addtocart_as_icon"];
    	$this->context->cookie->reduced_label = $theme_settings["reduced_label"];
    	$this->context->cookie->img_name = $theme_settings["legacy_img_name"];
		
		return $theme_settings;

	}

	public function getusedfonts($theme_settings) {

		$cyrillic = $latin_ext = $subset = "";

		$allfonts = array(
			$theme_settings["logo_font"], 
			$theme_settings["heading_font"], 
			$theme_settings["subheading_font"], 
			$theme_settings["text_font"], 
			$theme_settings["buttons_font"],
			$theme_settings["price_font"], 
			$theme_settings["slogan_font"]);

		if ($theme_settings["cyrillic"] == true) {
			$subset = "%26subset=";
			$cyrillic = "cyrillic";
		}

		if ($theme_settings["latin_ext"] == true) {
			$subset = "%26subset=";
			$latin_ext = (($cyrillic == "") ? "" : ",")."latin-ext";
		}

		$allfonts = array_unique($allfonts);
		foreach ($allfonts as $key => $font) {
			if (!in_array($font, $this->systemFonts))
					$fonts[] = $font;				
		}
		if (isset($fonts)) {
			$font_str = implode("%7C", $fonts);
			return $font_str.$subset.$cyrillic.$latin_ext;
		}
		return false;
	}

	public function isInst($name) {	

		$return = "not_installed";
		if (Module::isInstalled($name))
			$return = "installed";

		return $return;
	}

	public function isEn($name) {	

		$return = "disabled";
		$id_module = Module::getModuleIdByName($name);
		if (Db::getInstance()->getValue('SELECT `id_module` FROM `'._DB_PREFIX_.'module_shop` WHERE `id_module` = '.(int)$id_module.' AND `id_shop` = '.(int)Context::getContext()->shop->id))
			$return = "enabled";

		return $return;

	}

	public static function isCustomerFavoriteProduct($id_customer, $id_product, Shop $shop = null)
	{
		if ((!$id_customer) || (Module::isInstalled("favoriteproducts") == false))
			return false;

		if (!$shop)
			$shop = Context::getContext()->shop;
		$res = Db::getInstance()->getValue('
			SELECT COUNT(*)
			FROM `'._DB_PREFIX_.'favorite_product`
			WHERE `id_customer` = '.(int)$id_customer.'
			AND `id_product` = '.(int)$id_product.'
			AND `id_shop` = '.(int)$shop->id);
		if (!$res) 
			return false;
		return true;
	}	

	public function getUserWishlists($id_customer, $shop_id, $prod_id) { // check is product in customer wishlist

		$wishlist_ids = Db::getInstance()->ExecuteS('SELECT `id_wishlist` FROM `'._DB_PREFIX_.'wishlist` WHERE `id_customer` = '.$id_customer);
		$ids = array();
		foreach ($wishlist_ids as $wishlist_id => $wishlist) {
			foreach ($wishlist as $key => $id) {
				 $data = Db::getInstance()->ExecuteS('SELECT `id_product` FROM `'._DB_PREFIX_.'wishlist_product` 			WHERE `id_wishlist` = '.(int)$id);					 
				 foreach ($data as $k => $pid) {				 
					$ids[$wishlist_id."-".$k] = $pid["id_product"];					
				 }				 
			}
		}
		if (in_array($prod_id, $ids))
			$inWishList = true;
		else
			$inWishList = false;

		return $inWishList;
	}

	public function getImLink($link_rewrite, $img_id, $imgName) {	

		return $this->context->link->getImageLink($link_rewrite, $img_id, $imgName);
		
	}

	public function getImg($product_id, $link_rewrite, $imgName, $imgAttr = false) {	
		if ($imgAttr == false) {
			$imgAttr = Image::getCover($product_id);	
			$imgAttr = $imgAttr["id_image"];
		}
		$img = $this->getImLink($link_rewrite, (int)$imgAttr["id_image"], $imgName);
		return $img;
	}	
	
	public function getImgByAttr($product_id, $link_rewrite, $imgName, $imgattr) {
		$imgid = Image::getImages($this->context->language->id, $product_id, $imgattr);
		if (!empty($imgid[0]))
			$img = $this->getImLink($link_rewrite, (int)$imgid[0]["id_image"], $imgName);
		else
			$img = false;

		return $img;
	}	
	
	public function getSocialAccouts($ts) {	
		$soc = array();
		if ($ts["soc_fb"] == 1) $soc["facebook"] = $ts["email_fb_acc"];
		if ($ts["soc_tw"] == 1) $soc["twitter"] = $ts["email_tw_acc"];
		if ($ts["soc_gp"] == 1) $soc["gplus"] = $ts["email_gp_acc"];
		if ($ts["soc_yt"] == 1) $soc["youtube"] = $ts["email_yt_acc"];
		if ($ts["soc_fl"] == 1) $soc["flickr"] = $ts["email_fl_acc"];
		if ($ts["soc_ig"] == 1) $soc["instagram"] = $ts["email_ig_acc"];
		if ($ts["soc_pi"] == 1) $soc["pinterest"] = $ts["email_pi_acc"];
		if ($ts["soc_li"] == 1) $soc["linkedin"] = $ts["email_li_acc"];
		return $soc;
	}
	public function getPaymentIcons($ts) {	
		$pay = array();
		if ($ts["pay_visa"] == 1) $pay["visa"] = $ts["pay_visa"];
		if ($ts["pay_am_exp"] == 1) $pay["am_exp"] = $ts["pay_am_exp"];
		if ($ts["pay_mastercard"] == 1) $pay["mastercard"] = $ts["pay_mastercard"];
		if ($ts["pay_paypal"] == 1) $pay["paypal"] = $ts["pay_paypal"];
		if ($ts["pay_maestro"] == 1) $pay["maestro"] = $ts["pay_maestro"];
		if ($ts["pay_discover"] == 1) $pay["discover"] = $ts["pay_discover"];
		if ($ts["pay_cirrus"] == 1) $pay["cirrus"] = $ts["pay_cirrus"];
		if ($ts["pay_direct"] == 1) $pay["direct"] = $ts["pay_direct"];
		if ($ts["pay_solo"] == 1) $pay["solo"] = $ts["pay_solo"];
		if ($ts["pay_switch"] == 1) $pay["switch"] = $ts["pay_switch"];
		if ($ts["pay_wu"] == 1) $pay["wu"] = $ts["pay_wu"];
		return $pay;
	}
	/* CUSTOM HOOKS */
	public function hookcomingsoon($params)
	{
		$theme_settings = $this->getOptions("hookcomingsoon");
		$cs["min"] = sprintf('%02d', $theme_settings["ready_min"]);
	    $cs["hour"] = sprintf('%02d', $theme_settings["ready_hour"]);
	    $cs["day"] = sprintf('%02d', $theme_settings["ready_day"]);
	    $cs["mon"] = sprintf('%02d', $theme_settings["ready_month"]);
	    $cs["year"] = sprintf('%02d', $theme_settings["ready_year"]);
	    $cs["mes"] = $theme_settings["ready_text"];
	    $cs["status"] = Configuration::get('PS_SHOP_ENABLE');
	    $cs["date_set"] = $theme_settings["date_set"];
	    $cs["addr"] = $theme_settings["location"];
	    $cs["location_lat"] = $theme_settings["location_lat"];
	    $cs["location_lng"] = $theme_settings["location_lng"];
	    //echo date('H:i m/d/Y', $theme_settings["date_set"]);
	    $mtime = strtotime($cs["day"].".".($cs["mon"]+1).".".$cs["year"]." ".$cs["hour"].":".$cs["min"]);
	    $diff = $mtime-time();
	    
	    $cms = new CMS($theme_settings["maintanance_cms_page"], (int)Context::getContext()->language->id);

	   	$soc = $this->getSocialAccouts($theme_settings);
	   	$protocol_content = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
	   	$facebookCacheFile = _PS_MODULE_DIR_.'pk_blockfacebooklike/cache/tpl/fans_'.(int)Context::getContext()->shop->id.'.tpl';
	   	if (file_exists($facebookCacheFile)) {
	   		$fileHolder = $facebookCacheFile;	
	   	} else {
	   		$fileHolder = false;	
	   	}

		$this->context->smarty->assign(
		  array(
		      'cs' => $cs,
		      'soc' => $soc,		      
		      'name' => Configuration::get('PS_SHOP_NAME'),
		      'module_dir' => $this->context->link->getModuleLink($this->name),
		      'mainURL' => $protocol_content.Tools::getHttpHost().__PS_BASE_URI__.(!Configuration::get('PS_REWRITING_SETTINGS') ? 'index.php' : $this->context->language->iso_code."/"),
		      'aboutus' => $cms->content,
		      'diff' => $diff,
		      'facebooklike' => $fileHolder
		  )
		);	
		return $this->display(__FILE__, 'views/frontend/comingsoon.tpl');
	}

	public function hookfooter_bottom($params)
	{
		$ts = $this->getOptions("hookfooter_bottom");
		$pay = $this->getPaymentIcons($ts);
		$soc = $this->getSocialAccouts($ts);
		$this->context->smarty->assign(array(
			"soc" => $soc,
			'pay' => $pay,
			"module_dir" => $this->context->link->getModuleLink($this->name)
		));	
		return $this->display(__FILE__, 'views/frontend/footer_bottom.tpl');
	}

	public function hookfooter_top($params)
	{

		$s = $this->getModulesState();
		$state = explode(".", $s["footer_top"][$this->name]);
	 	if ($state[1] == 1) {
	 		
	 		if (!$this->isCached('views/frontend/products.tpl', $this->getCacheId())) {
				$lid = $this->context->language->id;
				$category = new Category(Context::getContext()->shop->getCategory(), $lid);

				$this->context->smarty->assign(array(
					"ts_fea" => $category->getProducts($lid, 0, 2),
					"ts_spe" => Product::getPricesDrop($lid, 0, 2),
					"ts_new" => Product::getNewProducts($lid, 0, 2)
				));
			}
			return $this->display(__FILE__, 'views/frontend/products.tpl', $this->getCacheId());
		}
	}

	/*	search request	*/
	public function getSearchResult($pID, $cRewrite, $image_name) {
		$ids = explode(",", $pID);
		$crw = explode(",", $cRewrite);
		$currency = Context::getContext()->currency->id;

		if (is_array($ids)) {
			foreach ($ids as $key => $id) {
				$cover = Image::getCover($id);		
				$img = (int)$cover['id_product'].'-'.(int)$cover['id_image'];
				if (isset($crw[$key])) {
					$data[$key]["link"] = $this->context->link->getImageLink($crw[$key], $img, $image_name);
					$data[$key]["price"] = Tools::displayPrice(Product::getPriceStatic($id), $currency);
				}
			}
		}
		print_r(json_encode($data));
	}

	public function productVideo($params) {
		$s = $this->getOptions("productVideo");
		$id_product = Tools::getValue('id_product');
		$sid = (int)Context::getContext()->shop->id;
		$lid = $this->context->language->id;
		$getVideo = Db::getInstance()->ExecuteS('SELECT `video` FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.$sid.' AND lang_id = '.$lid);
	        $this->context->smarty->assign(array(
	            'pk_video_id' => $getVideo[0]["video"],
	            'id_lang' => $lid,
	            'languages' => Language::getLanguages(true)
	        ));

        if ($s["product_video"] == 1)
	        return $this->display(__FILE__, 'views/frontend/video.tpl');
	    else
	    	return false;

	}		

	/*	BACKOFFICE HOOKS	*/	

	public function hookDisplayAdminProductsExtra($params) {
		$s = $this->getOptions("hookDisplayAdminProductsExtra");
		$id_product = Tools::getValue('id_product');
		$languages = Language::getLanguages(true);

        $id_lang_default = Configuration::get('PS_LANG_DEFAULT');     

        $custom_tab = $custom_tab_name = $video_id = array();
        foreach ($languages as $id => $language) {       

        	$getData = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.(int)Context::getContext()->shop->id.' AND lang_id = '.$language["id_lang"]); 	

        	if (isset($getData[0])) {
        		$custom_tab[$language["id_lang"]] = $getData[0]["custom_tab"];
        		$custom_tab_name[$language["id_lang"]] = $getData[0]["custom_tab_name"];
        		$video_id[$language["id_lang"]] = $getData[0]["video"];
			}
        	if ($language["id_lang"] == $id_lang_default)
        		$languages[$id]["is_default"] = true;
        	else
        		$languages[$id]["is_default"] = false;
        }      
        $this->context->smarty->assign(array(
        	'show_video' => $s["product_video"],
        	'show_custom_tab' => $s["custom_tab"],
            'pk_video_id' => $video_id,
            'pk_custom_tab' => $custom_tab,
            'pk_custom_tab_name' => $custom_tab_name,
            'languages' => $languages,
            'id_lang' => $id_lang_default,
            'iso_tiny_mce' => (Tools::file_exists_cache(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.Context::getContext()->language->iso_code.'.js') ? Context::getContext()->language->iso_code : 'en'),
			'ad' => dirname($_SERVER['PHP_SELF'])
        ));
        
        return $this->display(__FILE__, 'views/admin/video.tpl');
    }
    
    public function hookActionProductUpdate($params) {
    	$sql = array();
        $id_product = Tools::getValue('id_product');
        $languages = Language::getLanguages();        
        if ($id_product) {
	        foreach ($languages as $id_lang => $language) {
	        	$custom_tab = Tools::getValue('custom_tab_'.$language["id_lang"]);
	        	$custom_tab_name = Tools::getValue('custom_tab_name_'.$language["id_lang"]);
	        	$video_id = Tools::getValue('video_id_'.$language["id_lang"]); 

	        	$check = Db::getInstance()->ExecuteS('SELECT id_pet FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE id_product = '.$id_product.' AND shop_id = '.(int)Context::getContext()->shop->id.' AND lang_id = '.$language["id_lang"].';');
	        	if (!empty($check)) {
	        		$sql[] = 'UPDATE `'._DB_PREFIX_.'pk_product_extratabs` SET custom_tab_name = "'.$custom_tab_name.'", custom_tab = \''.$custom_tab.'\', video="'.$video_id.'" WHERE id_product = '.$id_product.' AND shop_id = '.(int)Context::getContext()->shop->id.' AND lang_id = '.$language["id_lang"].';';
	    		} else {
	    			$sql[] = 'INSERT INTO `'._DB_PREFIX_.'pk_product_extratabs` (`id_product`, `shop_id`, `lang_id`, `video`, `custom_tab_name`, `custom_tab`) VALUES ('.$id_product.', '.(int)Context::getContext()->shop->id.', '.$language["id_lang"].', "'.$video_id.'", "'.$custom_tab_name.'", "'.$custom_tab.'")';	
	    			}        		       			
	        }                
	        $this->runSql($sql);
	    }
    }
    /*	FRONTPAGE HOOKS	*/
    public function hookProductTab($params) {
    	$s = $this->getOptions("hookProductTab");
        $id_product = Tools::getValue('id_product');
        $sid = (int)Context::getContext()->shop->id;
		$lid = $this->context->language->id;
        $getCustomTab = Db::getInstance()->ExecuteS('SELECT `custom_tab_name` FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.$sid.' AND lang_id = '.$lid);        
        $getVideo = Db::getInstance()->ExecuteS('SELECT `video` FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.$sid.' AND lang_id = '.$lid);
        $tab = "";
        if (($s["custom_tab"]) && isset($getCustomTab[0]["custom_tab_name"]))
            $tab .= "<h3 class='page-product-heading' data-title=\"12\"><span>".(($getCustomTab[0]["custom_tab_name"] == '') ? "Custom Tab" : $getCustomTab[0]["custom_tab_name"])."</span></h3>";

        if (($s["product_video"] == 1) && (!empty($getVideo)))
        	if ($getVideo[0]["video"] != "")
            	$tab .= "<h3 class='page-product-heading' data-title=\"13\"><span>".$this->l('Video')."</span></h3>";

	    return $tab;
    }

    public function hookProductTabContent($params) {
    	$s = $this->getOptions("hookProductTabContent");
        $id_product = Tools::getValue('id_product');
        $sid = (int)Context::getContext()->shop->id;
		$lid = $this->context->language->id;
        $getCustomTab = Db::getInstance()->ExecuteS('SELECT `custom_tab`, `custom_tab_name` FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.$sid.' AND lang_id = '.$lid);
       	$getVideo = Db::getInstance()->ExecuteS('SELECT `video` FROM `'._DB_PREFIX_.'pk_product_extratabs` WHERE  id_product = '.$id_product.' AND shop_id = '.$sid.' AND lang_id = '.$lid);
        if (isset($getCustomTab[0])) {
            $this->context->smarty->assign(array(
                'pk_custom_tab' => $getCustomTab[0]["custom_tab"],
                'pk_custom_tab_name' => $getCustomTab[0]["custom_tab_name"],
                'pk_video_id' => $getVideo[0]["video"]
            ));
        }
	    return $this->display(__FILE__, 'views/frontend/customcontent.tpl');	    
    }

    public function installQuickAccess(){

      $qick_access = new QuickAccess();
      $qick_access->link = 'index.php?controller=AdminModules&configure='.$this->name.'&tab_module=front_office_features&module_name='.$this->name;
      $qick_access->new_window = false;

      $languages = Language::getLanguages(false);
      foreach ($languages as $language)
          $qick_access->name[$language['id_lang']]= $this->theme_name.' Settings';

      $qick_access->add();  
      if(!$qick_access->id)
            return FALSE;
        
      return true;
    }

	public function hookHeader($params) {	

			
		$theme_settings = $this->getOptions();
		$themeUrl = __PS_BASE_URI__.'themes/'.strtolower(_THEME_NAME_).'/css/';
		$this->context->controller->addCSS(($this->_path).'css/themesettings.css', 'all');
		$this->context->controller->addCSS($themeUrl.'alysum.css', 'all');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === FALSE) {
			$this->context->controller->addCSS($themeUrl.'responsive-all-mobile-devices.css', 'all');
			$this->context->controller->addCSS($themeUrl.'responsive-tablet-landscape.css', 'all');
			$this->context->controller->addCSS($themeUrl.'responsive-tablet-portrait.css', 'all');			
			$this->context->controller->addCSS($themeUrl.'responsive-phone-landscape.css', 'all');
			$this->context->controller->addCSS($themeUrl.'responsive-phone-portrait.css', 'all');					
		}
		$this->context->controller->addCSS($this->local_path."css/presets/preset".$theme_settings["preset"].".css", 'all');
		$this->context->controller->addCSS($this->generatedFile, 'all');
		$this->context->controller->addCSS($this->customcssFile, 'all');		
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
			$this->context->controller->addCSS(($this->_path).'css/ie.css', 'all');
		$this->context->controller->addJS(($this->_path).'js/countdown.js');
		$this->context->controller->addJS(($this->_path).'js/modernizr.custom.08176.js');
		$this->context->controller->addJS(($this->_path).'js/impromtu.js');
		$this->context->controller->addJS(($this->_path).'js/jquery.flexisel.js');
		$this->context->controller->addJS(($this->_path).'js/wayfinder.js');		
		$this->context->controller->addJS(($this->_path).'js/commonscripts.js');		
		if ((($this->context->controller->php_self != "index") && ($theme_settings['show_map'] == 1)) || $theme_settings['show_map_bottom'] == 1)
			$this->context->controller->addJS(Tools::getShopProtocol().'maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false');

		$this->context->smarty->assign(
			'theme_settings', $this->prepareResult("head", $theme_settings),
			'shopID', (int)$this->context->shop->id			
		);	
		
		return ($this->display(__FILE__, 'views/frontend/variables.tpl'));
	}

	protected function getCacheId($name = null)
	{
		if ($name === null)
			$name = $this->name;
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

}