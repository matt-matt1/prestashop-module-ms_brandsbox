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

<ul>
	{foreach from=$brands item=brand name=brand_list}
		{if $smarty.foreach.brand_list.iteration <= $text_list_nb}
			<li>
				<a{if $brand['link']} href="{$brand['link']}"{/if}{if $brand['name']} title="{$brand['name']}"{/if}>
					{if $brand['name']}{$brand['name']}{/if}
				</a>
			</li>
		{/if}
	{/foreach}
</ul>
