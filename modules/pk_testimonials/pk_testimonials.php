<?php

class pk_testimonials extends Module
{
	private $_html;

	public function __construct()
	{
		$this->name = 'pk_testimonials';
		$this->tab = 'front_office_features';
		$this->version = '1.6';
		$this->author = 'promokit.eu';
		$this->bootstrap = true;

		parent::__construct();
		$this->displayName = $this->l('Customer Testimonials');
		$this->description = $this->l('Creates a customer testimonials page');
		$this->confirmUninstall = $this->l('Uninstalling will delete the actual testimonials as well as the module. You can use the backup function to save them first (Found under the >>Configure option) Are you sure?');
	}


	public function install() {

 	 	if (parent::install() == false 
            //OR $this->registerHook('leftColumn') == false
			OR $this->registerHook('displayHeader') == false
			OR $this->registerHook('displayHome') == false
			OR $this->registerHook('hook_home_01') == false
           	OR $this->registerHook('hook_home_02') == false
           	OR $this->registerHook('hook_home_03') == false
			OR $this->registerHook('wide_top') == false
			OR $this->registerHook('wide_middle') == false			
			OR $this->registerHook('wide_bottom') == false
			OR $this->registerHook('displayBackOfficeHeader') == false
            OR !Configuration::updateValue('TESTIMONIAL_CAPTCHA', '0')
			OR !Configuration::updateValue('TESTIMONIAL_PERPAGE', '10')
			OR !Configuration::updateValue('TESTIMONIAL_PERBLOCK', '2')
            OR !Configuration::updateValue('TESTIMONIAL_CAPTCHA_PUB', '12345')
            OR !Configuration::updateValue('TESTIMONIAL_CAPTCHA_PRIV', '678910')
            OR !Configuration::updateValue('TESTIMONIAL_DISPLAY_IMG', '1')
            OR !Configuration::updateValue('TESTIMONIAL_MAX_IMG', '80')
        	)
 	 		return false;
 	 	
 	 		if (!Db::getInstance()->Execute('
			CREATE TABLE '._DB_PREFIX_.'testimonials (
				`testimonial_id` int(5) NOT NULL AUTO_INCREMENT,
				`testimonial_title` varchar(64) NOT NULL DEFAULT \'My Testimonial\',
				`testimonial_submitter_name` varchar(50) NOT NULL DEFAULT \'anonymous\',
				`testimonial_submitter_email` varchar(50) NOT NULL DEFAULT \'anonymous@mail.com\',
				`testimonial_main_message` text NOT NULL,
				`date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			     `status` char(8) NOT NULL DEFAULT \'Disabled\',
				PRIMARY KEY(`testimonial_id`)
			) ENGINE=MyISAM default CHARSET=utf8'))
 	 			return false;

 	 		if (!Db::getInstance()->Execute("
 	 		INSERT INTO `"._DB_PREFIX_."testimonials` VALUES (1, 'Summary', 'Marek', 'marek@mail.com', 'Ipsum dolor sit amet, consectetur adipiscing elit. Nulla interdum tincidunt felis, id mattis nisi mattis in. Etiam vehicula sem et augue mattis congue. Vivamus consequat congue purus, non imperdiet nulla rhoncus eu. Donec vehicula lor', '2014-05-15 15:49:24', 'Enabled');
			INSERT INTO `"._DB_PREFIX_."testimonials` VALUES (2, 'summary', 'Fred', 'fred@email.com', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla interdum tincidunt felis, id mattis nisi mattis in. Etiam vehicula sem et augue mattis congue. Vivamus consequat congue purus, non imperdiet nulla rhoncus eu. Donec vehicula lor', '2014-05-15 15:48:45', 'Enabled');"))
 	 			return false;
 	 		
 	 		return true;
  	}

	public function uninstall() {

        if (!parent::uninstall()
        OR !Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'testimonials`;'))
        return false;

        Configuration::deleteByName('TESTIMONIAL_CAPTCHA');
		Configuration::deleteByName('TESTIMONIAL_PERPAGE');
		Configuration::deleteByName('TESTIMONIAL_PERBLOCK');
        Configuration::deleteByName('TESTIMONIAL_CAPTCHA_PUB');
        Configuration::deleteByName('TESTIMONIAL_CAPTCHA_PRIV');
        Configuration::deleteByName('TESTIMONIAL_DISPLAY_IMG');
        Configuration::deleteByName('TESTIMONIAL_MAX_IMG');

        return true;
	}

	    /**
		 Function for cleaning text input fields
		**/

    function cleanInput ($text) {  //clean the inputs
			$text = trim($text);
			$text = strip_tags($text);
			$text = htmlspecialchars($text, ENT_QUOTES);

		return ($text); //output clean text
	}
		
		
        /**
		 Function for validating text input fields
		**/

		function field_validator($field_descr, $field_data, $min_length="", $max_length="", $field_required=1) {
				$errors = array();
				if(!$field_data && !$field_required){ return; }

				# check for required fields
				if ($field_required && empty($field_data)) {
				return false;
     			}

				# field data min length checking:
				if ($min_length) {
					if (strlen($field_data) < $min_length) {
						return false;
					}
				}

				 # field data max length checking:
				if ($max_length) {
					if (strlen($field_data) > $max_length) {
						return false;
					}
               }

	   else
	   
		echo "ok";
		return true;
	
	}
	
	/** Function for check file ext **/
	public function checkImageExt() {  
         $allowedextlist = array('jpg', 'png', 'jpeg');
	     $notallowedextlist = array('php', 'php3', 'php4', 'phtml','exe');
             $fileName = strtolower($_FILES['testimonial_img']['name']); //check the correct extension
             if(!in_array(end(explode('.', $fileName)), $allowedextlist))	
				 {
				 echo "false";
                                 return false;
				 }
		
	        return true;
              
		}		
	
	/** Function for uploading file **/
	
	public function uploadImage(){
	
		      $uploadpath = "upload";
				
	               //upload the files
			move_uploaded_file($_FILES["testimonial_img"]["tmp_name"],
			_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$uploadpath.DIRECTORY_SEPARATOR.$_FILES["testimonial_img"]["name"]);

                        //store the path for displaying the image
			$testimonial_img = $uploadpath ."/".$_FILES["testimonial_img"]["name"];
                        $testimonial_img = addslashes($testimonial_img);

                       
                        return $testimonial_img; //return image path 
			 
	}


    /** Function for checking file size **/

    public function checkfileSize() {
       $MAX_SIZE = (Configuration::get('TESTIMONIAL_MAX_IMG') * 1024);
       
       if ( $_FILES["testimonial_img"]["size"] > $MAX_SIZE )
       {
           return false;
       }
       else return true;
    }

	/** Function for writing testimonials **/
	
    public function writeTestimonial($testimonial_title,$testimonial_submitter_name,$testimonial_submitter_email,$testimonial_main_message) {
         $db = Db::getInstance();
		$result = $db->Execute('
		INSERT INTO `'._DB_PREFIX_.'testimonials`
		( `testimonial_title`, `testimonial_submitter_name`, `testimonial_submitter_email`, `testimonial_main_message`)
		VALUES
		("'.$testimonial_title.'"
		,"'.$testimonial_submitter_name.'"
		,"'.$testimonial_submitter_email.'"
		,"'.$testimonial_main_message.'"
                )');
		return;
    }


	public function displayTestimonials()
	{
		$output = array(); // create an array named $output to store our testimonials. We will read the from the DB
		$db = Db::getInstance(); // create and object to represent the database
		$result = $db->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'testimonials`;'); // Query to count the total number of testimonials		
		if ($result == true) {
			$numrows = 0;
			foreach ($result as $key => $row) {
		 		$numrows++;
		 	}		
		} else {
			$numrows = 1;
		}
		
        $nextpage = "";
		$prevpage = "";
		// number of rows to show per page
		$rowsperpage = Configuration::get('TESTIMONIAL_PERPAGE');

		// find out total pages
		$totalpages = ceil($numrows / $rowsperpage);
		// get the current page or set a default
		if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
		   // cast var as int
		   $currentpage = (int) $_GET['currentpage'];
		} else {
		   // default page num
		   $currentpage = 1;
		} // end if

		// the offset of the list, based on current page 
		$offset = ($currentpage - 1) * $rowsperpage;
		
	   	// get the info from the db 
		$result = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'testimonials` WHERE status = "enabled" ORDER BY testimonial_id DESC LIMIT '.$offset.', '.$rowsperpage.';'); // Query to return the testimonials on that page
		// while there are rows to be fetched...
		foreach ($result as $key => $data) {
     		$time = $result[$key]["date_added"];
     		$t = explode(" ", $time);
     		$result[$key]["date_added"] = date("d F Y", strtotime($t[0]));     		
     	} 
		if ($result != false) {
			foreach ($result as $key => $row) {
				$results[] = $row;
				$time = $result[$key]["date_added"];
	     		$t = explode(" ", $time);
	     		$result[$key]["date_added"] = date("d F Y", strtotime($t[0]));  				 		
		 	}
		} else {
			$results[] = "";
			}
				 
	 	/****** pagination links ******/
		// range of num links to show
		$range = 3;

		// if not on page 1, don't show back links
		if ($currentpage > 1) {
		   // show << link to go back to page 1
		   
		   // get previous page num
		   $prevpage = $currentpage - 1;
		   // show < link to go back to 1 page
		} // end if 

		// if not on last page, show forward and last page links        
		if ($currentpage != $totalpages) {
		   // get next page
		   $nextpage = $currentpage + 1;
	
		} // end if
		/****** end pagination links ******/
		$this->smarty->assign(array(
			'http_host' => $_SERVER['HTTP_HOST'],
			'this_path' => $this->_path,
			'base_dir'=> __PS_BASE_URI__,
			'testimonials' => $results,
			'currentpage' => $currentpage,
			'prevpage' => $prevpage,
			'nextpage' => $nextpage,
			'totalpages' => $totalpages
		));
				  
		return $this->display(__FILE__, 'views/templates/front/displaytestimonials.tpl');
	 }

     public function displayrandomTestimonial()
     {
     	$result = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'testimonials` where status = "enabled" ORDER BY date_added DESC LIMIT '.Configuration::get('TESTIMONIAL_PERBLOCK'));
     	foreach ($result as $key => $data) {
     		$time = $result[$key]["date_added"];
     		$t = explode(" ", $time);
     		$result[$key]["date_added"] = date("d F Y", strtotime($t[0]));     		
     		$result[$key]["avatar"] = $this->get_avatar($data["testimonial_submitter_email"]);     		
     	}     	     	
      return $result;
     }    
      
     

	function hookLeftColumn()  //display a block link to the front office testimonials page
	{
        $testimonials = $this->displayrandomTestimonial();
        $this->smarty->assign('displayImage', 0);
        if (!empty($testimonials)) {
        	$this->smarty->assign(array(
				'this_path' => $this->_path,
				'testims' => $testimonials,
				'hookn' => ""
			));
        } else {
        	$this->smarty->assign(array(
				'this_path' => $this->_path,
        		'testimonial_submitter_name' => '',
        		'hookn' => ""
			));
        }
		return $this->display(__FILE__, 'views/templates/front/blocktestimonial-column.tpl');
	}

	function hookRightColumn()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		return $this->hookLeftColumn();
	}

	function hookwide_bottom()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{
		$status = $this->getModuleState("wide_bottom");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}
	
	function hookwide_top()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{
		$status = $this->getModuleState("wide_top");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	function hookwide_middle()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		$status = $this->getModuleState("wide_middle");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	function hookdisplayHome()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		$status = $this->getModuleState("displayHome");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	function hookhook_home_01()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		$status = $this->getModuleState("hook_home_01");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	function hookhook_home_02()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		$status = $this->getModuleState("hook_home_02");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	function hookhook_home_03()  //display a block link to the front office testimonials page Same as hookLeftColumn
	{		
		$status = $this->getModuleState("hook_home_03");
		if (($status == 1) && ($this->context->controller->php_self == "index")) {
			$testimonials = $this->displayrandomTestimonial();
			if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 				$this->smarty->assign('testimonial_bg', Tools::getShopProtocol().Context::getContext()->shop->domain.Context::getContext()->shop->physical_uri.'modules/'.$this->name.'/image/testimonial_bg_'.$this->context->shop->id.'.jpg');
 				$this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));

	        if (!empty($testimonials)) {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
					'testims' => $testimonials,
					'hookn' => "wide-"
				));
	        } else {
	        	$this->smarty->assign(array(
					'this_path' => $this->_path,
	        		'testimonial_submitter_name' => '',
	        		'hookn' => "wide-"
				));
	        }
			return $this->display(__FILE__, 'views/templates/front/blocktestimonial.tpl');
		}
	}

	
	public function hookdisplayHeader()
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;
		$this->page_name = Dispatcher::getInstance()->getController();
		if (intval(Configuration::get('TESTIMONIAL_CAPTCHA') == 1) && ($this->page_name == 'addtestimonial'))
			$this->context->controller->addJS('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
		if ($this->page_name == 'addtestimonial')
			$this->context->controller->addJS($this->_path.'js/testimonials.js');

		//$this->context->controller->addJS($this->_path.'js/carousel.js');
		$this->context->controller->addCSS(($this->_path).'css/testimonial.css', 'all');

	}
	 

	public function getContent()
	{
	  if (isset($_POST['Enable']) OR isset($_POST['Disable']) OR isset($_POST['Delete']) OR isset($_POST['Update'])  OR isset($_POST['submitConfig']) OR isset($_POST['Backup'])) {
             $this->_postProcess();
      	}

          // echo var_dump($_POST);
            $this->_html = $this->_displayConfigForm();
            $this->_html .= $this->getadminTestimonials();
            $this->context->controller->addJS($this->_path.'js/displayadmintestimonial.js');
            return $this->_html;
    }

	private function _postProcess() {

          if (Tools::isSubmit('submitConfig')) {

          		$id_shop = (int)$this->context->shop->id;
                $reCaptcha = Tools::getValue('reCaptcha');
                if ($reCaptcha != 0 AND $reCaptcha != 1)
                    $output .= '<div class="alert error">'.$this->l('recaptcha : Invalid choice.').'</div>';
                else
                    Configuration::updateValue('TESTIMONIAL_CAPTCHA', intval($reCaptcha));

				$recaptchaPub = strval(Tools::getValue('recaptchaPub'));

				if (!$recaptchaPub OR empty($recaptchaPub))
               		$this->_html .= '<div class="alert error">'.$this->l('Please enter your public key').'</div>';

				else
					Configuration::updateValue('TESTIMONIAL_CAPTCHA_PUB', strval($recaptchaPub));

				$recaptchaPriv = strval(Tools::getValue('recaptchaPriv'));
               
				if (!$recaptchaPriv OR empty($recaptchaPriv))
					$this->_html .= '<div class="alert error">'.$this->l('Please enter your private key').'</div>';
				else
					Configuration::updateValue('TESTIMONIAL_CAPTCHA_PRIV', strval($recaptchaPriv));

				$perPage = strval(Tools::getValue('perPage'));

                if (!$perPage OR empty($perPage))
                	$this->_html .= '<div class="alert error">'.$this->l('Please enter the amount of testimonials per page').'</div>';
                else
                	Configuration::updateValue('TESTIMONIAL_PERPAGE', strval($perPage));

				$perBlock = strval(Tools::getValue('perBlock'));

				if (!$perBlock OR empty($perBlock))
					$this->_html .= '<div class="alert error">'.$this->l('Please enter the amount of testimonials in the module').'</div>';
				else
					Configuration::updateValue('TESTIMONIAL_PERBLOCK', strval($perBlock));

				$displayImage = strval(Tools::getValue('displayImage'));

				if ($displayImage != 0 AND $displayImage != 1)
					$this->_html .= '<div class="alert error">'.$this->l('Please select whether to allow users to upload the Testimonial Image').'</div>';
				else
					Configuration::updateValue('TESTIMONIAL_DISPLAY_IMG', strval($displayImage));

				if (isset($_FILES['testimonialsbg']) && isset($_FILES['testimonialsbg']['tmp_name']) && !empty($_FILES['testimonialsbg']['tmp_name'])) {

					$img = dirname(__FILE__).'/image/testimonial_bg_'.(int)$id_shop.'.jpg';

					if (file_exists($img))
						unlink($img);
					
					if ($error = ImageManager::validateUpload($_FILES['testimonialsbg']))
						$errors .= $error;

					elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['testimonialsbg']['tmp_name'], $tmp_name))
						return false;			

					elseif (!ImageManager::resize($tmp_name, $img))
						$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));

					if (isset($tmp_name))
						unlink($tmp_name);

				}

			}

			if (isset($_POST['Backup'])) {
                $result = Db::getInstance()->ExecuteS("SELECT * from `"._DB_PREFIX_."testimonials`");
                if ($result == true) {

                	$filename = dirname(__FILE__).'/backup.csv';
                    $fp = fopen($filename, 'w');

                	foreach ($result as $key => $res) {
                        fputcsv($fp,$res);                                                
                	}

                	$this->_html .= $this->displayConfirmation($this->l('The .CSV file has been successfully exported') );
                	fclose($fp);
                }

                else
                    $this->_html .= $this->displayError($this->l('No Testimonials to Backup'));
			}

			if (isset($_POST['Delete'])) {
			
				foreach($_POST['moderate'] as $check => $val) {
                 $deleted=Db::getInstance()->Execute('
                 DELETE FROM `'._DB_PREFIX_.'testimonials`
                 WHERE testimonial_id =  "'.($val).'"
                 ');
             	}
           	}

         if (isset($_POST['Enable']))
                 {
                     foreach($_POST['moderate'] as  $check => $val)
                     {
                         $enabled=Db::getInstance()->Execute('
                         UPDATE `'._DB_PREFIX_.'testimonials`
                         SET `status` = "Enabled"
                         WHERE `testimonial_id` = "'.($val).'"');
                     }
                 }

       if (isset($_POST['Disable']))
             {
 			 
		 foreach($_POST['moderate'] as  $check => $val)
                    {
             		
			$disabled=Db::getInstance()->Execute('
                         UPDATE `'._DB_PREFIX_.'testimonials`
                         SET `status` = "Disabled"
                         WHERE `testimonial_id` = "'.($val).'"');
                     }
               }
			   
	if (isset($_POST['Update']))
             {
                    foreach($_POST['moderate'] as  $check => $val)
                    {
             		$testimonial_main_message =  "testimonial_main_message_".$val;
                        //echo $testimonial_main_message;
			$testimonial_main_message = $_POST[$testimonial_main_message];
                         
			 $update=Db::getInstance()->Execute('
                         UPDATE `'._DB_PREFIX_.'testimonials`
                         SET `testimonial_main_message` = "'.$testimonial_main_message.'"
                         WHERE `testimonial_id` = "'.($val).'"');
                    }
              }
			   
			   
return $this->_html;

     }
	 
	 function backupFile(){  //check if backup file exists
		if (file_exists(dirname(__FILE__).'/backup.csv'))
			return true;
	 	return false;
	 }
	 
	 
	function _displayConfigForm(){

 		global $cookie;
 		$rev = date("H").date("i").date("s")."\n";

 		if (file_exists(dirname(__FILE__).'/image/testimonial_bg_'.$this->context->shop->id.'.jpg'))
 			$this->smarty->assign('testimonial_bg', $this->_path.'image/testimonial_bg_'.$this->context->shop->id.'.jpg?'.$rev);
 		else 
 			$this->smarty->assign('testimonial_bg', $this->_path.'image/demo.jpg');

		$this->smarty->assign('base_dir', __PS_BASE_URI__);
		$this->smarty->assign('requestUri', $_SERVER['REQUEST_URI']);
		$this->smarty->assign('recaptcha', Configuration::get('TESTIMONIAL_CAPTCHA'));
		$this->smarty->assign('recaptchaPriv', Configuration::get('TESTIMONIAL_CAPTCHA_PRIV'));
		$this->smarty->assign('recaptchaPub', Configuration::get('TESTIMONIAL_CAPTCHA_PUB'));
		$this->smarty->assign('recaptchaPerpage', Configuration::get('TESTIMONIAL_PERPAGE'));
		$this->smarty->assign('recaptchaPerBlock', Configuration::get('TESTIMONIAL_PERBLOCK'));
        $this->smarty->assign('maximagesize', Configuration::get('TESTIMONIAL_MAX_IMG'));
        $this->smarty->assign('displayImage', Configuration::get('TESTIMONIAL_DISPLAY_IMG'));
        $this->smarty->assign('backupfileExists', $this->backupFile());

		return $this->display(__FILE__,'views/templates/admin/displayadmincfgForm.tpl');

	}


	function getadminTestimonials()
	{
		$results = null;
		$testimonials = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'testimonials` ORDER BY date_added DESC');
			 // while there are rows to be fetched...
		 	foreach ($testimonials as $key => $testimonial) {
		 		$results[] = $testimonial;
		 	}
			
			$this->smarty->assign(array(
                  'testimonials' => $results,
			      'requestUri', $_SERVER['REQUEST_URI'],
                  'http_host', $_SERVER['HTTP_HOST'],
			      'base_dir', __PS_BASE_URI__,
			      'this_path' => $this->_path
			));
				  
			return $this->display(__FILE__,'views/templates/admin/displayadmintestimonialsForm.tpl');
        }
    public function hookDisplayBackOfficeHeader()
	{
		// Check if module is loaded
		if (Tools::getValue('configure') != $this->name)
			return false;

		// CSS
		$this->context->controller->addCSS($this->_path.'css/admintestimonial.css');
		
	}

	public function get_avatar( $email, $size = '70', $default = 'mystery', $alt = false ) {

		if ( false === $alt)
			$safe_alt = '';
		else
			$safe_alt = esc_attr( $alt );

		if ( !is_numeric($size) )
			$size = '96';

		if ( !empty($email) )
			$email_hash = md5( strtolower( trim( $email ) ) );

		if ( Tools::usingSecureMode() ) {
			$host = 'https://secure.gravatar.com';
		} else {
			if ( !empty($email) )
				$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
			else
				$host = 'http://0.gravatar.com';
		}

		if ( 'mystery' == $default )
			$default = "$host/avatar/ad516503a11cd5ca435acc9bb6523536"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
		elseif ( 'blank' == $default )
			$default = includes_url('images/blank.gif');
		elseif ( !empty($email) && 'gravatar_default' == $default )
			$default = '';
		elseif ( 'gravatar_default' == $default )
			$default = "$host/avatar/?s={$size}";
		elseif ( empty($email) )
			$default = "$host/avatar/?d=$default&amp;s={$size}";
		elseif ( strpos($default, 'http://') === 0 )
			$default = add_query_arg( 's', $size, $default );

		if ( !empty($email) ) {
			$out = "$host/avatar/";
			$out .= $email_hash;
			//$out .= '?s='.$size;
			$out .= '&amp;d=' . urlencode( $default );

			$avatar = "<img alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		} else {
			$avatar = "<img alt='{$safe_alt}' src='{$default}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
		}

		return $avatar;
	}

	public function getModuleState($hook)	{  // get options from database
		if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
			return false;		
		return $sett[0]["value"];
	}

}
?>