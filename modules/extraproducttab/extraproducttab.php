<?php
/*
* 2007-2015 mitsos1os
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize the module for your
* needs please refer to
*  @author mitsos1os
*  @copyright  2014-2015
*/
if (!defined('_PS_VERSION_'))
    exit;
 
class ExtraProductTab extends Module
{
    /* @var boolean error */
    protected $_errors = false;
    private $languages;
    private $default_lang;
    
    public function __construct()
    {
        $this->name = 'extraproducttab';
        $this->tab = 'front_office_features';
        $this->version = '2.5.4.1';
        $this->author = 'mitsos1os';
        $this->module_key = '0d8b2ad951d72e6b8f348e84c1b405cf';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
        $this->bootstrap = true;
        parent::__construct();
 
        $this->displayName = $this->l('Extra Product Tabs');
        $this->description = $this->l('Module for creating extra tabs in product description');

    }
    
    
    public function install()
    {
        if (!parent::install() ||
            !Configuration::updateValue('PS_extraProductTab_displayHeader',true) ||
            !$this->createTables() ||
            //!$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('actionProductSave') ||
            !$this->registerHook('actionProductDelete') ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('displayProductTab') ||
            !$this->registerHook('displayProductTabContent'))
            return false;
        return true;
    }
    
    public function uninstall()
    {
        if (!parent::uninstall() OR !$this->removeTables() OR !Configuration::deleteByName('PS_extraProductTab_displayHeader'))
            return false;
        return true;
    }
    
    public function createTables()
    {
        //Create the extraproducttab main table
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'extraproducttab` (`id_Tab` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,`name` TEXT(20) NOT NULL,`position` SMALLINT(10),PRIMARY KEY(`id_Tab`)) CHARACTER SET = `utf8` COLLATE = `utf8_general_ci`;';
        if(!Db::getInstance()->execute($sql))
            return false;
    
        //Create the extraproducttab_lang
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'extraproducttab_lang`(`id_Tab` INT(10) UNSIGNED NOT NULL,`id_lang` INT(10) UNSIGNED NOT NULL,`displayname` TEXT(20) NOT NULL,`defaultContent` TEXT,PRIMARY KEY(`id_Tab`,`id_lang`)) CHARACTER SET = `utf8` COLLATE = `utf8_general_ci`;';
        if(!Db::getInstance()->execute($sql))
            return false;
            
        //Create the extraproducttab_product
        $sql = 'CREATE TABLE IF NOT EXISTS `' ._DB_PREFIX_. 'extraproducttab_product`(`id_Tab` INT(10) UNSIGNED NOT NULL,`id_product` INT(10) UNSIGNED NOT NULL,`notActive` TINYINT(1) DEFAULT 0,PRIMARY KEY(`id_tab`,`id_product`)) CHARACTER SET = `utf8` COLLATE = `utf8_general_ci`;';  
        if(!Db::getInstance()->execute($sql))
            return false;
            
        //Create the extraproducttab_product_lang
        $sql = 'CREATE TABLE IF NOT EXISTS `' ._DB_PREFIX_. 'extraproducttab_product_lang` (`id_Tab` INT(10) UNSIGNED NOT NULL,`id_product` INT(10) UNSIGNED NOT NULL,`id_lang` INT(10) UNSIGNED NOT NULL,`content` TEXT,PRIMARY KEY(`id_tab`,`id_product`,`id_lang`)) CHARACTER SET = `utf8` COLLATE = `utf8_general_ci`;';
        if(!Db::getInstance()->execute($sql))
            return false;
    
        return true;
    }
    
    public function removeTables()
    {
        //remove extraProducTab tables
        $sql = 'DROP TABLE IF EXISTS `' ._DB_PREFIX_. 'extraproducttab_lang`,`' ._DB_PREFIX_. 'extraproducttab_product`,`' ._DB_PREFIX_. 'extraproducttab_product_lang`,`' ._DB_PREFIX_. 'extraproducttab`;';
        if(!Db::getInstance()->execute($sql))
            return false;
        
        return true;
    }

    public function getContent()
    {
        $output = null;
        $output .= $this->_postProcess();
        if (Tools::isSubmit('addExtraProductTab') || Tools::isSubmit('update'.$this->name))
             $output.=$this->displayAddForm();
        else
        {
            $helper = $this->initExtraTabList();
            $content = $this->getTabListContent();
            $output .= $helper->generateList($content,$this->fields_list);
            $output .= $this->generatePreferencesForm();
        }
        return $output;
    }


    private function _postProcess()
    {
        //update configuration object for display Header
        if (Tools::isSubmit('submitOptionsconfiguration')){
            return Configuration::updateValue('PS_extraProductTab_displayHeader',Tools::getValue('PS_extraProductTab_displayHeader'));
        }

        if (Tools::isSubmit('submit'.$this->name))
        {
            //check for new or already set tab
            if (Tools::getValue('extraProductTab_id') == '')
            {
                //Extra product Tab submitted. Save it in DB
                $result = Db::getInstance()->insert('extraproducttab',array(
                    'name' => pSQL(Tools::getValue('extraProductTab_name')),
                    'position' => (int)Tools::getValue('extraProductTab_position')
                ));

                //Check for error in addition
                if (!$result)
                    return $this->displayError($this->l('Error in record addition in table:').'extraproducttab');

                //Get the id of the tab we have just added
                $tabID = (int)Db::getInstance()->getValue('SELECT MAX(id_Tab) from '._DB_PREFIX_.'extraproducttab ;');
                $tempLanguages = $this->context->language->getLanguages();
                //add the display name for each language for the tab
                foreach($tempLanguages as $language)
                {
                    $result = Db::getInstance()->insert('extraproducttab_lang',array(
                        'id_Tab' => (int)$tabID,
                        'id_lang' => (int)$language['id_lang'],
                        'displayname' => pSQL(Tools::getValue('extraProductTab_displayName_'.$language['id_lang'])),
                        'defaultContent' => pSQL(Tools::getValue('extraProductTab_defaultContent_'.$language['id_lang']),true)
                    ));
                    if (!$result)
                        return $this->displayError($this->l('Error in record addition in table:').'extraproducttab_lang');
                }

                //all ok so return and inform user

                return $this->displayConfirmation($this->l('Extra Product Tab successfull save!'));
            }
            else
            {
                //existing tab so modify
                $tabID = (int)Tools::getValue('extraProductTab_id');
                $result = Db::getInstance()->update('extraproducttab',array(
                    'name' => pSQL(Tools::getValue('extraProductTab_name')),
                    'position' => (int)Tools::getValue('extraProductTab_position')
                ),'id_Tab = '.(int)$tabID);

                //Check for error in addition
                if (!$result)
                    return $this->displayError($this->l('Error in record modification in table:').'extraproducttab');

                $tempLanguages = $this->context->language->getLanguages();
                //also add the displayNames
                foreach($tempLanguages as $language)
                {
                    $result = Db::getInstance()->update('extraproducttab_lang',array(
                        'displayname' => pSQL(Tools::getValue('extraProductTab_displayName_'.$language['id_lang'])),
                        'defaultContent' => pSQL(Tools::getValue('extraProductTab_defaultContent_'.$language['id_lang']),true)
                    ),'id_Tab = '.(int)$tabID.' AND id_lang = '.(int)$language['id_lang']);
                    if (!$result)
                        return $this->displayError($this->l('Error in record modification in table:').'extraproducttab_lang');
                }

                //all ok so return and inform user

                return $this->displayConfirmation($this->l('Extra Product Tab successfull save!'));
            }

        }
        elseif (Tools::getIsset('delete'.$this->name))
        {
            $tabID = (int)Tools::getValue('id_Tab');
            $result = $this->deleteExtraProductTab($tabID);

            if ($result)
                return $this->displayConfirmation($this->l('Successful Extra Product Tab Deletion.'));
            else
                return $this->displayError($this->l('Problem in deleting Extra Product Tab'));

        }

    }

    private function deleteExtraProductTab($tabID)
    {       
        $result = Db::getInstance()->delete('extraproducttab_product_lang','`id_Tab` = '.$tabID);
        if (!$result)
            return $result;
        $result = Db::getInstance()->delete('extraproducttab_product','`id_Tab` = '.$tabID);
        if (!$result)
            return $result;
        $result = Db::getInstance()->delete('extraproducttab_lang','`id_Tab` = '.$tabID);
        if (!$result)
            return $result;
        $result = Db::getInstance()->delete('extraproducttab','`id_Tab` = '.$tabID);

        return $result;
    }

    protected function displayAddForm()
    {
        $token = Tools::getAdminTokenLite('AdminModules');
        $back = Tools::safeOutput(Tools::getValue('back', ''));
        $current_index = AdminController::$currentIndex;
        if (!isset($back) || empty($back))
            $back = $current_index.'&amp;configure='.$this->name.'&token='.$token.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $this->context->controller->getLanguages();
        $fields_form = array();
        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('View/Edit Extra Product Tab'),
                    'icon' =>'icon-list-alt'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tab ID:'),
                        'name' => 'extraProductTab_id',
                        'class' => 'fixed-width-lg',
                        'readonly' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tab Name:'),
                        'name' => 'extraProductTab_name',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tab Position'),
                        'name' => 'extraProductTab_position',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tab Display Name'),
                        'name' => 'extraProductTab_displayName',
                        'lang' => true,
                        'class' => 'fixed-width-xxl'
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Tab Default Content'),
                        'name' => 'extraProductTab_defaultContent',
                        'autoload_rte' =>true,
                        'lang' => true
                    )
                ),
                'buttons' => array(
                    'cancelExtraTab' => array(
                        'title' => $this->l('Cancel'),
                        'href' => $back,
                        'icon' => 'process-icon-cancel'
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
        );

        $helper  = new HelperForm();
        $helper->module = $this;
        $helper->title = $this->displayName;
        $helper->name_controller = $this->name;
        $helper->token = $token;
        $helper->currentIndex = $current_index.'&configure='.$this->name;
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->languages = $this->context->controller->_languages;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->submit_action = 'submit'.$this->name;

        //fill form fields
        //Edit Tab
        if (Tools::getIsset('update'.$this->name))
        {
           $tabID = (int)Tools::getValue('id_Tab');
           $sqlTabDetails = 'SELECT * FROM `'._DB_PREFIX_.'extraproducttab` WHERE `id_Tab` = '.$tabID;
           $tabDetails = Db::getInstance()->getRow($sqlTabDetails);

           $helper->fields_value['extraProductTab_id'] = $tabID;
           $helper->fields_value['extraProductTab_name'] = $tabDetails['name'];
           $helper->fields_value['extraProductTab_position'] = $tabDetails['position'];

           $sqlTabDisplayNames = 'SELECT * FROM `'._DB_PREFIX_.'extraproducttab_lang` WHERE `id_Tab` = '.$tabID.' ORDER BY `id_lang`';
           $tabDisplayNames = Db::getInstance()->executeS($sqlTabDisplayNames);

           foreach (Language::getLanguages(false) as $lang)
           {
               foreach ($tabDisplayNames as $displayName)
               {
                   if ($displayName['id_lang'] == $lang['id_lang'])
                   {
                       $helper->fields_value['extraProductTab_displayName'][(int)$lang['id_lang']] = $displayName['displayname'];
                       $helper->fields_value['extraProductTab_defaultContent'][(int)$lang['id_lang']] = $displayName['defaultContent'];
                       break;
                   }
               }
           }

        }
        //New Tab
        else
        {
            //defult empty values

            $helper->fields_value['extraProductTab_id'] = null;
            $helper->fields_value['extraProductTab_name'] = null;
            $helper->fields_value['extraProductTab_position'] = null;
            $helper->fields_value['extraProductTab_defaultContent'] = null;

            foreach (Language::getLanguages(false) as $lang)
            {
                $helper->fields_value['extraProductTab_displayName'][(int)$lang['id_lang']] = null;
            }
        }

        return $helper->generateForm($fields_form);

    }

    private function generatePreferencesForm()
    {
        $fields_options = array(
            'general' => array(
                'title' => $this->l('Preferences'),
                'fields' => array(
                    'PS_extraProductTab_displayHeader' => array(
                        'title' => $this->l('Tab Header in Content'),
                        'desc' => $this->l('Choose if tab header is included in the same hook as content (Preastashop Default Theme)'),
                        'cast' => 'boolval',
                        'type' => 'bool'
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            )
        );
        $helperOptions = new HelperOptions($this);
        $helperOptions->id = $this->id;
        $helperOptions->token = Tools::getAdminTokenLite('AdminModules');
        $helperOptions->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helperOptions->generateOptions($fields_options);
    }

    private function initExtraTabList()
    {
        $this->fields_list = array(
          'id_Tab' => array(
              'title' => $this->l('Tab ID'),
              'type' => 'text',
              'search' => false,
              'orderby' => true,
              'width' => 50,
          ),
          'name' => array(
              'title' => $this->l('Tab Name'),
              'type' => 'text',
              'search' => false,
              'orderby' => true,
              'width' => 'auto',
          ),
            'position' => array(
                'title' => $this->l('Tab Position'),
                'type' => 'text',
                'search' => false,
                'orderby' => true,
                'width' => 50,
            )

        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_Tab';
        $helper->actions = array('edit','delete');
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&addExtraProductTab'.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        return $helper;

    }

    private function getTabListContent()
    {
        $content = Db::getInstance()->executeS('
        SELECT *
        FROM `'._DB_PREFIX_.'extraproducttab`
        ORDER BY `'.(Tools::getIsset('extraproducttabOrderby') && Tools::getIsset('extraproducttabOrderway') ?
        bqSQL(Tools::getValue('extraproducttabOrderby')).'` '.bqSQL(Tools::getValue('extraproducttabOrderway')):
        'position`')
        );

        return $content;
    }

//HOOKS IMPLEMENTATION

    public function hookDisplayAdminProductsExtra($params)
    {
        //if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product'))))
        if (true)
        {

            //find Extra Product Tab IDs
            $extraTabIDSQL = 'SELECT * FROM '._DB_PREFIX_.'extraproducttab ORDER BY `position` ASC ;';
            $extraTabs = Db::getInstance()->executeS($extraTabIDSQL);

            //check if there are any extra product tabs
            if (!$extraTabs)
            {
                $returnNoTabs = '<h3><i>'.$this->l('No Extra Tabs defined in the module configuration').'</i><h3>';
                return $returnNoTabs;
            }

            //Get Tab display names
            $tabDisplayNamesSQL = 'SELECT * FROM '._DB_PREFIX_.'extraproducttab_lang;';
            $tabDisplayNames = Db::getInstance()->executeS($tabDisplayNamesSQL);

            $currentProductID = (int)Tools::getValue('id_product');
            if ($currentProductID !== 0){
                //Get Tab productactivation
                $productTabsActivationSQL = 'SELECT `id_Tab`,`notActive` FROM '._DB_PREFIX_.'extraproducttab_product WHERE `id_product` = '.$currentProductID.';';
                $productTabsActivation = Db::getInstance()->executeS($productTabsActivationSQL);

                //Get content for tabs
                $productTabsContentSQL = 'SELECT `id_Tab`,`id_lang`,`content` FROM '._DB_PREFIX_.'extraproducttab_product_lang WHERE `id_product` = '.$currentProductID.';';
                $productTabsContent = Db::getInstance()->executeS($productTabsContentSQL);
            }
            else{
                $productTabsActivation = null;
                $productTabsContent = null;
            }

            $formGeneration = "manual";
            //generate the form with the helper but delete the form tags in order to avoid inner form from the product's original details
            if ($formGeneration == "helper")
            {
                $output =  $this->renderForm($extraTabs, $tabDisplayNames, $productTabsActivation, $productTabsContent);
                $startPos = strpos($output,'>')+1;
                $endPos = strpos($output,'</form>');
                //$output = Tools::substr($output,$startPos,$endPos-$startPos) . Tools::substr($output,$endPos + 7);
                //$output = str_replace('autoload_rte','extraProductTab_rte',$output);
                //return $output;
                //check for PS Version
                if (strpos(_PS_VERSION_,'1.5') !== false)
                {
                    //it is 1.5 version
                    $output1 = Tools::substr($output,$startPos,$endPos-$startPos);
                    $startPos = strpos($output,'<script');
                    $output2 = Tools::substr($output,$startPos);
                    $output3 = $output1.$output2;
                    $output3 = str_replace('autoload_rte','extraProductTab_rte',$output3);
                    return $output3;
                }
                else
                {
                    //it is 1.6 version
                    $output = Tools::substr($output,$startPos,$endPos-$startPos);
                    return $output.$this->display(__FILE__, 'javascript.tpl');
                }


            }
            elseif ($formGeneration == "manual")
            {
                $this->context->smarty->assign(array(
                    'languages' => $this->context->controller->_languages,
                    'extraTabs' => $extraTabs,
                    'tabDisplayNames' => $tabDisplayNames,
                    'productTabsActivation' => $productTabsActivation,
                    'productTabsContent' => $productTabsContent
                ));
                if (strpos(_PS_VERSION_,'1.5') !== false)
                {
                    //1.5 version
                    $temp = $this->display(__FILE__, 'extraproducttab15.tpl');
                    //$temp = str_replace('autoload_rte','extraProductTab_rte',$temp);

                    return $temp;
                }
                else
                {
                  //1.6 version
                    return $this->display(__FILE__, 'extraproducttab.tpl');
                }

            }
        }
    }

    private function saveExtraProductTab($id_product){
        //get passed product id and save extra tab info if there
        $tabIDs = Db::getInstance()->executeS('SELECT `id_Tab` FROM '._DB_PREFIX_.'extraproducttab;');
        $languages = Language::getLanguages();
        foreach ($tabIDs as $currentTabID)
        {
            $tabID = $currentTabID['id_Tab'];
            //product Tab Activation
            $tabActiveOnProduct = (bool)Tools::getValue('extraTab_'.$tabID.'_active_on');
            //update the Database
            if ($tabActiveOnProduct)
            {
                if(!Db::getInstance()->delete('extraproducttab_product','`id_Tab` = '.$tabID.' AND `id_product` = '.$id_product))
                    $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
            }
            else
            {
                //if there don't do anything
                if (!Db::getInstance()->getValue('SELECT COUNT(*) FROM '._DB_PREFIX_.'extraproducttab_product WHERE `id_Tab` = '.$tabID.' AND `id_product` = '.$id_product))
                {
                    //update the database with not activation
                    if(!Db::getInstance()->insert('extraproducttab_product',array(
                        'id_Tab' => (int)$tabID,
                        'id_product' => (int)$id_product,
                        'notActive' => 1
                    )))
                        $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
                }
            }

            //product Tab Content
            foreach ($languages as $language)
            {
                //check if there is actually any content in the lang, if not skip for this tab
                $tabContent = '';
                $tabContent = (string)Tools::getValue('extraTab_'.$tabID.'_content_'.$language['id_lang']);
                $tabLangCount = (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM '._DB_PREFIX_.'extraproducttab_product_lang WHERE `id_Tab` = '.$tabID.' AND `id_product` = '.$id_product.' AND `id_lang` = '.$language['id_lang']);
                if (!$tabContent)
                {
                    if ($tabLangCount == 0)
                        continue;
                    else
                    {
                        if (!Db::getInstance()->delete('extraproducttab_product_lang','`id_Tab` = '.$tabID.' AND `id_product` = '.$id_product.' AND `id_lang` = '.$language['id_lang']))
                            $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
                    }

                }
                else
                {
                    if ($tabLangCount == 0)
                    {
                        if(!Db::getInstance()->insert('extraproducttab_product_lang',array(
                            'id_Tab' => $tabID,
                            'id_product' => $id_product,
                            'id_lang' => $language['id_lang'],
                            'content' => pSQL($tabContent,true)
                        )))
                            $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
                    }
                    else
                    {
                        if(!Db::getInstance()->update('extraproducttab_product_lang',array(
                                'content' =>pSQL($tabContent,true)),
                            '`id_Tab` = '.$tabID.' AND `id_product` = '.$id_product.' AND `id_lang` = '.$language['id_lang']))
                            $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
                    }
                }

            }

        }
    }

    public function hookActionProductSave($params){
        //check if extra product tabs are submitted
        $submitted_tabs = Tools::getValue('submitted_tabs');
        if (!array_search('ModuleExtraproducttab',$submitted_tabs,true))
            return;

        if (array_key_exists('id_product',$params) && !is_null($params['id_product'])){
            $id_product = $params['id_product'];
            $this->saveExtraProductTab($id_product);
        }

    }

    public function hookActionProductUpdate($params)
    {
        //check if extra product tabs are submitted
        $submitted_tabs = Tools::getValue('submitted_tabs');
        if (!array_search('ModuleExtraproducttab',$submitted_tabs,true))
            return;

        $id_product = (int)Tools::getValue('id_product');
        $this->saveExtraProductTab($id_product);
    }

    public function hookActionProductDelete($params){
        if (!array_key_exists('id_product',$params) || is_null($params['id_product'])){
            return;
        }
        $id_product = $params['id_product'];
        //delete the product content in tabs and tab activation
        if (!Db::getInstance()->delete('extraproducttab_product','id_product = '.$id_product))
            $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
        if (!Db::getInstance()->delete('extraproducttab_product_lang','id_product = '.$id_product))
            $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();

    }

    private function getProductTabs(){
        if (isset($this->productTabs)){
            //if set earlier skip re querying the database
            $productTabs = $this->productTabs;
        }
        else{
            $id_product = (int)Tools::getValue('id_product');
            $id_lang = (int)Tools::getValue('id_lang');
            if ($id_lang == 0)
            $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            //get tabs that are activated on product
            $productTabSQL='SELECT `tab`.`id_Tab`,`tablang`.`displayname`,IF(LENGTH(`tabContent`.`content`)>0,`tabContent`.`content`,`tablang`.`defaultContent`) AS content FROM `'._DB_PREFIX_.'extraproducttab` `tab` INNER JOIN `'._DB_PREFIX_.'extraproducttab_lang` `tablang` ON `tab`.`id_Tab` = `tablang`.`id_Tab`  LEFT JOIN `'._DB_PREFIX_.'extraproducttab_product_lang` `tabContent` ON `tab`.`id_Tab` = `tabContent`.`id_Tab` AND `tabContent`.`id_product` = '.$id_product.' AND `tabContent`.`id_lang` = '.$id_lang.' WHERE `tablang`.`id_lang` = '.$id_lang.' AND `tab`.`id_Tab` NOT IN (SELECT `id_Tab` FROM `'._DB_PREFIX_.'extraproducttab_product` WHERE `id_product` = '.$id_product.') HAVING LENGTH(content)>0 ORDER BY `tab`.`position` ASC;';
            $productTabs = Db::getInstance()->executeS($productTabSQL);
            $this->productTabs = $productTabs;
        }

        return $productTabs;
    }

    public function hookDisplayProductTab($params)
    {
        if (Configuration::get('PS_extraProductTab_displayHeader'))
            return;

        $id_lang = (int)Tools::getValue('id_lang');
        if ($id_lang == 0)
            $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        //get tab name and display name for current language if active on product
        $productTabs = $this->getProductTabs();
        if (count($productTabs) <=0)
            return;
        //assign variables to smarty
        $this->context->smarty->assign(array(
            'productTabs' => $productTabs
        ));

       return $this->display(__FILE__, 'producttab.tpl');
    }

    public function hookDisplayProductTabContent($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Tools::getValue('id_lang');
        if ($id_lang == 0)
            $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        //get tab name and display name for current language if active on product
        $productTabs = $this->getProductTabs();
        if (count($productTabs) <=0)
            return;
        //assign variables to smarty
        $this->context->smarty->assign(array(
            'productTabs' => $productTabs,
            'displayHeader' => Configuration::get('PS_extraProductTab_displayHeader')
        ));
        return $this->display(__FILE__, 'producttabcontent.tpl');
    }

    public function renderForm($extraTabs, $tabDisplayNames, $productTabsActivation, $productTabsContent)
    {
        //not used.... because no extra form needed
        $token = Tools::getAdminTokenLite('AdminProducts');
        $current_index = AdminController::$currentIndex.'&id_product='.(int)Tools::getValue('id_product');
        $fields_form = array();
        // add the field values
        $fields_value = array();
        //create the fieldset for each tab and get the input values for the form
        foreach ($extraTabs as $extraTab)
        {
            $tabID = $extraTab['id_Tab'];
            if (strpos(_PS_VERSION_,'1.5') !== false)
            {
                //1.5 version
                $fields_form[]['form'] = array(
                    'legend' => array(
                        'title' => $extraTab['name'].' '.$this->l(" content.")
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => $this->l('Tab display name'),
                            'name' => 'extraTab_'.$tabID.'_displayName',
                            'readonly' => true,
                            'lang' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'label' => $this->l('Active on this product'),
                            'name' => 'extraTab_'.$tabID.'_active',
                            'values' => array(
                                'query' => array(
                                    array(
                                        'id' => 'on',
                                        'name' => '',
                                        'val' => '1'
                                    ),
                                ),
                                'id' => 'id',
                                'name' => 'name'
                            )
                        ),
                        array(
                            'type' => 'textarea',
                            'label'=> $this->l('Content'),
                            'name' => 'extraTab_'.$tabID.'_content',
                            'lang' => true,
                            'rows' => 60,
                            'cols' => 40,
                            'autoload_rte' => true
                        )
                    ),


                );
            }
            else{
                //1.6 version
                $fields_form[]['form'] = array(
                    'legend' => array(
                        'title' => $extraTab['name'].' '.$this->l(" content.")
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => $this->l('Tab display name'),
                            'name' => 'extraTab_'.$tabID.'_displayName',
                            'readonly' => true,
                            'lang' => true,
                        ),
                        array(
                            'type' => 'checkbox',
                            'label' => $this->l('Active on this product'),
                            'name' => 'extraTab_'.$tabID.'_active',
                            'values' => array(
                                'query' => array(
                                    array(
                                        'id' => 'on',
                                        'name' => '',
                                        'val' => '1'
                                    ),
                                ),
                                'id' => 'id',
                                'name' => 'name'
                            )
                        ),
                        array(
                            'type' => 'textarea',
                            'label'=> $this->l('Content'),
                            'name' => 'extraTab_'.$tabID.'_content',
                            'lang' => true,
                            'rows' => 60,
                            'cols' => 40,
                            'class' => 'extraProductTab_rte'
                        )
                    ),


                );
            }

            //first get the simple value of tab activation
            $tmpKey = $this->findInArray($productTabsActivation,'id_Tab',$tabID);
            if ($tmpKey != -1)
                $fields_value['extraTab_'.$tabID.'_active_on'] = ($productTabsActivation[$tmpKey]['notActive']?0:1);
            else
                $fields_value['extraTab_'.$tabID.'_active_on']=1;
            //now get the multilingual fields
            foreach ($this->context->controller->_languages as $language)
            {
                //tab display names
                foreach($tabDisplayNames as $tabDisplayName)
                {
                    if ($tabDisplayName['id_Tab'] == $tabID && $tabDisplayName['id_lang'] == $language['id_lang'])
                    {
                        $fields_value['extraTab_'.$tabID.'_displayName'][$language['id_lang']] = $tabDisplayName['displayname'];
                        break;
                    }
                }
                //tab Content
                $fields_value['extraTab_'.$tabID.'_content'][$language['id_lang']]='';
                    foreach($productTabsContent as $tabContent)
                    {
                        if ($tabContent['id_Tab'] == $tabID && $tabContent['id_lang'] == $language['id_lang'])
                        {
                            $fields_value['extraTab_'.$tabID.'_content'][$language['id_lang']] = $tabContent['content'];
                            break;
                        }

                    }

            }
        }

        //add the save and cancel buttons
        $fields_form[sizeof($fields_form)-1]['form']['buttons'] = array(
            'cancelButton' => array(
                'title' => $this->l('Cancel'),
                'href' => $this->context->link->getAdminLink('AdminProducts'),
                'class' => 'btn btn-default',
                'icon' => 'process-icon-cancel'
            ),
            'saveButton' => array(
                'title'=> $this->l('Save'),
                'name' => 'submitAddproduct',
                'type' => 'submit',
                'class' => 'btn btn-default pull-right',
                'icon' => 'process-icon-save'
            ),
            'saveAndStayButton' => array(
                'title' => $this->l('Save and stay'),
                'name' => 'submitAddproductAndStay',
                'type' => 'submit',
                'class' => 'btn btn-default pull-right',
                'icon' => 'process-icon-save'
            )
        );

        //hidden submitted tabs info
        $fields_form[0]['form']['input'][]=array(
          'type' => 'hidden',
          'name' => 'submitted_tabs[]'
        );
        $fields_value['submitted_tabs[]'] = 'ModuleExtraproducttab';

        $helper = new HelperForm();
        $helper->languages = $this->context->controller->_languages;
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = $token;
        $helper->currentIndex = $current_index;
        $default_lang =(int)Configuration::get('PS_LANG_DEFAULT');
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->show_toolbar = false;
        $helper->fields_value=$fields_value;
        $helper->submit_action = 'submitExtraTabs';

        return $helper->generateForm($fields_form);
    }

    private function findInArray($arraySearched,$keyName,$value)
    {
        $index = 0;
        foreach($arraySearched as $item)
        {
           if ($item[$keyName] == $value)
               return $index;
            $index++;
        }
        return -1;
    }
}
?>