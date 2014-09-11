<?php
/*
* 2007-2012 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class GSearch extends Module
{
	public function __construct()
	{
		$this->name = 'gsearch';
		$this->tab = 'search_filter';
		$this->version = 1.2;
		$this->author = 'jorgevrgs';
		$this->need_instance = 0;
		$this->controllers = array('search');

		parent::__construct();

		$this->displayName = $this->l('Google Search block');
		$this->description = $this->l('Adds a block with a quick search field.');
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayHome') || !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayMobileTopSiteMap') || !$this->registerHook('displayFooter'))
			return false;
		return true;
	}
	
	public function hookdisplayMobileTopSiteMap($params)
	{
		$this->smarty->assign(array(
			'hook_mobile' => true,
			'instantsearch' => false
		));
		return $this->hookTop($params);
	}
	
	/*
	public function hookDisplayMobileHeader($params)
	{
		if (Configuration::get('PS_SEARCH_AJAX'))
			$this->context->controller->addJqueryPlugin('autocomplete');
		$this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
	}
	*/

	public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'gsearch.css', 'all');
	}

	public function hookDisplayLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookDisplayRightColumn($params)
	{
		$this->calculHookCommon($params);
		$this->smarty->assign('gsearch_type', 'block');
		return $this->display(__FILE__, 'column.tpl');
	}

	public function hookDisplayTop($params)
	{
		$this->calculHookCommon($params);
		$this->smarty->assign('gsearch_type', 'top');
		return $this->display(__FILE__, 'top.tpl');
	}

	public function hookDisplayFooter($params)
	{
		return $this->display(__FILE__, 'footer.tpl');
	}

	public function hookDisplayHome($params)
	{
		return $this->display(__FILE__, 'home.tpl');
	}

	/**
	 * _hookAll has to be called in each hookXXX methods. This is made to avoid code duplication.
	 *
	 * @param mixed $params
	 * @return void
	 */
	private function calculHookCommon($params)
	{
		$this->smarty->assign(array(
			'ENT_QUOTES' =>		ENT_QUOTES,
			'search_ssl' =>		Tools::usingSecureMode(),
			'self' =>			dirname(__FILE__),
		));

		return true;
	}
}

