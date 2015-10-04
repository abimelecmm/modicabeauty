<?php
/*
	Module Name: pk_blockfacebooklike
	Version: 2.5
	Author: Marek Mnishek
	Author URI: http://promokit.eu
	Copyright (C) 2013 promokit.eu 

*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class pk_blockfacebooklike extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'pk_blockfacebooklike';
		$this->tab = 'front_office_features';
		$this->version = '2.5';
		$this->author = 'promokit.eu';
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Facebook Like Box');
		$this->description = $this->l('Put your Facebook fan page like box on your site.');
		$this->cacheName = _PS_MODULE_DIR_.$this->name.'/cache/tpl/fans_'.(int)Context::getContext()->shop->id.'.tpl';
	}

	public function install()
	{
		if (!parent::install() OR 
			!$this->registerHook('footer') OR 
			!$this->registerHook('header') OR
			!Configuration::updateValue('PLLB_URL', 'promokit.eu') OR		
			!Configuration::updateValue('PLLB_TITLE', 'Facebook') OR		
			!Configuration::updateValue('PLLB_FACES', 1) OR
			!Configuration::updateValue('PLLB_COMPANY_NAME', 0) OR
			!Configuration::updateValue('PLLB_COMPANY_LOGO', 0) OR 
			!Configuration::updateValue('PLLB_FB_APP_ID', '') OR 
			!Configuration::updateValue('PLLB_NUM', 6))
			return false;
		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall() OR
		!Configuration::deleteByName('PLLB_URL') OR		
		!Configuration::deleteByName('PLLB_NUM') OR
		!Configuration::deleteByName('PLLB_TITLE') OR	
		!Configuration::deleteByName('PLLB_COMPANY_NAME') OR
		!Configuration::deleteByName('PLLB_COMPANY_LOGO') OR 	
		!Configuration::deleteByName('PLLB_FB_APP_ID') OR 	
		!Configuration::deleteByName('PLLB_FACES'))
			return false;
		return true;
	}
	
	private function _postProcess()
	{
		Configuration::updateValue('PLLB_URL', Tools::getValue('url'));
		Configuration::updateValue('PLLB_TITLE', Tools::getValue('title'));
		Configuration::updateValue('PLLB_FACES', Tools::getValue('faces'));
		Configuration::updateValue('PLLB_COMPANY_NAME', Tools::getValue('company_name'));
		Configuration::updateValue('PLLB_COMPANY_LOGO', Tools::getValue('company_logo'));	
		Configuration::updateValue('PLLB_NUM', Tools::getValue('num'));	
		Configuration::updateValue('PLLB_FB_APP_ID', Tools::getValue('fb_app_id'));
				
		$this->_html .= $this->displayConfirmation($this->l('Your settings have been updated.'));
	}
	
	public function getContent()
	{
		$this->_html .= '';
		$msg = "";
		if (Tools::isSubmit('submit')) {			
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'.$err.'</div>';
			
			$this->_html .= $this->cleanCache();
		}
		
		$this->_displayForm();
		
		return $this->_html;
	}
	
	private function _displayForm()
	{
		if (!($languages = Language::getLanguages()))
			 return false;		
		$this->_html .= '
		<style>.paddtop0 {padding-top:0 !important}</style>
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post" id="module_form" class="defaultForm form-horizontal">
		<div class="panel" id="fieldset_0">												
			<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Facebook Settings').'</div>
			<div class="form-wrapper">	
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Facebook App Token').'</label>
					<div class="col-lg-6">
						<input size="60" type="text" name="fb_app_id" value="'.Tools::getValue('fb_app_id', Configuration::get('PLLB_FB_APP_ID')).'"/>
						<p class="help-block"><a href="https://www.youtube.com/watch?v=wyyCHDZ1qww">'.$this->l('How to Create Facebook App').'</a> '.$this->l('and').' <a href="https://developers.facebook.com/tools/access_token/">'.$this->l('Get App Token').'</a></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Module Title').'</label>
					<div class="col-lg-6">
						<input size="60" type="text" name="title" value="'.Tools::getValue('title', Configuration::get('PLLB_TITLE')).'"/>
						<p class="help-block">'.$this->l('The title of box on the front page').'</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Facebook Page Name').'</label>
					<div class="col-lg-6">
						<input size="60" type="text" name="url" value="'.Tools::getValue('url', Configuration::get('PLLB_URL')).'"/>
						<p class="help-block">'.$this->l('The name of the Facebook Page').'</p>
					</div>
				</div>
				<!--<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Face number').'</label>
					<div class="col-lg-6">
						<input size="6" type="text" name="num" value="'.Tools::getValue('num', Configuration::get('PLLB_NUM')).'"/>
						<p class="help-block">'.$this->l('Number of visible faces').'</p>
					</div>
				</div>-->
				<div class="form-group">
					<label class="control-label col-lg-3 paddtop0">'.$this->l('Show Faces').'</label>
					<div class="col-lg-6">
						<input type="checkbox" name="faces" '.(Tools::getValue('faces', Configuration::get('PLLB_FACES')) ? "value='true' checked='checked'" : "value='false'").' />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3 paddtop0">'.$this->l('Show Company name').'</label>
					<div class="col-lg-6">
						<input type="checkbox" name="company_name" '.(Tools::getValue('company_name', Configuration::get('PLLB_COMPANY_NAME')) ? "value='true' checked='checked'" : "value='false'").' />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3 paddtop0">'.$this->l('Show Company Logo').'</label>
					<div class="col-lg-6">
						<input type="checkbox" name="company_logo" value="'.(Tools::getValue('company_logo', Configuration::get('PLLB_COMPANY_LOGO')) ? "true" : "false").'"'.(Tools::getValue('company_logo', Configuration::get('PLLB_COMPANY_LOGO')) ? "checked='checked'" : "").' />
					</div>
				</div>
				<div class="panel-footer">
					<button type="submit" value="1" id="module_form_submit_btn" name="submit" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save').'</button>
				</div>
			</div>
			</div>
		</form>';
	}
	
// -----------------------------------------------------------------	
	public function getFacebookData($name) {

		$filecontent = file_get_contents('https://graph.facebook.com/'.$name.'?access_token='.Configuration::get('PLLB_FB_APP_ID'));
		//echo 'https://graph.facebook.com/'.$name.'?access_token='.Configuration::get('PLLB_FB_APP_ID');
		if ($filecontent == false)
			$msg = '"allow_url_fopen" should be "on" in php.ini';
		else {
			$facebookInfo = json_decode($filecontent);
			Configuration::updateValue('PLLB_DATA_NAME', $facebookInfo->name);
			Configuration::updateValue('PLLB_DATA_LIKES', $facebookInfo->likes);
			Configuration::updateValue('PLLB_DATA_ID', $facebookInfo->id);
			$msg = "";
		}
		return $msg;

	}
	public function getAttribute($attrib, $tag){
		  //get attribute from html tag
		  $re = '/'.$attrib.'=["\']?([^"\' ]*)["\' ]/is';
		  preg_match($re, $tag, $match);
		  
		  if ($match)
		    return urldecode($match[1]);

		  return false;
	}

	public function cleanCache() {

		$cleaned = "";
		if ((!file_exists($this->cacheName)) || (!unlink($this->cacheName)))
			$cleaned = $this->displayError($this->l('Cache is not updated'));
        
		return $cleaned;
	}

	public function hookFooter()
	{

		$facebook_username = Configuration::get('PLLB_URL');
		$cacheName = $this->cacheName;
		$err = "";
		// generate the cache version if it doesn't exist or it's too old!
		$ageInSeconds = 3600; // 1 hour

		if(!file_exists($cacheName) || (filemtime($cacheName) + $ageInSeconds < time()) || (filesize($cacheName) == 0)) {	
			//$dom = simplexml_load_file($cacheName);
			$local_parts = explode("-", Context::getContext()->language->language_code);
			if (!isset($local_parts[1]))
				$local_parts[1] = $local_parts[0];
			$locale = $local_parts[0].'_'.strtoupper($local_parts[1]);

			$curl = 'https://www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/'.$facebook_username.'&locale='.$locale.'&connections='.Configuration::get('PLLB_NUM');
			//print_r($curl);
			$ch = curl_init($curl);
			
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1");
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		    $result = curl_exec($ch);
		    curl_close($ch);
		    
		    $doc = new DOMDocument('1.0', 'utf-8');
			@$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . $result);
			//print_r($doc->saveHTML());
			$line = $doc->getElementById("u_0_3");
			Configuration::updateValue('PK_FB_PPLS', $line->nodeValue);
			$peopleList = array();
			$i=0;
			//$text = $doc->getElementById('u_0_4')->nodeValue; <----------

			foreach ($doc->getElementsByTagName('ul')->item(0)->childNodes as $child) {
		    	$raw = $doc->saveXML($child);
		    	$li = preg_replace("/<li[^>]+\>/i", "", $raw);
		    	$peopleList[$i] = preg_replace("/<\/li>/i", "", $li);			    				    	
				$i++;
			}
			if (!$fileHolder = @fopen($cacheName, 'w')) {
				$err = "Can't open settings file! \"".$cacheName."\"<br/>";
			} else {	
				$pth = _PS_MODULE_DIR_.$this->name.'/cache/img/'.(int)Context::getContext()->shop->id.'/';

				if (!file_exists($pth)) {
				    mkdir($pth, 0777, true);
				} else {
					$files = glob($pth.'*'); // get all file names
					foreach($files as $file){ // iterate files
					  if(is_file($file))
					    unlink($file); // delete file
					}
				}
				$li = "";
				foreach ($peopleList as $key => $code) {

					$name = $this->getAttribute('title', $code);
					$image = $this->getAttribute('src', $code);
					$link = $this->getAttribute('href', $code);			

					$face = $pth.$key.".jpg";				
					$copy = copy(html_entity_decode($image), $face);
					
					if (!$copy) {
						$wrapper = "<a href='".$link."' title='".$name."' target='_blank'></a>";
					} else {
						//$imgPath = Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/cache/img/'.(int)Context::getContext()->shop->id.'/'.$key.'.jpg';
						$data = file_get_contents($face);
						$img_in_base64 = 'data:image/jpg;base64,'.base64_encode($data);
						
						if ($link != "") {
							$face_item = "<a href='".$link."' title='".$name."' target='_blank' style='background-image:url(".$img_in_base64.")'></a>";
						} else 	{
							$face_item = "<span title='".$name."' style='background-image:url(".$img_in_base64.")'></span>";
						}
	
					}					
					$li .= "<li class='face_".$key."'>".$face_item."<div class='fb_name ellipsis'>".$name."</div></li>";
				}			
				
				if (!fwrite($fileHolder, $li)) {
					$err = "Can't write settings! ".$cacheName."<br/>";
				}				
				fclose($fileHolder);	
				$err = $this->getFacebookData($facebook_username);
			}
		}	

		$FB_data["name"] = Configuration::get('PLLB_DATA_NAME');
		$FB_data["likes"] = Configuration::get('PLLB_DATA_LIKES');
		$FB_data["id"] = Configuration::get('PLLB_DATA_ID');

		$this->smarty->assign(array(
			'FB_page_URL' => 'https://www.facebook.com/'.$facebook_username,
			'FB_data' => $FB_data,
			'show_faces' => Configuration::get('PLLB_FACES'),
			'FB_title' => Configuration::get('PLLB_TITLE'),
			'company_name' => Configuration::get('PLLB_COMPANY_NAME'),
			'company_logo' => Configuration::get('PLLB_COMPANY_LOGO'),
			'modulePath' => $this->cacheName,
			'err' => $err,
			'ppls' => Configuration::get('PK_FB_PPLS')
		));

		return $this->display(__FILE__, $this->name.'.tpl');
		
	}
	public function hookHeader($params)
	{
		$this->context->controller->addCSS($this->_path.$this->name.'.css', 'all');
	}
	
		
	public function hookRightColumn($params)
	{
		return $this->hookFooter($params);
	}

	public function hookLeftColumn($params)
	{
		return $this->hookFooter($params);
	}

		
}