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
    protected static $arrCareGroups =  array('washing','bleaching','drying','ironing','cleaning'); 
    protected static $arrLaundryCareSymbols =  array('washing' => array("wh-washing","wh-washing-hand","wh-washing-30deg") ,
                                                    'bleaching' => array("wh-bleaching","wh-bleaching-non-chlorine","wh-bleaching-not-allowed"),    
                                                    'drying' => array("wh-drying","wh-drying-tumble-low-heat","wh-drying-tumble-medium-heat","wh-drying-not-allowed"),
                                                    'ironing' => array("wh-iron","wh-iron-low","wh-iron-medium","wh-iron-not-allowed"),
                                                    'cleaning' => array("wh-drycleaning","wh-drycleaning-a","wh-drycleaning-f"));
	public function __construct()
	{
		$this->name = 'laundrycaresymbols';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Viktor Nesterenko';
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
        // for debug temp return
        return array("wh-washing","wh-bleaching","wh-iron-medium","wh-drycleaning-a","wh-drying-not-allowed");
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
        return $this->display(__FILE__, 'product-tab-content.tpl');
    }

    public function hookDisplayAdminProductsExtra()
    {
        $product = new Product(Tools::getValue('id_product'), false, $this->context->cookie->id_lang);
        $selected_symbols = array();
       
        $this->context->smarty->assign(array(
            'product' => $product,
            'allLaundryCareSymbols' => $arrLaundryCareSymbols,
            'module_path' => $this->_path
        ));
        
        return $this->display(__FILE__, 'admin-tab.tpl');
    }

    public function hookBackOfficeHeader()
    {
        //$this->context->controller->addJS($this->_path.'js/back.js');
        $this->context->controller->addCSS($this->_path.'css/styles.css');
        $this->context->controller->addCSS($this->_path.'css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        //$this->context->controller->addJS($this->_path.'/js/front.js');
        $this->context->controller->addCSS($this->_path.'css/styles.css');
        $this->context->controller->addCSS($this->_path.'/css/front.css');
    }

}