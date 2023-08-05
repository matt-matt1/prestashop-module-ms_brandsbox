{*
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
 *}
{*
		array(
			'brands' => $brands,
			='page_link' => $this->context->link->getPageLink('manufacturer'),
			//'text_list_nb' => Configuration::get('BRAND_DISPLAY_TEXT_NB'),
			'text_list_nb' => Configuration::get('BRANDBOXES_NBR'),
			='brand_display_type' => Configuration::get('BRANDBOXES_STYLE'),
			'order_brands' => Configuration::get('BRANDBOXES_ORDERBY'),
			'asc_order' => Configuration::get('BRANDBOXES_ORDER'),
			//'brand_display_type' => 'brand_text',
			='display_link_brand' => Configuration::get('PS_DISPLAY_MANUFACTURERS'),
			='columns' => Configuration::get('BRANDBOXES_COLS'),
			//'active_only' => Configuration::get('BRANDBOXES_SHOWACT'),
			='show_name' => Configuration::get('BRANDBOXES_SHOWNAME'),
			='show_description' => Configuration::get('BRANDBOXES_SHOWDESC'),
			='show_short' => Configuration::get('BRANDBOXES_SHOWSHORT'),
			'show_meta_title' => Configuration::get('BRANDBOXES_SHOWMETAT'),
			'show_meta_description' => Configuration::get('BRANDBOXES_SHOWMETAD'),
			'onhover' => Configuration::get('BRANDBOXES_ONHOVER'),
			='display_title' => Configuration::get('BRANDBOXES_TITLELINK'),
			='title' => Configuration::get('BRANDBOXES_TITLE'),
			='display_links' => Configuration::get('BRANDBOXES_LINKS')
		);
    [brands] => Array
        (
            [0] => Array
                (
                    [id_manufacturer] => 1
                    [name] => Acer
                    [date_add] => 2023-07-30 20:01:42
                    [date_upd] => 2023-07-31 11:35:30
                    [active] => 1
                    [description] => 
                    [short_description] => 
                    [link_rewrite] => acer
                    [image] => 1
                    [link] => https://yumatechnical.com/pshop/brand/1-acer
                )
*}
<ul id="brandboxes-ul" class="brands-list">
	{foreach from=$brands item=brand name=brand_list}
		{if $smarty.foreach.brand_list.iteration <= $text_list_nb}
			<li class="item">
				{if $display_links}
				{*<a href="{$brand['link']}" title="{$brand['name']}">{/if}*}
				<a{if $brand['link']} href="{$brand['link']}"{/if}{if $onhover} title="{$onhover}"{/if}>{/if}
					<img{if $brand['imageurl']} src="{$brand['imageurl']}"{/if}{if $brand['name']} title="{$brand['name']}" alt="{$brand['name']}"{/if} />
					{if $show_name}<div class="name">{$brand['name']}</div>{/if}
					{if $show_description}<div class="description">{$brand['description']}</div>{/if}
					{if $show_short}<div class="short_description">{$brand['short_description']}</div>{/if}
					{if $show_meta_title}<div class="meta_title">{$brand['meta_title']}</div>{/if}
					{if $show_meta_description}<div class="meta_description">{$brand['meta_description']}</div>{/if}
				{if $display_links}
				</a>{/if}
			</li>
		{/if}
	{/foreach}
</ul>
