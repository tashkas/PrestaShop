{*
* 2007-2010 PrestaShop 
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
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Prestashop SA <contact@prestashop.com>
*  @copyright  2007-2010 Prestashop SA : 6 rue lacepede, 75005 PARIS
*  @version  Release: $Revision: 1.4 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registred Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

<div id="mywishlist">
	{capture name=path}<a href="{$link->getPageLink('my-account.php', true)}">{l s='My account' mod='blockwishlist'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='My wishlists' mod='blockwishlist'}{/capture}
	{include file="$tpl_dir./breadcrumb.tpl"}

	<h2>{l s='My wishlists' mod='blockwishlist'}</h2>

	{include file="$tpl_dir./errors.tpl"}

	{if $id_customer|intval neq 0}
		<form action="{$base_dir_ssl}modules/blockwishlist/mywishlist.php" method="post" class="std">
			<fieldset>
				<h3>{l s='New wishlist' mod='blockwishlist'}</h3>
				<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
				<label class="align_right" for="name">{l s='Name' mod='blockwishlist'}</label>
				<input type="text" id="name" name="name" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'htmlall':'UTF-8'}{/if}" />
				<input type="submit" name="submitWishlist" id="submitWishlist" value="{l s='Save' mod='blockwishlist'}" class="exclusive" />
			</fieldset>
		</form>
		{if $wishlists}
		<div id="block-history" class="block-center">
			<table class="std">
				<thead>
					<tr>
						<th class="first_item">{l s='Name' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Qty' mod='blockwishlist'}</th>
						<th class="item mywishlist_first">{l s='Viewed' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Created' mod='blockwishlist'}</th>
						<th class="item mywishlist_second">{l s='Direct Link' mod='blockwishlist'}</th>
						<th class="last_item mywishlist_first">{l s='Delete' mod='blockwishlist'}</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$wishlists}
					<tr id="wishlist_{$wishlists[i].id_wishlist|intval}">
						<td class="bold" style="width:200px;"><a href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a></td>
						<td class="bold align_center">
						{assign var=n value=0}
						{foreach from=$nbProducts item=nb name=i}
							{if $nb.id_wishlist eq $wishlists[i].id_wishlist}
								{assign var=n value=$nb.nbProducts|intval}
							{/if}
						{/foreach}
						{if $n}
							{$n|intval}
						{else}
							0
						{/if}
						</td>
						<td class="align_center">{$wishlists[i].counter|intval}</td>
						<td class="align_center">{$wishlists[i].date_add|date_format:"%Y-%m-%d"}</td>
						<td class="align_center"><a href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{l s='View' mod='blockwishlist'}</a></td>
						<td class="align_center">
							<a href="javascript:;"onclick="return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}', '{l s='Do you really want to delete this wishlist ?' mod='blockwishlist'}'));"><img src="{$content_dir}modules/blockwishlist/img/icon/delete.png" alt="{l s='Delete' mod='blockwishlist'}" /></a>
						</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</div>
		<div id="block-order-detail">&nbsp;</div>
		{/if}
	{/if}

	<ul class="footer_links">
		<li><a href="{$link->getPageLink('my-account.php', true)}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account.php', true)}">{l s='Back to Your Account' mod='blockwishlist'}</a></li>
		<li><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home' mod='blockwishlist'}</a></li>
	</ul>
</div>
