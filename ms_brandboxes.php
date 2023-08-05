<?php
/**
 * 2007-2023 Yuma Technical Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    Yuma Technical Contributors <yumatechnical@gmail.com>
 * @copyright 2007-2023 Yuma Technical Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * Registered Trademark & Property of Yuma Technical Contributors
 */
if (!defined('_PS_VERSION_')) {
	exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class Ms_BrandBoxes extends Module implements WidgetInterface
{
	private $templateFile;

	private	$replace_vars = array("%name%", "%description%", "%short_description%", "%meta_title%", "%meta_description%", "%date_added%", "%date_updated%");

	private const MOD = 'Modules.Brandboxes.Admin';

	private $vars = array(	//%s/$this->vars/$this->vars
	//const VARS = array(
		'BRANDBOXES_NBR' => array(
//			'name' => 'BRANDBOXES_NBR',
			'default' => 8,
			'type' => 'int',
			'text' => 'Number of brands',
			'required' => true,
		),
		'BRANDBOXES_COLS' => array(
//			'name' => 'BRANDBOXES_COLS',
			'default' => 1,
			'type' => 'int',
			'text' => 'Number of columns',
		),
		'BRANDBOXES_SHOWACT' => array(
//			'name' => 'BRANDBOXES_SHOWACT',
			'default' => true,
			'type' => 'switch',
			'text' => 'Show active',
			'required' => true,
		),
		'BRANDBOXES_SHOWNAME' => array(
//			'name' => 'BRANDBOXES_SHOWNAME',
			'default' => true,
			'type' => 'switch',
			'text' => 'Show brand name',
			'required' => true,
		),
		'BRANDBOXES_SHOWDESC' => array(
//			'name' => 'BRANDBOXES_SHOWDESC',
			'default' => false,
			'type' => 'switch',
			'text' => 'Show brand description',
			'required' => true,
		),
		'BRANDBOXES_SHOWSHORT' => array(
//			'name' => 'BRANDBOXES_SHOWSHORT',
			'default' => false,
			'type' => 'switch',
			'text' => 'Show brand short description',
			'required' => true,
		),
		'BRANDBOXES_SHOWMETAT' => array(
//			'name' => 'BRANDBOXES_SHOWMETAT',
			'default' => false,
			'type' => 'switch',
			'text' => 'Show brand meta title',
			'required' => true,
		),
		'BRANDBOXES_SHOWMETAD' => array(
//			'name' => 'BRANDBOXES_SHOWMETAD',
			'default' => false,
			'type' => 'switch',
			'text' => 'Show brand meta description',
			'required' => true,
		),
		'BRANDBOXES_ONHOVER' => array(
//			'name' => 'BRANDBOXES_ONHOVER',
			'default' => '%description%',
			'type' => 'text',
			'text' => 'When mouse pointer hovers over',
			'variables' => array("%name%", "%description%", "%short_description%", "%meta_title%", "%meta_description%", "%date_added%", "%date_updated%"),
		),
		'BRANDBOXES_ORDERBY' => array(
//			'name' => 'BRANDBOXES_ORDERBY',
			'default' => 'position',
			'type' => 'select',
			'options' => array(/*'name', 'description', 'short_description', 'meta_title', 'meta_description', 'random', 'date_added', 'date_updated'),*/
					array(
			 			'id' => 'position',
						'name' => 'position'
					),
					array(
			 			'id' => 'name',
						'name' => 'brand name'
					),
					array(
						'id' => 'description',
						'name' => 'brand description'
					),
					array(
						'id' => 'short_desc',
						'name' => 'short description',
					),
					array(
						'id' => 'meta_title',
						'name' => 'meta title',
					),
					array(
						'id' => 'meta_desc',
						'name' => 'meta description',
					),
					array(
						'id' => 'random',
						'name' => 'random',
					),
					array(
						'id' => 'date_added',
						'name' => 'date added',
					),
					array(
						'id' => 'date_updated',
						'name' => 'date updated',
					),
				),
			'text' => 'Order brands using',
//			'required' => true,
		),
/* name, position, random, date added, date updated */
		'BRANDBOXES_ORDER' => array(
//			'name' => 'BRANDBOXES_ORDER',
			'default' => true,
			'type' => 'switch',
			'text' => 'Ascending order',
			'required' => true,
		),
/* true = ASC (ascending order) ; false = DESC (descending order) */
		'BRANDBOXES_TITLE' => array(
//			'name' => 'BRANDBOXES_TITLE',
			'default' => 'Brands',
			'type' => 'text',
			'text' => 'Show section title',
		),
		'BRANDBOXES_TITLELINK' => array(
//			'name' => 'BRANDBOXES_TITLELINK',
			'default' => true,
			'type' => 'switch',
			'text' => 'Link for section title',
		),
		'BRANDBOXES_STYLE' => array(
//			'name' => 'BRANDBOXES_STYLE',
			'default' => 'brand_image',
			'type' => 'select',
			'text' => 'Show list as',
			'desc' => 'Select the option to sort each brand (default: "brand name")',
			'required' => true,
		),
		'BRANDBOXES_LINKS' => array(
//			'name' => 'BRANDBOXES_LINKS',
			'default' => true,
			'type' => 'switch',
			'text' => 'Link each brand',
			'required' => true,
		),
	);

	public function __construct()
	{
		$this->name = 'ms_brandboxes';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Matthew Scott';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = [
			'min' => '1.7.1.0',
			'max' => _PS_VERSION_,
		];
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->trans('Brand boxes', [], 'Modules.Brandboxes.Admin');
		$this->description = $this->trans('Displays brands in linkable boxes.', [], 'Modules.Brandboxes.Admin');

		$this->templateFile = 'module:ms_brandboxes/views/templates/hook/ms_brandboxes.tpl';
	}

	public function reset()
	{
		$this->_clearCache('*');

		foreach($this->vars as $var) {
			Configuration::updateValue($var, $var['default']);
		}
	}

	public function install()
	{
		$this->reset();

		return parent::install()
			&& $this->registerHook('displayLeftColumn')
			&& $this->registerHook('displayRightColumn')
			&& $this->registerHook('actionObjectManufacturerDeleteAfter')
			&& $this->registerHook('actionObjectManufacturerAddAfter')
			&& $this->registerHook('actionObjectManufacturerUpdateAfter')
//			&& $this->registerHook('actionProductAdd')
//			&& $this->registerHook('actionProductUpdate')
//			&& $this->registerHook('actionProductDelete')
//			&& $this->registerHook('displayHome')
//			&& $this->registerHook('displayOrderConfirmation2')
//			&& $this->registerHook('actionCategoryUpdate')
//			&& $this->registerHook('actionAdminGroupsControllerSaveAfter')
//			&& $this->registerHook('displayBackOfficeHeader')
			&& $this->registerHook('actionFrontControllerSetMedia')
		;
	}

	public function uninstall()
	{
		$this->_clearCache('*');

		return parent::uninstall()
			&& Configuration::deleteByName('BRANDBOXES_NBR')
			&& Configuration::deleteByName('BRANDBOXES_COLS')
			&& Configuration::deleteByName('BRANDBOXES_SHOWACT')
			&& Configuration::deleteByName('BRANDBOXES_SHOWNAME')
			&& Configuration::deleteByName('BRANDBOXES_SHOWDESC')
			&& Configuration::deleteByName('BRANDBOXES_SHOWSHORT')
			&& Configuration::deleteByName('BRANDBOXES_SHOWMETAT')
			&& Configuration::deleteByName('BRANDBOXES_SHOWMETAD')
			&& Configuration::deleteByName('BRANDBOXES_ONHOVER')
			&& Configuration::deleteByName('BRANDBOXES_ORDERBY')
			&& Configuration::deleteByName('BRANDBOXES_ORDER')
			&& Configuration::deleteByName('BRANDBOXES_TITLE')
			&& Configuration::deleteByName('BRANDBOXES_TITLELINK')
			&& Configuration::deleteByName('BRANDBOXES_STYLE')
			&& Configuration::deleteByName('BRANDBOXES_LINKS')
		;
	}

	public function hookActionObjectManufacturerUpdateAfter($params)
	{
		$this->_clearCache('*');
	}

	public function hookActionObjectManufacturerAddAfter($params)
	{
		$this->_clearCache('*');
	}

	public function hookActionObjectManufacturerDeleteAfter($params)
	{
		$this->_clearCache('*');
	}

	public function _clearCache($template, $cache_id = null, $compile_id = null)
	{
		parent::_clearCache($this->templateFile);
	}
/*
	public function hookDisplayBackOfficeHeader()
	{
		// Use addCss : registerStylesheet is only for front controller.
		$this->context->controller->addCss(
			$this->_path.'views/admin/css/'. $this->name. '.css'
		);
	}
*/
	public function hookActionFrontControllerSetMedia()
	{
		$this->context->controller->registerStylesheet(
			'mymodule-style',
			'modules/' . $this->name . '/views/css/' . $this->name . '.css',
			[
				'media' => 'all',
				'priority' => 1000,
			]
		);
/*		
		$this->context->controller->registerJavascript(
			'mymodule-javascript',
			'modules/' . $this->name . '/views/js/' . $this->name . '.js',
			[
				'position' => 'bottom',
				'priority' => 1000,
			]
		);*/
	}

	public function getContent()
	{
		$output = '';
		$errors = [];
		$values = [];

		if (Tools::isSubmit('submitBrandboxes')) {
			foreach($this->vars as $k => $v) {
				if (!isset($v['required']) || !$v['required']) {
					continue;
				}
				$values[$k] = Tools::getValue($k);
//				$errors[] = $this->trans('Output: '. print_r($v, true), [], self::MOD);
				switch($v['type']) {
				case 'int':
					if (!Validate::isInt($values[$k]) || $values[$k] <= 0) {
						$errors[] = $this->trans('Please enter a positive number for "'. $v['text']. '".', [], self::MOD);
					}
					break;
				case 'switch':
					if (!Validate::isBool($values[$k])) {
						$errors[] = $this->trans('Invalid value for "'. $v['text']. '" flag.', [], self::MOD);
					}
					break;
				case 'select':
					if (!isset($k) || (isset($v['options']) && !in_array($values[$k], $v['options']))) {
						$more = '';
//						if (isset($v['options']) && is_array($v['options'])) {
//						}
						if (isset($v['variables']) && is_array($v['variables'])) {
							$mustbe = implode(',', array_filter($v['variables'], function($k) {
								return $k == 'id';
							}, ARRAY_FILTER_USE_KEY));
							$more = $this->trans(' Must be one of '. $mustbe. '.', array(), self::MOD);
						}
						$errors[] = $this->trans('Invalid value for "'. $v['text']. '".'. $more, [], self::MOD);
					}
					break;
				//case 'text':
				default:
//					if (!Validate::isUnsignedInt($values[$k])) {
					if (!isset($values[$k]) || empty($value[$k])) {
						$errors[] = $this->trans('Invalid or missing value for "'. $v['text']. '".', [], self::MOD);
					}
					break;
				}
			}
			if (count($errors)) {
				$output = $this->displayError(implode('<br />', $errors));
			} else {
				foreach($values as $k => $v) {
					Configuration::updateValue($k, $v);
				}

				$this->_clearCache('*');

				$output = $this->displayConfirmation($this->trans('The settings have been updated.', [], 'Admin.Notifications.Success'));
			}
		}

		return $output . $this->renderForm();
	}

	public function renderForm()
	{
		$fields_form = [
			'form' => [
				'legend' => [
					'title' => $this->trans('Settings', [], 'Admin.Global'),
					'icon' => 'icon-cogs',
				],

//				'description' => $this->trans('To add products to your homepage, simply add them to the corresponding product category (default: "Home").', [], 'Modules.Brandboxes.Admin'),
/*				'input' => array_filter($this->vars, function($v, $k) {
					return array('name' => $k, 'query' => array(), 'value' => array(), );
				}, ARRAY_FILTER_USE_BOTH),
/*					[
						'type' => 'categories',
						'tree' => [
						  'id' => 'home_featured_category',
						  'selected_categories' => [Configuration::get('BRANDBOXES_CAT')],
						],
						'label' => $this->trans('Category from which to pick products to be displayed', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_CAT',
					],*/
/*array(
								array(
						 			'id' => 'position',
									'name' => $this->trans('position', array(), 'Modules.Brandlist.Admin'),
								),
								array(
						 			'id' => 'name',
									'name' => $this->trans('brand name', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'description',
									'name' => $this->trans('brand description', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'short_desc',
									'name' => $this->trans('short description', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'meta_title',
									'name' => $this->trans('meta title', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'meta_desc',
									'name' => $this->trans('meta description', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'random',
									'name' => $this->trans('random', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'date added',
									'name' => $this->trans('date added', array(), 'Modules.Brandlist.Admin'),
								),
								array(
									'id' => 'date updated',
									'name' => $this->trans('date updated', array(), 'Modules.Brandlist.Admin'),
								),
							),*/
/**/				'input' => [
					[
						'type' => 'select',
						'name' => 'BRANDBOXES_STYLE',
						'label' => $this->trans('Display brands list with each', [], 'Modules.Brandboxes.Admin'),
						'required' => true,
						'desc' => $this->trans('Select the option to sort each brand (default: "brand name")', [], 'Modules.Brandboxes.Admin'),
						'options' => array(
							'query' => array(
								array(
									'id' => 'brand_image',
									'name' => $this->trans('brand image', array(), self::MOD),
								),
								array(
									'id' => 'brand_text',
									'name' => $this->trans('brand name', array(), self::MOD),
								),
							),
							'id' => 'id',
							'name' => 'name',
						),
						'class' => 'fixed-width-xs',
					],
					[
						'type' => 'text',
						'label' => $this->trans('Number of brands to be displayed', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_NBR',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Set the number of brands that you would like to display on homepage (default: 8).', [], 'Modules.Brandboxes.Admin'),
					],
					[
						'type' => 'text',
						'label' => $this->trans('Number of colunms to be displayed', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_COLS',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Set the number of colunms that you would like to display on homepage (default: 1).', [], 'Modules.Brandboxes.Admin'),
					],
					[
						'type' => 'text',
						'label' => $this->trans('Biock title', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_TITLE',
						'class' => 'fixed-width-md',
						'desc' => $this->trans('Set the text to display before the brands (default: "Brands").', [], 'Modules.Brandboxes.Admin'),
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Link the brands title to brands page', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_TITLELINK',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish to have the brands title link to the all brands page (default: Yes).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display only if active', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWACT',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brands to be displayed if active (default: Yes).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display the brand name', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWNAME',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand name to be displayed (default: Yes).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display the description', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWDESC',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand description to be displayed (default: No).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display the short description', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWSHORT',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand short description to be displayed (default: No).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display the meta title', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWMETAT',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand meta title to be displayed (default: No).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display the meta description', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_SHOWMETAD',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand meta description to be displayed (default: No).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Link each brand', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_LINKS',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brand to link to the brand page (default: Yes).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'select',
						'name' => 'BRANDBOXES_ORDERBY',
						'label' => $this->trans('Orderby', [], 'Modules.Brandboxes.Admin'),
//						'required' => true,
						'desc' => $this->trans('Select the option to sort each brand (default: "brand name")', [], 'Modules.Brandboxes.Admin'),
						'options' => array(
							'query' => array_filter($this->vars['BRANDBOXES_ORDERBY']['options'], function($v, $k) {
								return array('name' => $v['name'], 'id' => $this->trans($v['id'], array(), self::MOD));
							}, ARRAY_FILTER_USE_BOTH),
							'id' => 'id',
							'name' => 'name',
						),
						'class' => 'fixed-width-xs',
					],
					[
						'type' => 'switch',
						'label' => $this->trans('Display in ascending order', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_ORDER',
						'class' => 'fixed-width-xs',
						'desc' => $this->trans('Enable if you wish the brands to be sorted in ascending order (default: Yes).', [], 'Modules.Brandboxes.Admin'),
						'values' => [
							[
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->trans('Yes', [], 'Admin.Global'),
							],
							[
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->trans('No', [], 'Admin.Global'),
							],
						],
					],
					[
						'type' => 'text',
						'label' => $this->trans('Display on hover', [], 'Modules.Brandboxes.Admin'),
						'name' => 'BRANDBOXES_ONHOVER',
						'class' => 'fixed-width-md',
						'desc' => $this->trans('Set the text to display on homepage when the mouse pointer hovers over the box (default: "%description%"; use "%name%", "%description%", "%short_description%", "%meta_title%", "%meta_description%", "%date_added%" and or "%date_updated%").', [], 'Modules.Brandboxes.Admin'),
					],
				],/**/
				'submit' => [
					'title' => $this->trans('Save', [], 'Admin.Actions'),
					'name' => 'save',
				],
				'reset' => [
					'title' => $this->trans('Reset', [], 'Admin.Actions'),
					'name' => 'reset',
					'icon' => 'icon-reset',
					'class' => '',
				],
			],
		];

		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBrandboxes';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = [
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
		];

		return $helper->generateForm([$fields_form]);
	}

	public function getConfigFieldsValues()
	{
		$ret = array();
		foreach($this->vars as $k => $v) {
			$ret[$k] = Tools::getValue($k, Configuration::get($k));
		}
		return $ret;
	}

	public function renderWidget($hookName = null, array $configuration = [])
	{
		if (!$this->isCached($this->templateFile, $this->getCacheId('ms_brandboxes'))) {
			$variables = $this->getWidgetVariables($hookName, $configuration);

			if (empty($variables)) {
				return false;
			}

			$this->smarty->assign($variables);
		}

		return $this->fetch($this->templateFile, $this->getCacheId('ms_brandboxes'));
	}

	public function getWidgetVariables($hookName = null, array $configuration = [])
	{
		$brands = Manufacturer::getManufacturers(
			false,
			(int) Context::getContext()->language->id,
			$active = Tools::getValue('BRANDBOXES_SHOWACT', (bool) Configuration::get('BRANDBOXES_SHOWACT')),//true,
			$p = false,
			$n = false,
			$allGroup = false,
			$group_by = false,
			$withProduct = true
		);
		
		if (!empty($brands)) {
			foreach ($brands as &$brand) {
				$brand['image'] = $this->context->language->iso_code . '-default';
				$brand['link'] = $this->context->link->getManufacturerLink($brand['id_manufacturer']);
				$fileExist = file_exists(
				    _PS_MANU_IMG_DIR_ . $brand['id_manufacturer'] . '-' .
				    ImageType::getFormattedName('medium') . '.jpg'
				);
				$brand['imageurl'] = $this->context->link->getManufacturerImageLink($brand['id_manufacturer']);
/* TODO */				$brand['meta_title'] = '';
/* TODO */				$brand['meta_description'] = '';
				$find_these = $this->vars['BRANDBOXES_ONHOVER']['variables'];
				$replacements = array($brand['name'], $brand['description'], $brand['short_description'], $brand['meta_title'], $brand['meta_description'], $brand['date_add'], $brand['date_upd']);
				$str = Configuration::get('BRANDBOXES_ONHOVER');
				$brand['onhover'] = str_replace($find_these, $replacements, $str);
				
				if ($fileExist) {
				    $brand['image'] = $brand['id_manufacturer'];
				}
			}
		}
		
		$retn = array(
			'brands' => $brands,
			'page_link' => $this->context->link->getPageLink('manufacturer'),
			'text_list_nb' => Configuration::get('BRANDBOXES_NBR'),
			'brand_display_type' => Configuration::get('BRANDBOXES_STYLE'),
			'order_brands' => Configuration::get('BRANDBOXES_ORDERBY'),
			'asc_order' => Configuration::get('BRANDBOXES_ORDER'),
			'display_link_brand' => Configuration::get('PS_DISPLAY_MANUFACTURERS'),
			'columns' => Configuration::get('BRANDBOXES_COLS'),
			'show_name' => Configuration::get('BRANDBOXES_SHOWNAME'),
			'show_description' => Configuration::get('BRANDBOXES_SHOWDESC'),
			'show_short' => Configuration::get('BRANDBOXES_SHOWSHORT'),
			'show_meta_title' => Configuration::get('BRANDBOXES_SHOWMETAT'),
			'show_meta_description' => Configuration::get('BRANDBOXES_SHOWMETAD'),
			'onhover' => Configuration::get('BRANDBOXES_ONHOVER'),
			'display_title_link' => Configuration::get('BRANDBOXES_TITLELINK'),
			'title' => Configuration::get('BRANDBOXES_TITLE'),
			'display_links' => Configuration::get('BRANDBOXES_LINKS')
		);
		return $retn;
	}

	protected function getManufacturer($id_manufacturer)
	{
		$manufacturer = new Manufacturer($id_manufacturer, $id_lang);
		if (Validate::isLoadedObject($manufacturer)) {
			echo '<pre>'. print_r($manufacturer, true). '</pre>';
		}
	}

	protected function getAction()
	{
		return Tools::getValue('action');
	}

	protected function getRoute()
	{
		return \PrestaShop\PrestaShop\Adapter\SymfonyContainer::getInstance()
			->get('request_stack')
			->getMasterRequest()
			->get('_route');
	}

	protected function getCacheId($name = null)
	{
		$cacheId = parent::getCacheId($name);
		if (!empty($this->context->customer->id)) {
			$cacheId .= '|' . $this->context->customer->id;
		}

		return $cacheId;
	}
}
