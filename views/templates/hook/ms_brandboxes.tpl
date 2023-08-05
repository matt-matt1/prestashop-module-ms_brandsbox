{**
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
{*debug*}

{*block name='content'}
  <section id="main">

    {block name='brand_header'}
      <h1>{l s='Brands' d='Shop.Theme.Catalog'}</h1>
    {/block}

    {block name='brand_miniature'}
      <ul>
        {foreach from=$brands item=brand}
          {include file='catalog/_partials/miniatures/brand.tpl' brand=$brand}
        {/foreach}
      </ul>
    {/block}

  </section>

{/block*}
{*<div class="brandboxes-section">*}
<section class="brandboxes clearfix md-{$columns}">
	<div class="container">
		{if $title}
		{*<div class="title">*}
			<h4>
				{if $display_title_link}<a{if page_link} href="{$page_link}"{/if}{if $title} title="{l s=$title d='Modules.Brandboxes.Shop'}"{/if}>{/if}
					{if $title}{l s=$title d='Modules.Brandboxes.Shop'}{/if}
				{if $display_title_link}</a>{/if}
			</h4>
		{*</div>*}
		{/if}
		{*<div class="brand-content">*}
			<div class="brands">
				{if $brands}
{include file="module:ms_brandboxes/views/templates/front/$brand_display_type.tpl" brands=$brands}
				{*else}
					<p>{l s='No brand' d='Modules.Brandboxes.Shop'}</p>*}
				{/if}
			</div>
		{*</div>*}
	</div>
</div>
