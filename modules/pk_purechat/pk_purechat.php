<?php

class pk_purechat extends Module {

	public function __construct() {
		$this->name = 'pk_purechat';
		$this->tab = 'advertising_marketing';
		$this->version = '1.0';
		$this->need_instance = 0;
		$this->author = 'promokit.eu';
		parent::__construct();
		$this->displayName = $this->l('PureChat');
		$this->description = $this->l('Integrate Purechat on your shop.');

		$this->affiliateurl = 'http://www.purechat.com';
	}

	function install()
	{
		if (!parent::install()
			|| !$this->registerHook('footer')
			|| !Configuration::updateValue('pk_PURECHAT', ''))
			return false;
		return true;
	}

	function uninstall()
	{
		if (!Configuration::deleteByName('pk_PURECHAT') OR !parent::uninstall())
			return false;
		return true;
	}

	public function getContent($tab = 'AdminModules')
	{

        $cookie = Context::getContext()->cookie;
        $currentIndex = AdminController::$currentIndex;

		$token = Tools::getAdminToken($tab.(int)Tab::getIdFromClassName($tab).(int)$cookie->id_employee);
		if (Tools::isSubmit('submitConf')) {
			$purechatscript = trim(Tools::getValue('purechatscript'));

			if(Configuration::updateValue('pk_PURECHAT', $purechatscript))
				Tools::redirectAdmin($currentIndex.'&modulename='.$this->name.'&configure='.$this->name.'&conf=6&token='.$token);
		}
		return $this->displayForm();
	}

	public function displayForm()	{

		$iso = Language::getIsoById($this->context->language->id);
		return '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><a href="http://www.prestatoolbox.'.(($iso != 'fr')?'com':'fr').'/?utm_source=module&utm_medium=cpc&utm_campaign='.$this->name.'"><img src="'.$this->_path.'logo.gif" alt="" /></a>'.$this->l('Settings').'</legend>
				<label><a href="'.$this->affiliateurl.'" target="_blank" title="'.$this->l('PureChat live chat solutions Create your account').'"><img src="'.$this->_path.'images/logo-purechat.png" alt="'.$this->l('PureChat live chat solutions').'" /></a></label>
				<div class="margin-form"><input type="text" name="purechatscript" value="'.Configuration::get('pk_PURECHAT').'" />
					<p>'.$this->l('To configure this module, after registering on').' <a href="'.$this->affiliateurl.'" title="'.$this->l('Above all things, subscribe to').' PURECHAT" target="_blank" style="color:orange"><b>PURECHAT</b></a>, '.$this->l('get code to insert the script and find the ID of your site in bold red represent the example below. Enter the ID above.').'</p>
					<p class="clear">&lt;script type=\'text/javascript\'&gt;(function () { var done = false; var script = document.createElement(\'script\'); script.async = true; script.type = \'text/javascript\'; script.src = \'https://widget.purechat.com/VisitorWidget/WidgetScript\'; document.getElementsByTagName(\'HEAD\').item(0).appendChild(script); script.onreadystatechange = script.onload = function (e) { if (!done &amp;&amp; (!this.readyState || this.readyState == \'loaded\' || this.readyState == \'complete\')) { var w = new PCWidget({ c: \'<b><span style="color:#900" title="\'.$this->l(\'Copy your site ID represented like this one\').\'">8266ec11-4efa-403c-bb2f-504a3e19cf7d</span></b>\', f: true }); done = true; } }; })();&lt;/script&gt;</p>
				</div><input type="submit" name="submitConf" value="'.$this->l('Save').'" class="button" />
			</fieldset>
		</form>';
	}

	function hookFooter($params) {
		$pure = Configuration::get('pk_PURECHAT');
		if ($pure) {			
			$this->smarty->assign(array('purechat' => $pure));
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}
}

?>
