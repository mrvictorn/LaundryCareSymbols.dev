<?php
/**
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class LaundryCareSymbols extends Module
{
	protected $config_form = false;

	public function __construct()
	{
		$this->name = 'laundrycaresymbols';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Media Atlantis Group';
		$this->need_instance = 1;

		/**
		 * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
		 */
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Laundry Care Symbols');
		$this->description = $this->l('Laundry Care Symbols for apparel products');

		$this->confirmUninstall = $this->l('');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	/**
	 * Don't forget to create update methods if needed:
	 * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
	 */
public function install()
    {
        // Hooks & Install
        return (parent::install() 
                && $this->prepareModuleSettings() 
                && $this->registerHook('actionObjectProductDeleteAfter') 
                && $this->registerHook('productTabContent') 
                && $this->registerHook('productTab') 
                && $this->registerHook('displayAdminProductsExtra') 
                && $this->registerHook('displayBackOfficeHeader');// todo: add css to header frontoffice!
    }

    public function prepareModuleSettings()
    {
        // Database
        $sql = array();
        include (dirname(__file__) . '/sql/install.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        // Database
        $sql = array();
        include (dirname(__file__) . '/sql/uninstall.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }

       
        return true;
    }

    private function cleanForProduct($productId)
    {
    	//todo: delete from table l_care records for $productId
    }

    private function getSymbolsForProduct($productId)
    {
    	//todo: return array of symbols codes  for $productId
    }

    private function setSymbolsForProduct($productId,$arrSymbolsCodes)
    {
    	//todo: return true if success on save $arrSymbolsCodes for $productId
    }


    public function hookActionObjectProductDeleteAfter($params)
    {
        return $this->cleanForProduct($params['object']->id);
    }

    


    public function hookProductTab($params)
    {
        $id_product = Tools::getValue('id_product');
        return $this->display(__FILE__, 'product-tab.tpl');
    }

    public function hookProductTabContent($params)
    {
        $id_product = Tools::getValue('id_product');

        $this->context->smarty->assign(array(
            'posts' => $posts,
            'is_16' => (bool)(version_compare(_PS_VERSION_, '1.6.0', '>=') === true),
            'blogLayout' => Configuration::get('PH_BLOG_LAYOUT')
        ));

        return $this->display(__FILE__, 'product-tab-content.tpl');
    }

    public function hookDisplayAdminProductsExtra()
    {
        
        if(!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog'))
            return;

        $product = new Product(Tools::getValue('id_product'), false, $this->context->cookie->id_lang);

        $posts = SimpleBlogPost::getSimplePosts($this->context->language->id);

        $selected_posts = array();
        $related_posts = array();

        foreach(SimpleBlogRelatedPost::getByProductId($product->id) as $key => $post)
        {
            $related_posts[] = $post['id_simpleblog_post'];
        }

        if(sizeof($related_posts) > 0)
        {
            $posts = SimpleBlogPost::getSimplePosts($this->context->language->id, null, null, 'NOT IN', $related_posts);
            $selected_posts = SimpleBlogPost::getSimplePosts($this->context->language->id, null, null, 'IN', $related_posts);
        }

        $this->context->smarty->assign(array(
            'product' => $product,
            'posts' => $posts,
            'selected_posts' => $selected_posts,
            'module_path' => $this->_path,
            'secure_key' => $this->secure_key,
            'is_16' => (bool)(version_compare(_PS_VERSION_, '1.6.0', '>=') === true)
        ));
        
        return $this->display(__FILE__, 'admin-tab.tpl');
    }

}