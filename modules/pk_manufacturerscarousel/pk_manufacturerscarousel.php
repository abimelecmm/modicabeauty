<?php
/*
*
*  @author Promokit Co. <support@promokit.eu>
*  @copyright  2011-2012 Promokit Co.
*  @version  Release: $Revision: 0 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Promokit Co.
*/

if (!defined('_PS_VERSION_'))
	exit;

class pk_manufacturerscarousel extends Module
{
    public function __construct()
    {
        $this->name = 'pk_manufacturerscarousel';
        $this->tab = 'front_office_features';
        $this->version = 1.1;
		$this->author = 'promokit.eu';
		$this->need_instance = 0;
		$this->bootstrap = true;

        parent::__construct();

		$this->displayName = $this->l('Manufacturers Carousel');
        $this->description = $this->l('Displays a block of manufacturers/brands');
    }

	public function install()
	{
		Configuration::updateValue('MCAROUSEL_DISPLAY_TITLE', 0);
		Configuration::updateValue('MCAROUSEL_DISPLAY_TEXT_NB', 8);
        return 
        	parent::install() && 
        	$this->registerHook('hook_home_01') && 
        	$this->registerHook('hook_home_02') && 
        	$this->registerHook('hook_home_03') && 
        	$this->registerHook('hook_home_04') && 
        	$this->registerHook('hook_home_05') && 
        	$this->registerHook('hook_home_06') && 
        	$this->registerHook('hook_home_07') && 
        	$this->registerHook('narrow_top') && 
        	$this->registerHook('narrow_middle') && 
        	$this->registerHook('narrow_bottom') && 
        	$this->registerHook('displayHeader');
    }

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitBlockManufacturers'))
		{

			$text_list = (int)(Tools::getValue('text_list'));
			$text_nb = (int)(Tools::getValue('text_nb'));
			if ($text_list && !Validate::isUnsignedInt($text_nb))
				$errors[] = $this->l('Invalid number of elements');			
			else
			{
				Configuration::updateValue('MCAROUSEL_DISPLAY_TEXT_NB', $text_nb);
				Configuration::updateValue('MCAROUSEL_DISPLAY_TITLE', $text_list);
			}
			if (isset($errors) && count($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			parent::_clearCache($this->name.'.tpl');
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" id="module_form" class="defaultForm form-horizontal">
			<div class="panel" id="fieldset_0">												
				<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Manufacturers Carousel Settings').'</div>
				<div class="form-wrapper">
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Display Titles').'</label>
						<div class="col-lg-6"><input type="checkbox" name="text_list" id="text_list" value="1" '.(Tools::getValue('text_list', Configuration::get('MCAROUSEL_DISPLAY_TITLE')) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Define the number of products').'</label>
						<div class="col-lg-1">
							<input type="text" size="5" name="text_nb" value="'.(int)(Tools::getValue('text_nb', Configuration::get('MCAROUSEL_DISPLAY_TEXT_NB'))).'" />
						</div>
					</div>
					<div class="panel-footer">
					<button type="submit" value="1" name="submitBlockManufacturers" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save').'</button>
					</div>
				</div>
			</div>
		</form>';
		return $output;
	}

	public function hookdisplayHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).$this->name.'.css', 'all');
		//$this->context->controller->addJS($this->_path.'js/jquery.flexisel.js');
	}
	public function getData() {
		$this->smarty->assign(array(
			'manufacturers' => Manufacturer::getManufacturers(),
			'text_list_nb' => Configuration::get('MCAROUSEL_DISPLAY_TEXT_NB'),
			'show_title' => Configuration::get('MCAROUSEL_DISPLAY_TITLE'),
			'display_link_manufacturer' => Configuration::get('PS_DISPLAY_SUPPLIERS'),
		));
	}
	public function hookHook_home_01($params)
	{
		$status = $this->getModuleState("hook_home_01");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookHook_home_02($params)
	{
		$status = $this->getModuleState("hook_home_02");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookHook_home_03($params)
	{
		$status = $this->getModuleState("hook_home_03");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookHook_home_04($params)
	{
		$status = $this->getModuleState("hook_home_04");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hookhook_home_05($params)
	{
		$status = $this->getModuleState("hook_home_05");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}	
	public function hookHook_home_06($params)
	{
		$status = $this->getModuleState("hook_home_06");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}	
	public function hookhook_home_07($params)
	{
		$status = $this->getModuleState("hook_home_07");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hooknarrow_middle($params)
	{
		$status = $this->getModuleState("narrow_middle");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hooknarrow_top($params)
	{
		$status = $this->getModuleState("narrow_top");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
	 	}
	}
	public function hooknarrow_bottom($params)
	{
		$status = $this->getModuleState("narrow_bottom");
 		if (($status == 1) && ($this->context->controller->php_self == "index")) {
 			if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
	 			$this->getData();
	 		return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
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
