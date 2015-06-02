<?php

/*
* @author    Krystian Podemski <podemski.krystian@gmail.com>
* @site
* @copyright  Copyright (c) 2014 impSolutions (http://www.impsolutions.pl) && PrestaHome.com
* @license    You only can use module, nothing more!
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ph_recentposts extends Module
{
    
    public function __construct()
    {
        $this->name = 'ph_recentposts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.4';
        $this->author = 'www.PrestaHome.com';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->ps_versions_compliancy['min'] = '1.5.3';
        $this->ps_versions_compliancy['max'] = _PS_VERSION_;
        $this->secure_key = Tools::encrypt($this->name);

        if(!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog'))
            $this->warning = $this->l('You have to install and activate ph_simpleblog before use ph_recentposts');

        parent::__construct();

        $this->displayName = $this->l('Simple Blog - Recent posts');
        $this->description = $this->l('Widget to display recently added posts from PrestaHome SimpleBlog module');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
    }

    public function install()
    {

        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('displaySimpleBlogRecentPosts') 
                && $this->registerHook('hook_home_01') 
                && $this->registerHook('hook_home_02') 
                && $this->registerHook('hook_home_03') 
                && $this->registerHook('narrow_top') 
                && $this->registerHook('narrow_middle') 
                && $this->registerHook('narrow_bottom') 
                && $this->registerHook('displayHeader') 
                && $this->registerHook('displayHome') 
                && $this->registerHook('displayLeftColumn'));
    }

    public function prepareModuleSettings()
    {
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function preparePosts($nb = 4, $cat = null)
    {

        $featured = false;

        if($cat == 9999)
        {
            $cat = 0;
            $featured = true;
        }

        if(!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog'))
            return false;

        if(!isset($nb) || !isset($cat))
            return false;

        require_once _PS_MODULE_DIR_ . 'ph_simpleblog/models/SimpleBlogPost.php';

        $id_lang = $this->context->language->id;

        $posts = SimpleBlogPost::getPosts($id_lang, $nb, null, null, true, 'sbp.id_simpleblog_post', 'DESC', null, $featured);

        return $posts;
    }

    public function prepareSimpleBlogRecentPosts()
    {
        if(!$posts = $this->preparePosts(Configuration::get('PH_RECENTPOSTS_NB'), Configuration::get('PH_RECENTPOSTS_CAT')))
            return;
        
        $id_lang = $this->context->language->id;

        $gridType = Configuration::get('PH_BLOG_COLUMNS');
        $gridColumns = Configuration::get('PH_BLOG_GRID_COLUMNS');
        $blogLayout = Configuration::get('PH_RECENTPOSTS_LAYOUT');

        $gridHtmlCols = '';

        $mainTemplate = '';

        // First attempts to support 1.6
        if($gridType == 'prestashop')
        {
            $gridHtmlCols = 'ph_col ph_col_'.$gridColumns;
        } elseif($gridType == 'bootstrap')
        {
            $mainTemplate = '-bootstrap';
        }

        if($blogLayout == 'full')
        {
            $gridHtmlCols = 'ph_col';
        }

        $this->context->smarty->assign(array(
            'grid' => Configuration::get('PH_BLOG_COLUMNS'),
            'gridHtmlCols' => $gridHtmlCols,
            'module_dir' => _MODULE_DIR_.'ph_simpleblog/',
            'blogLayout' => $blogLayout,
            'posts' => $posts
        ));
    }

    public function hookDisplaySimpleBlogRecentPosts($params)
    {
        $this->prepareSimpleBlogRecentPosts();
        
        if(isset($params['template']))
             return $this->display(__FILE__, $params['template'].'.tpl');
        else
            return $this->hookDisplayHome($params);
    }

    public function hookhook_home_01($params)
    {
        $status = $this->getModuleState("hook_home_01");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            //return $this->display(__FILE__, 'recent.tpl', $this->getCacheId($this->name));
                return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hookhook_home_02($params)
    {
        $status = $this->getModuleState("hook_home_02");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
         //   if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
           return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hookhook_home_03($params)
    {
        $status = $this->getModuleState("hook_home_03");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hooknarrow_top($params)
    {
        $status = $this->getModuleState("narrow_top");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hooknarrow_middle($params)
    {
        $status = $this->getModuleState("narrow_middle");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hooknarrow_bottom($params)
    {
        $status = $this->getModuleState("narrow_bottom");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hookdisplayHome($params)
    {
        $status = $this->getModuleState("displayHome");
        if (($status == 1) && ($this->context->controller->php_self == "index")) {
            //if (!$this->isCached('recent.tpl', $this->getCacheId($this->name)))               
                $this->prepareSimpleBlogRecentPosts();
            return $this->display(__FILE__, 'recent.tpl');
        }
    }

    public function hookDisplayLeftColumn($params)
    {
        return;
    }

    protected function getCacheId($name = null)
    {
        return parent::getCacheId($name.'|'.date('Ymd'));
    }

    public function getModuleState($hook)   {  // get options from database
        if (!$sett = Db::getInstance()->ExecuteS('SELECT value FROM `'._DB_PREFIX_.'pk_theme_settings_hooks` WHERE hook = "'.$hook.'" AND module = "'.$this->name.'" AND id_shop = '.(int)$this->context->shop->id.';')) 
            return false;       
        return $sett[0]["value"];
    }

}
