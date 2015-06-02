<?php

if (!defined('_PS_VERSION_'))
	exit;

class pk_isotopesort extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'pk_isotopesort';
		$this->tab = 'front_office_features';
		$this->version = '1.6.1';
		$this->author = 'promokit.eu';
		$this->need_instance = 0;
		$this->DBtable = _DB_PREFIX_.'pk_isotope';
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Isotope Product Filter');
		$this->description = $this->l('Displays featured, new, special products on your homepage.');
	}

	function install()
	{
		if (
			!Configuration::updateValue('ISOTOPE_NBR', 8) || 
			!Configuration::updateValue('ISOTOPE_ADD_METHOD', 1) || 
			!Configuration::updateValue('ISOTOPE_COUNTDOWN', 0) ||
			!Configuration::updateValue('ISOTOPE_MAX', 99) ||
			!Configuration::updateValue('ISOTOPE_FEA', 1) ||
			!Configuration::updateValue('ISOTOPE_SPE', 1) ||
			!Configuration::updateValue('ISOTOPE_BES', 1) ||
			!Configuration::updateValue('ISOTOPE_NEW', 1) ||
			!Configuration::updateValue('ISOTOPE_CAT', 1) ||
			!parent::install() || 
			!$this->registerHook('hook_home_01') ||
			!$this->registerHook('hook_home_02') ||
			!$this->registerHook('hook_home_03') ||
			!$this->registerHook('hook_home_04') ||
			!$this->registerHook('hook_home_05') ||
			!$this->registerHook('hook_home_06') ||
			!$this->registerHook('hook_home_07') ||
			!$this->registerHook('displayHome') ||
			!$this->registerHook('narrow_top') ||
			!$this->registerHook('narrow_middle') ||
			!$this->registerHook('narrow_bottom') ||
			!$this->registerHook('displayHeader'))
			return false;

		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'.$this->DBtable.'`');

		if (!Db::getInstance()->Execute('
				CREATE TABLE `'.$this->DBtable.'` (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
					`data` VARCHAR(100), 
					PRIMARY KEY (`id`)
				) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;'))
				return false;
		if (!Db::getInstance()->Execute('
				INSERT INTO `'.$this->DBtable.'` (
					`id`, `data`
				) VALUES (1,1),(2,2);'))
				return false;
		return true;
	}

	public function getContent()
	{
		$output = "";
		if (Tools::isSubmit('IsotopeSettins'))
		{
			$nbr = (int)(Tools::getValue('nbr'));
			Configuration::updateValue('ISOTOPE_ADD_METHOD', (int)(Tools::getValue('admethod')));
			Configuration::updateValue('ISOTOPE_MAX', (int)(Tools::getValue('isotope_max')));
			Configuration::updateValue('ISOTOPE_COUNTDOWN', (bool)(Tools::getValue('countdown')));
			Configuration::updateValue('ISOTOPE_FEA', (int)(Tools::getValue('isotope_fea')));
			Configuration::updateValue('ISOTOPE_SPE', (int)(Tools::getValue('isotope_spe')));
			Configuration::updateValue('ISOTOPE_BES', (int)(Tools::getValue('isotope_bes')));
			Configuration::updateValue('ISOTOPE_NEW', (int)(Tools::getValue('isotope_new')));
			Configuration::updateValue('ISOTOPE_CAT', Tools::getValue('isotope_cat'));
		
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('An invalid number of products has been specified.');
			else
				Configuration::updateValue('ISOTOPE_NBR', (int)($nbr));
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Your settings have been updated.'));

			parent::_clearCache($this->name.'.tpl');
			
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$meth = (int)(Configuration::get('ISOTOPE_ADD_METHOD'));
		$countdown = (int)(Configuration::get('ISOTOPE_COUNTDOWN'));
		$max = (int)(Configuration::get('ISOTOPE_MAX'));
		$spe = (int)(Configuration::get('ISOTOPE_SPE'));
		$fea = (int)(Configuration::get('ISOTOPE_FEA'));
		$bes = (int)(Configuration::get('ISOTOPE_BES'));
		$new = (int)(Configuration::get('ISOTOPE_NEW'));
		$cat = Configuration::get('ISOTOPE_CAT');

		$cats = explode(",", $cat);
		$cat = implode(",", array_unique($cats));

		$id_lang = Context::getContext()->language->id;

		$categories = Category::getCategories(intval($id_lang), false);		
		$sql = 'SELECT data FROM `'.$this->DBtable.'`';
		$data = Db::getInstance()->executeS($sql);		

		$htmlPrd = '';

		foreach ($data as $key => $item)
			$htmlPrd .= $this->htmlCodeProducts($item["data"], "");		

		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" id="module_form" class="defaultForm form-horizontal">
		<div class="panel" id="fieldset_0">												
			<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Isotope Settings').'</div>
			<div class="form-wrapper">
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Display countdown').'</label>
					<div class="col-lg-6"><input type="checkbox" name="countdown" id="countdown" value="1" '.(($countdown == true) ? 'checked ' : '').'/></div>
					<label class="control-label col-lg-3" style="clear:left"></label>
					<div class="col-lg-8"><p class="help-block">'.$this->l('This feature display countdown timer for products with special price, if special price is limited by time.').'</p></div>
				</div>
				<div class="form-group'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Featured Products').'</label>
					<div class="col-lg-6"><input type="checkbox" name="isotope_fea" id="isotope_fea" value="1" '.(($fea == 1) ? 'checked ' : '').'/></div>
				</div>
				<div class="form-group'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Special Products').'</label>
					<div class="col-lg-6"><input type="checkbox" name="isotope_spe" id="isotope_spe" value="1" '.(($spe == 1) ? 'checked ' : '').'/></div>
				</div>
				<div class="form-group'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Bestsellers Products').'</label>
					<div class="col-lg-6"><input type="checkbox" name="isotope_bes" id="isotope_bes" value="1" '.(($bes == 1) ? 'checked ' : '').'/></div>
				</div>
				<div class="form-group'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('New Products').'</label>
					<div class="col-lg-6"><input type="checkbox" name="isotope_new" id="isotope_new" value="1" '.(($new == 1) ? 'checked ' : '').'/></div>
				</div>
				<div class="form-group catlist'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Categories:').'</label>
					<div class="col-lg-3">
						'.$this->displayCategoriesSelect($categories, 0).'						
					</div>
					<div class="col-lg-3">
						<div class="tabcategories"><ul>'.$this->getCatList($cat, true).'</ul></div>
						<input type="hidden" name="isotope_cat" class="isotope_cat" value="'.$cat.'">
					</div>
				</div>
				<hr/>
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Automatically get products').'</label>
					<div class="col-lg-6">
						<input type="radio" name="admethod" id="auto_admethod" value="1" '.(($meth == 1) ? 'checked ' : '').'/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">'.$this->l('Manually get products').'</label>
					<div class="col-lg-6">
						<input type="radio" name="admethod" id="manual_admethod" value="0" '.(($meth == 0) ? 'checked ' : '').'/>
					</div>
				</div>
				<div class="form-group automatical_method'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Define the number of products').'</label>
					<div class="col-lg-1">
						<input type="text" size="5" name="nbr" value="'.Tools::safeOutput(Tools::getValue('nbr', (int)(Configuration::get('ISOTOPE_NBR')))).'" />
					</div>
					<label class="control-label col-lg-3" style="clear: left"></label>
					<div class="col-lg-8"><p class="help-block">'.$this->l('Define the number of each type/category of products that you would like to display on your homepage. Total you will have defined number multiplied to the number of selected types and categories.' ).'</p>
					</div>
				</div>
				<div class="form-group'.(($meth == 0) ? ' hide ' : '').'">
					<label class="control-label col-lg-3">'.$this->l('Define the max number of products').'</label>
					<div class="col-lg-1">
						<input type="text" size="5" name="isotope_max" value="'.Tools::safeOutput($max, (int)(Configuration::get('ISOTOPE_MAX'))).'" />
					</div>
					<label class="control-label col-lg-3" style="clear: left"></label>
					<div class="col-lg-8"><p class="help-block">'.$this->l('Sometimes total number of products can be more than you want. With this option you can set maximum total number of products.' ).'</p>
					</div>
				</div>
				<div class="form-group">
					<div class="manually_method'.(($meth == 1) ? ' hide ' : '').'">
						<div class="categoriesArea col-lg-3">
						<label>' . $this->l('Categories') . '</label>
						'.$this->displayCategoriesSelect($categories, 0).'						
						</div>
						<div id="allprdcts" class="col-lg-4">
							<label>' . $this->l('All Products from category') . '</label>
							<div class="contain"></div>
						</div>
						<div id="selectedprdcts" class="col-lg-5">
							<label>' . $this->l('Selected Products') . '</label>
							<div class="contain">'.$htmlPrd.'</div>
						</div>					
						<script>
						$(document).ready(function() {  
						    $(".manually_method .categList").click(function() {    	
								var cID = $(".manually_method .categList").val();
								$.ajax({
								    type: "POST",
								    url: "'._MODULE_DIR_.$this->name.'/ajax.php?cID="+cID,
								    success: function(result){
								      if (result == "0") {
								        console.log("no data")
								      } else {
										$("#allprdcts .contain").html(result);
								      }
								    }
								});				
						    });
							$(".catlist .categList").click(function() {    	
								var catID = $(".catlist .categList").val();
								$.ajax({
								    type: "POST",
								    url: "'._MODULE_DIR_.$this->name.'/ajax.php?catID="+catID,
								    success: function(result){
								      if (result == "0") {
								        console.log("no data")
								      } else {
										$(".tabcategories ul").append(result);
										var current = "";
								      	$(".tabcategories li").each(function(){
								      		current = current+($(this).data("id"))+",";
								      	});
										$(".isotope_cat").attr("value", current);
								      }
								    }
								});				
						    });
							$(".catlist .tabcategories li").click(function() {    	
								var catID = $(this).data("id");
								var current = $(".isotope_cat").val().replace(catID+",", "");
								$(".isotope_cat").attr("value", current);
								$(".catlist .tabcategories").find("[data-id="+catID+"]").remove();
						    });
							$("#allprdcts").on("click", ".prodSection", function () {
								var pID = $(this).data("pid");								
								var res = "";
								$.ajax({
								    type: "POST",
								    url: "'._MODULE_DIR_.$this->name.'/ajax.php?pID="+pID,
								    success: function(result){
								      if (result == "0") {
								        res = result;
								        console.log("no data");
								      } else {
								      	res = result;
								      }
								    }
								});
								$(this).clone().appendTo("#selectedprdcts .contain");
						    });
							$("#selectedprdcts").on("click", ".prodSection", function () {
								var pID = $(this).data("pid");								
								var res = "";
								$.ajax({
								    type: "POST",
								    url: "'._MODULE_DIR_.$this->name.'/ajax.php?rem_pID="+pID,
								    success: function(result){
								      if (result == "0") {
								        res = result;
								      } else {
								      	res = result;
								      }
								    }
								});
								$(this).remove();
							});
						});
						</script>
					</div>
				</div>
			</div>
			<div class="panel-footer">
			<button type="submit" value="1" id="module_form_submit_btn" name="IsotopeSettins" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save').'</button>
			</div>
		</div>
		</form>';
		$this->context->controller->addCSS(($this->_path).'css/bo_isotopeFilter.css', 'all');
		return $output;
	}

	protected function getBestSellers($params, $nb)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return false;

		if (!($result = ProductSale::getBestSalesLight((int)$params['cookie']->id_lang, 0, $nb)))
			return (Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY') ? array() : false);

		$currency = new Currency($params['cookie']->id_currency);
		$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
		foreach ($result as &$row)
			$row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);

		return $result;
	}

	public function ajaxCall() {

		$nb = (int)(Configuration::get('ISOTOPE_NBR'));
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		$products["featured"] = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 8));
		$products["new"]=Product::getNewProducts((int)Context::getContext()->language->id, 0, ($nb ? $nb : 8));

		if (!empty($products)) {
			foreach($products as $product) {
				if (!empty($product)) {
					foreach($product as $data) {
						$pr_html="";
						$imageData = Image::getCover($data['id_product']);
						$imgLink = $this->context->link->getImageLink($data['link_rewrite'], (int)$data['product_id'].'-'.(int)$imageData['id_image'], 'home_default');
						
						$pr_html.="<li class=\"ajax_block_product new_products isotope-hidden isotope-item\" >";
						$pr_html.="<a href=\"".$data['link']."\" title=\"".$data['name']."\" class=\"product_image\">";
							$pr_html.="<img src=\"".$imgLink."\" alt=\"".$data['name']."\"></a>";
						$pr_html.="<h5 class=\"s_title_block\">";
							$pr_html.="<a href=\"".$data['link']."\" title=\"".$data['name']."\">".$data['name']."</a>";
						$pr_html.="</h5> <div class=\"product_desc\">";
							$pr_html.=$data['description_short'];
						$pr_html.="</div><div>";
							$pr_html.="<p class=\"price_container\"><span class=\"price\">".Product::getPriceStatic((int)$data['id_product'], true, NULL)."</span></p>";
									$pr_html.="<a class=\"exclusive ajax_add_to_cart_button\" rel=\"ajax_id_product_1\" href=\"".$data['link']."\" title=\"Add to cart\">Add to cart</a></div></li>";
						print_r($pr_html);
																		
					}
				}
			}
		}
	

	}

	public function hookDisplayHeader($params)
	{
		if ($this->context->controller->php_self == "index") {			
			$this->context->controller->addCSS(($this->_path).'css/'.$this->name.'.css', 'all');
			$this->context->controller->addJS($this->_path.'js/jquery.isotope.min.js');
			$this->context->controller->addJS($this->_path.'js/scripts.js');
		}

	}
	public function isNew($id) {
		$result = Db::getInstance()->executeS('
			SELECT p.id_product
			FROM `'._DB_PREFIX_.'product` p
			'.Shop::addSqlAssociation('product', 'p').'
			WHERE p.id_product = '.(int)$id.'
			AND DATEDIFF(
				product_shop.`date_add`,
				DATE_SUB(
					NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
				)
			) > 0
		');
		if (count($result) > 0)
			return "new_product";
		return "";

	}

	public function getData($params) {

		$rootCategory = Category::getRootCategory();
		$idLang = (int)Context::getContext()->language->id;
		$categoriesArray = array('0' => array('id_category' => $rootCategory->id_category));
		$bestsellersList = array();
		$nb = (int)(Configuration::get('ISOTOPE_NBR'));
		$max = (int)(Configuration::get('ISOTOPE_MAX'));
		$meth = Configuration::get('ISOTOPE_ADD_METHOD');
		$fea_counter = $new_counter = $spe_counter = $bes_counter = 1;	
		$categories = "";

		$spe = (int)(Configuration::get('ISOTOPE_SPE'));
		$fea = (int)(Configuration::get('ISOTOPE_FEA'));
		$bes = (int)(Configuration::get('ISOTOPE_BES'));
		$new = (int)(Configuration::get('ISOTOPE_NEW'));
		$cat = Configuration::get('ISOTOPE_CAT');

		if ($meth == 0) {// manual

			$sql = 'SELECT data FROM `'.$this->DBtable.'`';			
			$data = Db::getInstance()->executeS($sql);		
			foreach ($data as $k => $value)
				$listID[$k] = $value["data"];

			$uniqueListID = array_unique($listID);

			$link = new Link();

			$currency = new Currency($params['cookie']->id_currency);
			$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
			
			foreach ($uniqueListID as $k => $productID) {

				$product = new Product($productID, true, $this->context->language->id);
				$prdcts["unsorted"][$k] = get_object_vars($product);
				$prdcts["unsorted"][$k]["id_product"] = $productID;
				$cover = Image::getCover($productID);
				$img = (int)$cover["id_image"];				
				$prdcts["unsorted"][$k]["id_image"] = $img;
				$prdcts["unsorted"][$k]["link"] = $link->getProductLink($product);
			
			}
			$products["unsorted"] = Product::getProductsProperties((int)$idLang, $prdcts["unsorted"]);
			if (!empty($products["unsorted"])) {
				foreach ($products["unsorted"] as $key => $value) {
					if ($value["price"] == 0) {
						$product = new Product($value["id_product"], true, $this->context->language->id);
						$products["unsorted"][$key]["price"] = $product->getPublicPrice();
						$products["unsorted"][$key]["price_without_reduction"] = $product->getPriceStatic($value["id_product"], true, null, 6, null, false, false);
					}
					// get all bestsellers ID	
					$bestsellersList[] = $value["id_product"];
				}

			} else
				$bestsellersList[] = "";
			
		} else { // automatically

			$category = new Category(Context::getContext()->shop->getCategory(), $idLang);

			$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
			$orderWay = Tools::getProductsOrder('way', Tools::getValue('orderway'));

			$home_id = $category->getRootCategory();
			$categories_id = explode(",", $cat);
			$pids = array();
			$pids_str = null;

			foreach ($categories_id as $key => $id) 
				if (!empty($id)) {

					if (!empty($pids)) // get numbers of existing products
						foreach ($pids as $k => $v)
							$pids_str .= implode(",",$v);

					//$prdcts = $this->categoryProducts($idLang, 0, ($nb ? $nb : 10), $orderBy, $orderWay, ($pids_str ? $pids_str : 0), $id, true); // get category products with skip list
					$prdcts = Product::getProducts($idLang, 0, ($nb ? $nb : 10), $orderBy, $orderWay, $id, true);
					
					if ($prdcts)
						foreach ($prdcts as $k => $prdct) {
							$cover = Image::getCover($prdct["id_product"]);
							$prdct["id_image"] = (int)$cover["id_image"];
							$products[$k][0] = Product::getProductProperties((int)$idLang, $prdct);
							$pids[$id][$prdct["id_product"]] = $prdct["id_product"];
						}

					$sql = 'SELECT link_rewrite, name FROM `'._DB_PREFIX_.'category_lang` WHERE id_category='.$id.' AND id_shop='.Context::getContext()->shop->id.' AND id_lang='.Context::getContext()->language->id;		
					$catInfo = Db::getInstance()->executeS($sql);	
					$categories[$id] = $catInfo[0];

					// get number of all products in current category
					//$total_products[$id] = $category->getProducts($id, $idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay, 0, true);

				}
				
			if ($fea) {

				//if (!empty($pids))
				//	foreach ($pids as $k => $v)
				//		$pids_str .= implode(",",$v);
				
				//$products["featured"] = $this->getProducts($home_id->id_category, $idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay, ($pids_str ? $pids_str : 0));
				$products["featured"] = $category->getProducts($idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay);			

				// get number of all products featured products
				//$total_products["featured"] = $this->getProducts($home_id->id_category, $idLang, 1, ($nb ? $nb : 10), $orderBy, $orderWay, 0, true);

				if (!empty($products["featured"]))
					foreach ($products["featured"] as $key => $value)
						$pids["featured"][$value["id_product"]] = $value["id_product"];
				
			}
			
			if ($new) {

				//if (!empty($pids))
				//	foreach ($pids as $k => $v)
				//		$pids_str .= implode(",",$v);

				//$products["new"] = $this->getNewProducts($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay, ($pids_str ? $pids_str : 0));
				$products["new"] = Product::getNewProducts($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay);

				// get number of all products new products
				//$total_products["new"] = $this->getNewProducts($idLang, 0, ($nb ? $nb : 10), true);

				if (!empty($products["new"]))
					foreach ($products["new"] as $key => $value)
						$pids["new"][$value["id_product"]] = $value["id_product"];

			}
			
			if ($spe) {

				//if (!empty($pids))
				//	foreach ($pids as $k => $v)
				//		$pids_str .= implode(",",$v);

				//$products["special"] = $this->getPricesDrop($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay, ($pids_str ? $pids_str : 0));

				// get number of all products new products
				//$total_products["special"] = $this->getPricesDrop($idLang, 0, ($nb ? $nb : 10), true);
				$products["special"] = Product::getPricesDrop($idLang, 0, ($nb ? $nb : 10), false, $orderBy, $orderWay);

				if (!empty($products["special"]))
					foreach ($products["special"] as $key => $value)
						$pids["special"][$value["id_product"]] = $value["id_product"];

			}

			if ($bes) {
				$products["bestsellers"] = $this->getBestSellers($params, ($nb ? $nb : 10));
				if (!empty($products["bestsellers"]))
					foreach ($products["bestsellers"] as $key => $value) {
						$bestsellersList[] = $value["id_product"];// get all bestsellers ID		
						$pids["bestsellers"][$value["id_product"]] = $value["id_product"];
					} else
						$bestsellersList[] = "";
			}


		}	
		
		if (!empty($products)) {
			foreach ($products as $key=>$product)
				if (!empty($product))
					foreach ($product as $k => $val)
						$p[$val["id_product"]] = $val;
		} else 
			$p[0] = "";

		// sort products by types
		if (!empty($p))	{	
			foreach ($p as $k=>$data) {
				if (!empty($data)) {
					$type[$data["id_product"]] = "";					
					if ($fea_counter <= $nb) {
						if (Product::idIsOnCategoryId($data["id_product"], $categoriesArray)) {						
							$type[$data["id_product"]] .= " featured_product";
							$fea_counter++;
						} else
							$type[$data["id_product"]] .= "";

					}
					if ($new_counter <= $nb) {
						if ($this->isNew($data["id_product"])) {
							$type[$data["id_product"]] .= " new_product"; 
							$new_counter++;
						} else
							$type[$data["id_product"]] .= "";

					}
					if ($spe_counter <= $nb) {
						if (!empty($data["specific_prices"])) {
							$type[$data["id_product"]] .= " special_product"; 
							$spe_counter++;
						} else
							$type[$data["id_product"]] .= "";				

					}
					if ($bes_counter <= $nb) {
						if (in_array($data["id_product"], $bestsellersList)) { 
							$type[$data["id_product"]] .= " bestsellers";
							$bes_counter++;
						} else
							$type[$data["id_product"]] .= "";	

					}
					if ($meth == 1) {// auto
						foreach ($categories_id as $key => $cat)
							$cat_counter[$cat] = 0;

						foreach ($categories_id as $key => $cat) {
							if ($cat_counter[$cat] <= $nb) {
								$allProductCats = Product::getProductCategories($data["id_product"]);
								if (in_array($cat, $allProductCats)) { 
									$type[$data["id_product"]] .= " ".$categories[$cat]["link_rewrite"];
									$cat_counter[$cat]++;
								}
							}
						}
					}
					$productReadyList[$data["id_product"]] = $data;
				} else
					$productReadyList[$k] = "";
			} 
			shuffle($productReadyList);		
		} else
			$productReadyList = $type = "";

		$isFav = $isInWishList = array();
		if ($productReadyList)
			foreach ($productReadyList as $key => $product) {
				if ($product) {
					$isFav[$product["id_product"]] = $this->isCustomerFavoriteProduct((int)Context::getContext()->cookie->id_customer, $product["id_product"]);
					$isInWishList[$product["id_product"]] = $this->getUserWishlists((int)Context::getContext()->cookie->id_customer, Context::getContext()->shop->id, $product["id_product"]);
				}
			}
		
		$comments["install"] = $this->isInst("productcomments");
		$comments["enable"] = $this->isEn("productcomments");
		$wishlist["install"] = $this->isInst("blockwishlist");
		$wishlist["enable"] = $this->isEn("blockwishlist");
		$favorite["install"] = $this->isInst("favoriteproducts");
		$favorite["enable"] = $this->isEn("favoriteproducts");
		
		$this->smarty->assign(array(
			'products' => $productReadyList,
			'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
			'countdown' => (bool)(Configuration::get('ISOTOPE_COUNTDOWN')),
			'type' => $type,
			'moduleName' => $this->name,
			'comments' => $comments,
			'wishlist' => $wishlist,
			'favorite' => $favorite,
			'isFav' => $isFav,
			'isInWishList' => $isInWishList,
			'spe' => $spe,
			'fea' => $fea,
			'new' => $new,
			'bes' => $bes,
			'categories' => $categories,
			'isotope_max' => $max
		));

	}

	public function hookhook_home_01($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_01");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
				//	return $this->display(__FILE__, $this->name.'.tpl');
			}
		}
	}

	public function hookhook_home_02($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_02");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hookhook_home_03($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_03");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hookhook_home_04($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_04");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hookhook_home_05($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_05");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hookhook_home_06($params) {	
		
		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_06");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hookhook_home_07($params) {	

		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("hook_home_07");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	public function hookdisplayHome($params) {	

		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("displayHome");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hooknarrow_top($params) {	

		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_top");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name))) {
					$this->getData($params);	
					echo "false";
				}

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hooknarrow_middle($params) {	

		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_middle");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}
	public function hooknarrow_bottom($params) {	

		if ($this->context->controller->php_self == "index") {
	 		$status = $this->getModuleState("narrow_bottom");
			if ($status == 1) {
				if (!$this->isCached($this->name.'.tpl', $this->getCacheId($this->name)))
					$this->getData($params);	

				return $this->display(__FILE__, $this->name.'.tpl', $this->getCacheId($this->name));
			}
		}
	}

	private function recurseCategory($categories, $current, $id_category = 1, $id_selected = 1) {

		global $currentIndex;

		$this->_html .= '<option value="' . $id_category . '"' . (($id_selected == $id_category) ? ' selected="selected"' : '') . '>' . str_repeat('&nbsp;', $current ['infos'] ['level_depth'] * 5) . (_PS_VERSION_ < 1.4 ? self::hideCategoryPosition(stripslashes($current ['infos'] ['name'])) : stripslashes($current ['infos'] ['name'])) . '</option>';
		if (isset($categories [$id_category]))
			foreach ( $categories [$id_category] as $key => $row )
				$this->recurseCategory($categories, $categories [$id_category] [$key], $key, $id_selected);
	}
	private function displayCategoriesSelect($categories, $selected) {
		$this->_html = '';
		$this->_html .= '<select multiple name="id_category" class="categList">';
						$this->recurseCategory($categories, $categories [0] [1], 1, $selected);
		$this->_html .= '</select>';
		return $this->_html;
	}

	private function htmlCodeCategories($prod_id) {

		$orderBy = Tools::getProductsOrder('by', Tools::getValue('orderby'));
		$langID = (int)Context::getContext()->language->id;
		$html = '';

		$products = Product::getProducts($langID, 0, 0, $orderBy, "ASC", $prod_id, true);

		foreach ($products as $key => $data)
			$html .= $this->htmlCodeProducts($data["id_product"], "");

		return $html;
		
	}
	private function htmlCodeProducts($prod_id, $front) {

		$cover = Image::getCover($prod_id);		
		$img = $prod_id.'-'.(int)$cover["id_image"];
		$productName = Product::getProductName($prod_id);

		$sql = 'SELECT link_rewrite, description_short FROM `'._DB_PREFIX_.'product_lang` WHERE id_product='.$prod_id.' AND id_shop='.Context::getContext()->shop->id.' AND id_lang='.Context::getContext()->language->id;		

		$prodData = Db::getInstance()->executeS($sql);		
		
		$html = '';
		if ($front == "front") {			
		} else {			
			$html = "<div data-pid=\"".$prod_id."\" class=\"prodSection\" title=\"".$productName."\"><img src=\"".$this->context->link->getImageLink($prodData[0]["link_rewrite"], $img, 'small_default')."\" alt=\"\" /><span>".substr($productName, 0, 8)."...</span></div>";			
		}

		return $html;

	}
	public function saveData($pID) {
			
		Db::getInstance()->Execute('INSERT INTO `'.$this->DBtable.'` (`data`) VALUES ('.$pID.');');

	}

	public function removeData($rem_pID) {

		Db::getInstance()->Execute('DELETE FROM `'.$this->DBtable.'` WHERE data = '.$rem_pID);	

	}

	public function getProductsFromCategory($cID) {

		$ids = explode(",", $cID);	
		$html = '';

		foreach ($ids as $id)
			$html .= $this->htmlCodeCategories($id);

		print_r($html);
	}

	public function getCatList($cids, $raw = false) {

		$ids = explode(",", $cids);

		$html = "";
		$cats = Category::getCategoryInformations($ids);
		foreach ($cats as $key => $cat)
			$html .= "<li data-id='".$cat['id_category']."'>".$cat['name']."</li>";

		if ($raw == false)
			print_r($html);
		else
			return $html;

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

		$inWishList = false;
		$wishlist_ids = Db::getInstance()->ExecuteS('SELECT `id_wishlist` FROM `'._DB_PREFIX_.'wishlist` WHERE `id_customer` = '.$id_customer);
		$ids = array();
		foreach ($wishlist_ids as $wishlist_id => $wishlist) {
			foreach ($wishlist as $key => $id) {
				 $data = Db::getInstance()->ExecuteS('SELECT `id_product` FROM `'._DB_PREFIX_.'wishlist_product` 			WHERE `id_wishlist` = '.(int)$id);					 
				 foreach ($data as $k => $pid)				 
					$ids[$wishlist_id."-".$k] = $pid["id_product"];					

			}
		}
		if (in_array($prod_id, $ids))
			$inWishList = true;			

		return $inWishList;
	}

	protected function getCacheId($name = null) {
		return parent::getCacheId($name.'|'.date('Ymd'));
	}

}
