<?php
/*
*
*  @author Promokit Co. <support@promokit.eu>
*  @copyright  2011-2013 Promokit Co.
*  @version  Release: $Revision: 0 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Promokit Co.
*/

class pk_productscarousel extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'pk_productscarousel';
		$this->tab = 'front_office_features';
		$this->version = '1.4';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Products Carousel with tabs');
		$this->description = $this->l('Displays Products Carousel in Your Homepage');
	}

	function install()
	{
		if (!Configuration::updateValue('CAROUSEL_PRODUCTS_NUMBER', 6) OR 
			!Configuration::updateValue('PRODUCTS_NEW', 1) OR 
			!Configuration::updateValue('PRODUCTS_SPE', 1) OR 
			!Configuration::updateValue('PRODUCTS_BES', 1) OR 
			!Configuration::updateValue('PRODUCTS_FEA', 1) OR 
			!Configuration::updateValue('TYPE_ACTIVE', 'new') OR 
			!Configuration::updateValue('PRODUCT_HOVER', 1) OR 
			!Configuration::updateValue('PRODUCT_PRICE', 1) OR 
			!Configuration::updateValue('PRODUCT_PRICE_WR', 1) OR 
			!Configuration::updateValue('PRODUCT_BUTTON', 1) OR 
			!Configuration::updateValue('PRODUCT_RATING', 1) OR 
			!Configuration::updateValue('PRODUCTS_VISIBLE', 5) OR 
			!Configuration::updateValue('PRODUCTS_TO_SCROLL', 1) OR 
            !Configuration::updateValue('RANDOM', 1) OR
            !Configuration::updateValue('PC_COUNTDOWN', 1) OR
			!parent::install() OR 
			!$this->registerHook('displayHeader') OR
			!$this->registerHook('hook_home_01') OR
            !$this->registerHook('hook_home_02') OR
            !$this->registerHook('hook_home_03') OR
            !$this->registerHook('displayHome') OR
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
		$output = '<div style="width:800px;">';
		if (Tools::isSubmit('submitHomeFeatured'))
		{
			Configuration::updateValue('PRODUCTS_NEW', Tools::getValue('products_new'));
			Configuration::updateValue('PRODUCTS_FEA', Tools::getValue('products_fea'));
			Configuration::updateValue('PRODUCTS_BES', Tools::getValue('products_bes'));
			Configuration::updateValue('PRODUCTS_SPE', Tools::getValue('products_spe'));
			Configuration::updateValue('TYPE_ACTIVE', Tools::getValue('type_active'));
			Configuration::updateValue('PRODUCT_HOVER', Tools::getValue('product_hover'));
			Configuration::updateValue('PRODUCT_PRICE', Tools::getValue('product_price'));
			Configuration::updateValue('PRODUCT_PRICE_WR', Tools::getValue('product_price_wr'));
			Configuration::updateValue('PRODUCT_RATING', Tools::getValue('product_rating'));
			Configuration::updateValue('PRODUCT_BUTTON', Tools::getValue('product_button'));
			Configuration::updateValue('RANDOM', Tools::getValue('random'));
			Configuration::updateValue('PC_COUNTDOWN', Tools::getValue('PC_COUNTDOWN'));
			Configuration::updateValue('PRODUCTS_VISIBLE', Tools::getValue('products_visible'));
			$nbr = intval(Tools::getValue('nbr'));
			$products_to_scroll = intval(Tools::getValue('products_to_scroll'));

			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('Invalid number of products');
			else
				Configuration::updateValue('CAROUSEL_PRODUCTS_NUMBER', $nbr);  /*---------------------*/			
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Settings updated'));

			parent::_clearCache($this->name.'.tpl');
		}
		$output .= "</div>";
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$t_a = Configuration::get('TYPE_ACTIVE');
		$fea = Configuration::get('PRODUCTS_FEA');
		$new = Configuration::get('PRODUCTS_NEW');
		$spe = Configuration::get('PRODUCTS_SPE');
		$bes = Configuration::get('PRODUCTS_BES');
		$p_hover = Configuration::get('PRODUCT_HOVER');
		$p_price = Configuration::get('PRODUCT_PRICE');
		$p_price_wr = Configuration::get('PRODUCT_PRICE_WR');
		$p_button = Configuration::get('PRODUCT_BUTTON');
		$p_rating = Configuration::get('PRODUCT_RATING');
		$random = Configuration::get('RANDOM');
		$pc_countdown = Configuration::get('PC_COUNTDOWN');
		$products_visible = Configuration::get('PRODUCTS_VISIBLE');
		$output = '
		<style type="text/css">
			.label { width:280px; line-height:20px; padding:0 }
			.section { width:430px; overflow:hidden; background:#fff; border:1px solid #ccc; margin-bottom:10px; padding:10px }
			.section span {line-height:20px}
			.section input {margin:3px 15px 0 5px; vertical-align:top}
			.section input[type="text"] {margin-top:0px}
		</style>
	   	<form action="'.$_SERVER['REQUEST_URI'].'" method="post" style="width:800px;">
			<fieldset><legend><img src="'.$this->_path.'logo.png" width="16" height="16" alt="" title="" />'.$this->l('Carousel settings').'</legend>                                
                                <h4>Products type</h4>
				<div class="section">
				<label class="label">'.$this->l('New products').'</label>
						<input type="checkbox" name="products_new" value="1" '.(($new == 1) ? 'checked="checked" ' : '').'/><span>'.$this->l('Show on load').':</span>
						<input type="radio" name="type_active" value="new" '.((($t_a == 'new') && ($new == 1)) ? 'checked="checked" ' : '').' '.(($new != 1) ? 'disabled ' : '').' />
				</div>
				<div class="section">
				<label class="label">'.$this->l('Featured products').'</label><input type="checkbox" name="products_fea" value="1" '.(($fea == 1) ? 'checked="checked" ' : '').'/><span>'.$this->l('Show on load').':</span>
				<input type="radio" name="type_active" value="fea" '.((($t_a == 'fea') && ($fea == 1)) ? 'checked="checked" ' : '').' '.(($fea != 1) ? 'disabled ' : '').'/>
				</div>
				<div class="section">
				<label class="label">'.$this->l('Special products').'</label>
						<input type="checkbox" name="products_spe" value="1" '.(($spe == 1) ? 'checked="checked" ' : '').'/><span>'.$this->l('Show on load').':</span>
						<input type="radio" name="type_active" value="spe" '.((($t_a == 'spe') && ($spe == 1)) ? 'checked="checked" ' : '').' '.(($spe != 1) ? 'disabled ' : '').' />
				</div>
				<div class="section">
				<label class="label">'.$this->l('Bestsellers').'</label>
						<input type="checkbox" name="products_bes" value="1" '.(($bes == 1) ? 'checked="checked" ' : '').'/><span>'.$this->l('Show on load').':</span>
						<input type="radio" name="type_active" value="bes" '.((($t_a == 'bes') && ($bes == 1)) ? 'checked="checked" ' : '').' '.(($bes != 1) ? 'disabled ' : '').' />
				</div>
				<h4>Products display mode</h4>
				<div class="section">
				<label class="label">'.$this->l('Random').'</label>
						<input type="checkbox" name="random" value="1" '.(($random == 1) ? 'checked="checked" ' : '').'/><span>
				</div>
				<h4>Carousel Settings</h4>
				<div class="section">
					<label class="label">'.$this->l('The number of products for one type').'</label>
					<input type="text" size="1" name="nbr" value="'.(Configuration::get('CAROUSEL_PRODUCTS_NUMBER')).'" />	
				</div>			
				<div class="section">
					<label class="label">'.$this->l('The number of visible products').'</label>
					<input type="text" size="1" name="products_visible" value="'.$products_visible.'" />	
				</div>
				<div class="section">
					<label class="label">'.$this->l('Change product image on hover').'</label>
					<input type="checkbox" name="product_hover" value="1" '.(($p_hover == 1) ? 'checked="checked" ' : '').' />
				</div>
				<div class="section">
					<label class="label">'.$this->l('Show Price').'</label>
					<input type="checkbox" name="product_price" value="1" '.(($p_price == 1) ? 'checked="checked" ' : '').' />
				</div>
				<div class="section">
					<label class="label">'.$this->l('Show Price without reduction').'</label>
					<input type="checkbox" name="product_price_wr" value="1" '.((($p_price_wr == 1) && ($p_price == 1)) ? 'checked="checked" ' : '').' '.(($p_price != 1) ? 'disabled ' : '').' />
				</div>
				<div class="section">
					<label class="label">'.$this->l('Show "Add to cart" button').'</label>
					<input type="checkbox" name="product_button" value="1" '.(($p_button == 1) ? 'checked="checked" ' : '').' />
				</div>
				<div class="section">
					<label class="label">'.$this->l('Show product rating').'</label>
					<input type="checkbox" name="product_rating" value="1" '.(($p_rating == 1) ? 'checked="checked" ' : '').' />
				</div>
				<div class="section">
					<label class="label">'.$this->l('Show countdown').'</label>
					<input type="checkbox" name="PC_COUNTDOWN" value="1" '.(($pc_countdown == 1) ? 'checked="checked" ' : '').' />
				</div>
				<br/>
				<input type="submit" name="submitHomeFeatured" value="'.$this->l('Save').'" class="button" />
			</fieldset>
      	</form>';
		return $output;
	}

	public function getImages($products, $params) {

			if (!empty($products)) {
				foreach ($products as $key => $product) {					
					$imgholder = Image::getImages((int)($params['cookie']->id_lang), $product["id_product"]);				
					foreach ($imgholder as $k => $img) {								
						if ($k < 2) {						
							if ($k == 0) {
								$productData[$key]["prodCover"] = Image::getCover($product["id_product"]); // get product cover image
								
							} else {
								$productData[$key]["image"] = $img["id_image"];							
							}				
							$productData[$key]["data"] = $product;												
							$productData[$key]["grade"] = $this->getGrade($product["id_product"]);
						}
					}				
				}
				return $productData;
			} else {
				return false;
			}
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

	public function getProducts($params) {

		$idLang = (int)($params['cookie']->id_lang);
		$category = new Category(Context::getContext()->shop->getCategory(), Configuration::get('PS_LANG_DEFAULT'));
		$nb = (int)(Configuration::get('CAROUSEL_PRODUCTS_NUMBER'));

		$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
   		$orderWay = Tools::getProductsOrder('way', Tools::getValue('orderway'));

		$new = Product::getNewProducts($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay); /*get new products*/

		$random = (int)(Configuration::get('RANDOM'));

        if ($random > 0) /* get random products	*/
            $featured = $category->getProducts($idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay, false, true, true, ($nb ? $nb : 10));	/* get featured products	*/
        else
            $featured = $category->getProducts($idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay);	 /* get featured products	*/

        $specials = Product::getPricesDrop($idLang, 0, (Configuration::get('CAROUSEL_PRODUCTS_NUMBER')), false, $orderBy, $orderWay);
        $bestsellers = $this->getBestSellers($params, ($nb ? $nb : 10));

        $comments["install"] = $this->isInst("productcomments");
		$comments["enable"] = $this->isEn("productcomments");		
		$wishlist["install"] = $this->isInst("blockwishlist");
		$wishlist["enable"] = $this->isEn("blockwishlist");
		$favorite["install"] = $this->isInst("favoriteproducts");
		$favorite["enable"] = $this->isEn("favoriteproducts");					

		if (Configuration::get('PRODUCTS_NEW') == 1) $product_kit["new"] = $this->getImages($new, $params);
		if (Configuration::get('PRODUCTS_FEA') == 1) $product_kit["fea"] = $this->getImages($featured, $params);
		if (Configuration::get('PRODUCTS_SPE') == 1) $product_kit["spe"] = $this->getImages($specials, $params);
		if (Configuration::get('PRODUCTS_BES') == 1) $product_kit["bes"] = $this->getImages($bestsellers, $params);

		foreach ($product_kit as $key => $value)
			if ($key == Configuration::get('TYPE_ACTIVE')) {
				$pr_kit[$key] = $value;
				$pr_type[$key] = $key;
			}

		foreach ($product_kit as $key => $value)
			if ($key != Configuration::get('TYPE_ACTIVE')) {
				$pr_kit[$key] = $value;				
				$pr_type[$key] = $key;
			}

		$isFav = $isInWishList = array();
		foreach ($pr_kit as $key => $products)
			if ($products)
				foreach ($products as $k => $set) {
					$isFav[$set["data"]["id_product"]] = $this->isCustomerFavoriteProduct((int)Context::getContext()->cookie->id_customer, $set["data"]["id_product"]);
					$isInWishList[$set["data"]["id_product"]] = $this->getUserWishlists((int)Context::getContext()->cookie->id_customer, Context::getContext()->shop->id, $set["data"]["id_product"]);
				}

		$this->smarty->assign(array(
			'products_kit' => $pr_kit,
			'products_types' => $pr_type,						
			'visible_products' => Configuration::get('PRODUCTS_VISIBLE'),
			'products_to_scroll' => Configuration::get('PRODUCTS_TO_SCROLL'),
			'active' => Configuration::get('TYPE_ACTIVE'),
			'p_hover' => Configuration::get('PRODUCT_HOVER'),
			'p_price' => Configuration::get('PRODUCT_PRICE'),
			'p_price_wr' => Configuration::get('PRODUCT_PRICE_WR'),
			'p_button' => Configuration::get('PRODUCT_BUTTON'),
			'p_rating' => Configuration::get('PRODUCT_RATING'),
			'pc_countdown' => Configuration::get('PC_COUNTDOWN'),	
			'comments' => $comments,
			'wishlist' => $wishlist,
			'favorite' => $favorite,
			'isFav' => $isFav,
			'isInWishList' => $isInWishList
		));

	}	

	public function hookhook_home_01($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getProducts($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookhook_home_02($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getProducts($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookhook_home_03($params) {
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_03");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))				
					$this->getProducts($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hooknarrow_top($params) {
		if ($this->context->controller->php_self == "index") {	
			$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getProducts($params);
				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}	

	public function hooknarrow_middle($params) {
		if ($this->context->controller->php_self == "index") {	
			$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getProducts($params);

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}	

	public function hooknarrow_bottom($params) {
		if ($this->context->controller->php_self == "index") {	
			$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getProducts($params);

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookdisplayHome($params) {
		if ($this->context->controller->php_self == "index") {	
			$status = $this->getModuleState("displayHome");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getProducts($params);

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
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
			$inWishList = true;
		else
			$inWishList = false;

		return $inWishList;
	}
	
	public function hookDisplayHeader($params)
	{
		if ($this->context->controller->php_self == "index")
			$this->context->controller->addCSS($this->_path.'css/productsCarousel.css', 'all');		
	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

	public function isEn($name) {	

		if (Module::isEnabled($name))
			return "enabled";
		return "disabled";	
	}

	public function isInst($name) {	

		if (Module::isInstalled($name)) 
			return "installed";
		return "not_installed";
	}

	protected function getCacheId($name = null)
	{
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

}
?>