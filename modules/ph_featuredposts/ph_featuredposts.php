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

class ph_featuredposts extends Module
{
    public function __construct()
    {
        $this->name = 'ph_featuredposts';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'www.PrestaHome.com';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->ps_versions_compliancy['min'] = '1.5.3.1';
        $this->ps_versions_compliancy['max'] = '1.6.1.0';
        $this->secure_key = Tools::encrypt($this->name);

        if(!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog'))
            $this->warning = $this->l('You have to install and activate ph_simpleblog before use ph_featuredposts');

        parent::__construct();

        $this->displayName = $this->l('Simple Blog - Featured posts');
        $this->description = $this->l('Widget to display featured posts from PrestaHome SimpleBlog module');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
    }

    public function install()
    {
        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('displaySimpleBlogRecentPosts') 
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

    public function preparePosts($nb = 4, $cat = 0)
    {
        $featured = true;

        if(!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog'))
            return false;

        if(!isset($nb) || !isset($cat))
            return false;

        require_once _PS_MODULE_DIR_ . 'ph_simpleblog/models/SimpleBlogPost.php';

        $id_lang = $this->context->language->id;

        $posts = SimpleBlogPost::getPosts($id_lang, $nb, $cat, null, true, 'sbp.date_add', 'DESC', null, $featured);

        return $posts;
    }

    public function prepareSimpleBlogRecentPosts()
    {
        if(!$posts = $this->preparePosts(Configuration::get('ph_featuredposts_NB')))
            return;
        
        $id_lang = $this->context->language->id;

        $gridColumns = Configuration::get('ph_featuredposts_GRID_COLUMNS');
        $blogLayout = Configuration::get('ph_featuredposts_LAYOUT');

        $this->context->smarty->assign(array(
            'blogLayout' => $blogLayout,
            'columns' => $gridColumns,
            'gallery_dir' => _MODULE_DIR_.'ph_simpleblog/galleries/',
            'recent_posts' => $posts,
            'tpl_path' => dirname(__FILE__).'/views/templates/hook/',
        ));
    }

    public function hookDisplaySimpleBlogRecentPosts($params)
    {
        $this->prepareSimpleBlogRecentPosts();
        return $this->hookDisplayHome($params);
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->prepareSimpleBlogRecentPosts();
        return $this->display(__FILE__, 'featuredposts.tpl');
    }

}