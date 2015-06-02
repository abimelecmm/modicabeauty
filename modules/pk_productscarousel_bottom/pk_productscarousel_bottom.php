<?php
/*
*
*  @author Promokit Co. <support@promokit.eu>
*  @copyright  2011-2014 Promokit Co.
*  @version  Release: $Revision: 0 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Promokit Co.
*/

class pk_productscarousel_bottom extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'pk_productscarousel_bottom';
		$this->tab = 'front_office_features';
		$this->version = '1.9';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Bottom Products Carousel');
		$this->description = $this->l('Displays products carousel on your homepage');
	}

	function install()
	{
		if (!Configuration::updateValue('BC_PRODUCTS_NUMBER', 6) OR 
			!Configuration::updateValue('BC_PRODUCTS_NEW', 1) OR 
			!Configuration::updateValue('BC_PRODUCTS_SPE', 1) OR 
			!Configuration::updateValue('BC_PRODUCTS_BES', 1) OR 
			!Configuration::updateValue('BC_PRODUCTS_FEA', 1) OR 
			!Configuration::updateValue('BC_TYPE_ACTIVE', 'fea') OR 
			!Configuration::updateValue('BC_PRODUCT_HOVER', 1) OR 
			!Configuration::updateValue('BC_PRODUCT_PRICE', 1) OR 
			!Configuration::updateValue('BC_PRODUCT_PRICE_WR', 1) OR 
			!Configuration::updateValue('BC_PRODUCT_BUTTON', 1) OR 
			!Configuration::updateValue('BC_PRODUCT_RATING', 1) OR
			!Configuration::updateValue('BC_COUNTDOWN', 0) OR
			!Configuration::updateValue('BC_PRODUCTS_VISIBLE', 5) OR 
			!Configuration::updateValue('BC_AUTOPLAY', 1) OR 
			!Configuration::updateValue('BC_PRODUCTS_TO_SCROLL', 1) OR 
			!parent::install() OR 
			!$this->registerHook('displayHeader') OR
			!$this->registerHook('hook_home_01') OR
            !$this->registerHook('hook_home_02') OR
            !$this->registerHook('hook_home_03') OR
            !$this->registerHook('narrow_top') OR
            !$this->registerHook('narrow_middle') OR
            !$this->registerHook('narrow_bottom')
			) return false;
		return true;
	}


	public function uninstall()
	{
		return (parent::uninstall());
	}

	public function getContent()
	{
		$output = "";
		if (Tools::isSubmit('carouselSettings'))
		{
			Configuration::updateValue('BC_PRODUCTS_NEW', Tools::getValue('BC_PRODUCTS_NEW'));
			Configuration::updateValue('BC_PRODUCTS_FEA', Tools::getValue('BC_PRODUCTS_FEA'));
			Configuration::updateValue('BC_PRODUCTS_BES', Tools::getValue('BC_PRODUCTS_BES'));
			Configuration::updateValue('BC_PRODUCTS_SPE', Tools::getValue('BC_PRODUCTS_SPE'));
			Configuration::updateValue('BC_TYPE_ACTIVE', Tools::getValue('BC_TYPE_ACTIVE'));
			Configuration::updateValue('BC_PRODUCT_HOVER', Tools::getValue('BC_PRODUCT_HOVER'));
			Configuration::updateValue('BC_PRODUCT_PRICE', Tools::getValue('BC_PRODUCT_PRICE'));
			Configuration::updateValue('BC_PRODUCT_PRICE_WR', Tools::getValue('BC_PRODUCT_PRICE_WR'));
			Configuration::updateValue('BC_PRODUCT_RATING', Tools::getValue('BC_PRODUCT_RATING'));
			Configuration::updateValue('BC_COUNTDOWN', Tools::getValue('BC_COUNTDOWN'));
			Configuration::updateValue('BC_PRODUCTS_VISIBLE', Tools::getValue('nbr_vis'));
			Configuration::updateValue('BC_AUTOPLAY', Tools::getValue('BC_AUTOPLAY'));			
			Configuration::updateValue('BC_PRODUCT_BUTTON', Tools::getValue('BC_PRODUCT_BUTTON'));

			$nbr = intval(Tools::getValue('nbr'));
			$products_to_scroll = intval(Tools::getValue('products_to_scroll'));

			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of products');
			else
				Configuration::updateValue('BC_PRODUCTS_NUMBER', $nbr);  /*---------------------*/					
				Configuration::updateValue('BC_PRODUCTS_TO_SCROLL', $products_to_scroll);/*---------------------*/			
			if (isset($errors) AND sizeof($errors))
				$output = $this->displayError(implode('<br />', $errors));
			else
				$output = $this->displayConfirmation($this->l('Settings updated'));

			parent::_clearCache($this->name.'.tpl');
		}

		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$t_a = Configuration::get('BC_TYPE_ACTIVE');
		$fea = Configuration::get('BC_PRODUCTS_FEA');
		$new = Configuration::get('BC_PRODUCTS_NEW');
		$spe = Configuration::get('BC_PRODUCTS_SPE');
		$bes = Configuration::get('BC_PRODUCTS_BES');
		$p_hover = Configuration::get('BC_PRODUCT_HOVER');
		$p_price = Configuration::get('BC_PRODUCT_PRICE');
		$p_price_wr = Configuration::get('BC_PRODUCT_PRICE_WR');
		$p_button = Configuration::get('BC_PRODUCT_BUTTON');
		$p_rating = Configuration::get('BC_PRODUCT_RATING');
		$countdown = Configuration::get('BC_COUNTDOWN');
		$bc_autoplay = Configuration::get('BC_AUTOPLAY');
		$output = '
		<style type="text/css">
			.bootstrap .form-horizontal .control-label {padding-top: 0 !important;}
			.form-wrapper h4 {margin-bottom:15px}
		</style>
	   	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class="defaultForm form-horizontal blockcms" id="configuration_form">
		   	<div class="panel" id="fieldset_0">												
				<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Carousel Settings').'</div>
				<div class="form-wrapper">
					<h4>'.$this->l('Products type').'</h4>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('New products').'</label>
						<div class="col-lg-6"><input type="radio" name="BC_TYPE_ACTIVE" value="new" '.(($t_a == 'new') ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Featured products').'</label>
						<div class="col-lg-6"><input type="radio" name="BC_TYPE_ACTIVE" value="fea" '.(($t_a == 'fea') ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Special products').'</label>
						<div class="col-lg-6"><input type="radio" name="BC_TYPE_ACTIVE" value="spe" '.(($t_a == 'spe') ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Bestsellers').'</label>
						<div class="col-lg-6"><input type="radio" name="BC_TYPE_ACTIVE" value="bes" '.(($t_a == 'bes') ? 'checked="checked" ' : '').' /></div>
					</div>
					<h4>'.$this->l('Carousel Settings').'</h4>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('The number of products in carousel').'</label>
						<div class="col-lg-1"><input type="text" size="1" name="nbr" value="'.(Configuration::get('BC_PRODUCTS_NUMBER')).'" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('The number of visible products').'</label>
						<div class="col-lg-1"><input type="text" size="1" name="nbr_vis" value="'.(Configuration::get('BC_PRODUCTS_VISIBLE')).'" /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Change product image on hover').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_PRODUCT_HOVER" value="1" '.(($p_hover == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Show Price').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_PRODUCT_PRICE" value="1" '.(($p_price == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Show buttons ("Add to cart", "Wishlist", "Favorites")').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_PRODUCT_BUTTON" value="1" '.(($p_button == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Show product rating').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_PRODUCT_RATING" value="1" '.(($p_rating == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Carousel Autoplay').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_AUTOPLAY" value="1" '.(($bc_autoplay == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">'.$this->l('Countdown').'</label>
						<div class="col-lg-6"><input type="checkbox" name="BC_COUNTDOWN" value="1" '.(($countdown == 1) ? 'checked="checked" ' : '').' /></div>
					</div>
				</div>
				<div class="panel-footer">
				<button type="submit" value="1" id="module_form_submit_btn" name="carouselSettings" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save').'</button>
				</div>
			</div>
      	</form>';
		return $output;
	}

	public function getImages($products, $params) {

			if (!empty($products)) {
				foreach ($products as $key => $product) {
					$imgholder = Image::getImages((int)($params['cookie']->id_lang), $product["id_product"]);				
					foreach ($imgholder as $k => $img) {										
						if ($k < 2) {						
							if ($k == 0)
								$productData[$key]["prodCover"] = Image::getCover($product["id_product"]); // get product cover imag
							else
								$productData[$key]["image"] = $img["id_image"];							
			
							$productData[$key]["data"] = $product;												
							$productData[$key]["grade"] = $this->getGrade($product["id_product"]);
						}
					}				
				}
				return $productData;
			} else
				return false;

	}

	protected function getBestSellers($params, $n)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return false;

		if (!($result = ProductSale::getBestSalesLight((int)($params['cookie']->id_lang), 0, $n)))
			return (Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY') ? array() : false);

		$bestsellers = array();
		$currency = new Currency($params['cookie']->id_currency);
		$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
		foreach ($result as &$row)
			$row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);
		return $result;
	}

	public function getmodulescontent($params) {
		
		$idLang = (int)($params['cookie']->id_lang);
		$category = new Category(Context::getContext()->shop->getCategory(), Configuration::get('PS_LANG_DEFAULT'));
		$nb = (int)(Configuration::get('BC_PRODUCTS_NUMBER'));

		$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
   		$orderWay = Tools::getProductsOrder('way', Tools::getValue('orderway'));

		$new = Product::getNewProducts($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay); /*get new products*/		
		$featured = $category->getProducts($idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay);	 /*		get featured products	*/
		$specials = Product::getPricesDrop($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay);
		$bestsellers = $this->getBestSellers($params, ($nb ? $nb : 10));

		if (Configuration::get('BC_TYPE_ACTIVE') == "new") {$products = $this->getImages($new, $params); $pr_type = "new";}
		if (Configuration::get('BC_TYPE_ACTIVE') == "fea") {$products = $this->getImages($featured, $params); $pr_type = "fea";}
		if (Configuration::get('BC_TYPE_ACTIVE') == "spe") {$products = $this->getImages($specials, $params); $pr_type = "spe";}
		if (Configuration::get('BC_TYPE_ACTIVE') == "bes") {$products = $this->getImages($bestsellers, $params); $pr_type = "bes";}

		$comments["install"] = $this->isInst("productcomments");
		$comments["enable"] = $this->isEn("productcomments");		
		$wishlist["install"] = $this->isInst("blockwishlist");
		$wishlist["enable"] = $this->isEn("blockwishlist");
		$favorite["install"] = $this->isInst("favoriteproducts");
		$favorite["enable"] = $this->isEn("favoriteproducts");	

		$isFav = $isInWishList = array();
		if ($products)
			foreach ($products as $key => $product) {
				$isFav[$product["data"]["id_product"]] = $this->isCustomerFavoriteProduct((int)Context::getContext()->cookie->id_customer, $product["data"]["id_product"]);
				$isInWishList[$product["data"]["id_product"]] = $this->getUserWishlists((int)Context::getContext()->cookie->id_customer, Context::getContext()->shop->id, $product["data"]["id_product"]);
			}	
			
		return $this->smarty->assign(array(
			'products_kit' => $products,
			'pr_type' => $pr_type,							
			'products_to_scroll' => Configuration::get('BC_PRODUCTS_TO_SCROLL'),
			'sc_products_num' => Configuration::get('BC_PRODUCTS_NUMBER'),
			'bc_products_visible' => Configuration::get('BC_PRODUCTS_VISIBLE'),
			'bc_autoplay' => Configuration::get('BC_AUTOPLAY'),
			'active' => Configuration::get('BC_TYPE_ACTIVE'),
			'p_hover' => Configuration::get('BC_PRODUCT_HOVER'),
			'p_price' => Configuration::get('BC_PRODUCT_PRICE'),
			'p_price_wr' => Configuration::get('BC_PRODUCT_PRICE_WR'),
			'p_button' => Configuration::get('BC_PRODUCT_BUTTON'),
			'p_rating' => Configuration::get('BC_PRODUCT_RATING'),
			'countdown' => Configuration::get('BC_COUNTDOWN'),
			'comments' => $comments,
			'wishlist' => $wishlist,
			'favorite' => $favorite,
			'isFav' => $isFav,
			'isInWishList' => $isInWishList
		));
		

	}

	public function hookdisplayHome($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("displayHome");
			if ($status == 1) {	
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookhook_home_01($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookhook_home_02($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookhook_home_03($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_03");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hooknarrow_top($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hooknarrow_middle($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hooknarrow_bottom($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getmodulescontent($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
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
				 $data = Db::getInstance()->ExecuteS('SELECT `id_product` FROM `'._DB_PREFIX_.'wishlist_product` WHERE `id_wishlist` = '.(int)$id);					 
				 foreach ($data as $k => $pid)			 
					$ids[$wishlist_id."-".$k] = $pid["id_product"];					

			}
		}
		if (in_array($prod_id, $ids))
			return true;
		return false;
	}

	public function getGrade($pid) {

		$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');

		if ($this->isInst("productcomments") == "installed") {
			$sql = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS grade
					FROM `'._DB_PREFIX_.'product_comment` pc
					WHERE pc.`id_product` = '.(int)$pid.'
					AND pc.`deleted` = 0'.($validate == '1' ? ' AND pc.`validate` = 1' : ''));

			$num = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT COUNT(pc.`grade`) AS num
					FROM `'._DB_PREFIX_.'product_comment` pc
					WHERE pc.`id_product` = '.(int)$pid.'
					AND pc.`deleted` = 0'.($validate == '1' ? ' AND pc.`validate` = 1' : ''));

			if (empty($sql["grade"])) $sql["grade"] = 0;
			$sql["num"] = $num["num"];
			$sql["comments_module"] = $this->isInst("productcomments");
		} else {
			$sql["num"] = 0;
			$sql["grade"] = 0;
			$sql["comments_module"] = $this->isInst("productcomments");
		}

		return $sql;

	}

	public function hookHeader($params) {		
		if ($this->context->controller->php_self == "index") {
 			$this->context->controller->addCSS($this->_path.'css/productsCarousel.css', 'all');	
 			$this->context->controller->addJS($this->_path.'js/buttons.js');
 			//$this->context->controller->addJS($this->_path.'js/carousel.js');
		}
	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

	public function isInst($name) {	

		if (Module::isInstalled($name))
			return "installed";
		return "not_installed";
	}

	public function isEn($name) {	

		if (Module::isEnabled($name))
			return "enabled";
		return "disabled";
	}

	protected function getCacheId($name = null)
	{
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

}

?>