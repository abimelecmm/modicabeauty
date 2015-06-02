<?php

class pk_twitterwidget extends Module
{
	private $_html = '';
	private $_postErrors = array();
	
    public function __construct()
    {
        $this->name = 'pk_twitterwidget';
        $this->tab = 'front_office_features';
        $this->version = 1.2;
        $this->bootstrap = true;
        $this->author = 'promokit.eu';

        parent::__construct();

        /* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Twitter Feed');
        $this->description = $this->l('Twitter Feed in footer');
		$this->full_url = _MODULE_DIR_.$this->name.'/';
		
		$config = Configuration::getMultiple(array('PS_TWITTER_USERNAME','PS_TWITTER_COUNT'));
		if (empty($config['PS_TWITTER_USERNAME']))
			$this->warning = $this->l('Please insert your Twitter username');		
			}

     function install()
    {
        if (!parent::install() OR !$this->registerHook('footer') OR !$this->registerHook('header') 
			OR !Configuration::updateValue('PS_TWITTER_USERNAME', 'PromokitTest')
			OR !Configuration::updateValue('PS_TWITTER_COUNT', '2')
			OR !Configuration::updateValue('PS_TW_CONSUMER_KEY', 'YibflmnRpCfauwvqUteWg')
			OR !Configuration::updateValue('PS_TW_CONSUMER_SECRET', 'liXY1XKnRn0XB6R8YKTc9skiNbmRdUjBq2KVPvCrjY')
			OR !Configuration::updateValue('PS_TW_AT', '2156330221-WGbrSeeO7MZ98rimGbRpB7mLfzyfN1xl7z8s6No')
			OR !Configuration::updateValue('PS_TW_AT_SECRET', 'NkZb58AXNuF5l1fHf0ykJBAuyL9o7jwq4EmYJgOc0TNqB')
			)
			return false;
		return true;
    }
    public function getKeys() {
    	
    	$keys = array();
    	$keys["consumer_key"] = Configuration::get('PS_TW_CONSUMER_KEY');
    	$keys["consumer_secret"] = Configuration::get('PS_TW_CONSUMER_SECRET');
    	$keys["access_token"] = Configuration::get('PS_TW_AT');
    	$keys["access_token_secret"] = Configuration::get('PS_TW_AT_SECRET');

    	return $keys;

    }
	public function uninstall()
	{
		if (!Configuration::deleteByName('PS_TWITTER_USERNAME')
			OR !Configuration::deleteByName('PS_TWITTER_COUNT')
		    OR !parent::uninstall())
			return false;
		return true;
	}	
	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'css/jquery.tweet.css', 'all');
		$this->context->controller->addJS(($this->_path).'js/jquery.tweet.js');
		//$this->context->controller->addJS(($this->_path).'js/jquery.flexisel.js');
	}
    function hookFooter($params)
    {

		global $smarty;
		$smarty->assign('username', Configuration::get('PS_TWITTER_USERNAME'));
		$smarty->assign('count', Configuration::get('PS_TWITTER_COUNT'));
		$smarty->assign('tw_this_path', $this->full_url."ajax.php");
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	
	
	public function _displayForm()
	{

		$this->_html .= '
		<form  class="width2" action="'.$_SERVER['REQUEST_URI'].'" method="post" style="margin-bottom:10px;">
			<fieldset><legend style="line-height:32px"><img src="'.$this->_path.'logo.png" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Twitter username').'</label>
				<div class="margin-form"><input type="text" name="twitterusername" id="twitterusername" value="'.Configuration::get('PS_TWITTER_USERNAME').'" size="50">
				</div>
				<label>'.$this->l('Tweet\'s number').'</label>
				<div class="margin-form"><input type="text" name="count" id="count" value="'.Configuration::get('PS_TWITTER_COUNT').'" size="20" >
				</div><br/>
				<label>'.$this->l('Consumer key').'</label>
				<div class="margin-form"><input type="text" name="consumer_key" id="consumer_key" value="'.Configuration::get('PS_TW_CONSUMER_KEY').'" size="70" >					
				</div>	
				<label>'.$this->l('Consumer secret').'</label>
				<div class="margin-form"><input type="text" name="consumer_secret" id="consumer_secret" value="'.Configuration::get('PS_TW_CONSUMER_SECRET').'" size="70" >
				</div>												
				<label>'.$this->l('Access token').'</label>
				<div class="margin-form"><input type="text" name="access_token" id="access_token" value="'.Configuration::get('PS_TW_AT').'" size="70" >
				</div>												
				<label>'.$this->l('Access token secret').'</label>
				<div class="margin-form"><input type="text" name="access_token_secret" id="access_token_secret" value="'.Configuration::get('PS_TW_AT_SECRET').'" size="70" >
				</div><center><input type="submit" name="btnSetting" value="'.$this->l('Save').'" class="button" /></center>												<br/><br/>
				<strong>Look at this video from our partner how to get Twitter API keys what you need above:</strong><br/><br/>
				<object width="640" height="425"><param name="movie" value="http://www.youtube.com/watch?v=5PUC9yGS4RI"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/5PUC9yGS4RI?hl=en_US&amp;version=3" type="application/x-shockwave-flash" width="640" height="425" allowscriptaccess="always" allowfullscreen="true"></embed></object><br/><br/>
				<br />
			</fieldset>
		</form>
		';
	}
	private function _postValidation()
	{
		if (isset($_POST['btnSetting']))
		{
			if (empty($_POST['twitterusername']))
				$this->_postErrors[] = '<p>'.$this->l('Please insert your Twitter username').'</p>';
			if (empty($_POST['count']))
				$this->_postErrors[] = '<p>'.$this->l('Please insert number of tweets displayed').'</p>';					
			//echo '<div class="conf confirm">'.$this->l('Configuration updated').'</div>';
					
		}
	}	
	private function _postProcess()
	{
		if(isset($_POST['btnSetting']))
		{
	 		Configuration::updateValue('PS_TWITTER_USERNAME', $_POST['twitterusername']);
			Configuration::updateValue('PS_TWITTER_COUNT', $_POST['count']);
			Configuration::updateValue('PS_TW_CONSUMER_KEY', $_POST['consumer_key']);
			Configuration::updateValue('PS_TW_CONSUMER_SECRET', $_POST['consumer_secret']);
			Configuration::updateValue('PS_TW_AT', $_POST['access_token']);
			Configuration::updateValue('PS_TW_AT_SECRET', $_POST['access_token_secret']);
			$this->_html .= '<div class="conf confirm">'.$this->l('Settings Saved').'</div>';
		}
		
	}	
	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('btnSetting'))	
		//if (!empty($_POST) || isset($_GET['delete']) )
		{

			$this->_postValidation();
			if (!sizeof($this->_postErrors))
				$this->_postProcess();					
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'. $err .'</div>';
				$this->_html .= '<br><p><a href="javascript:history.back(1)" class="button">'.$this->l('back').'</a></p>';
		}
		else $this->_displayForm();
		return $this->_html;
	}	
	
}

?>
