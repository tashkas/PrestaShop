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

<!-- Block suppliers module -->
<div id="suppliers_block_left" class="block blocksupplier">
	<h4><a href="{$link->getPageLink('supplier.php')}" title="{l s='Suppliers' mod='blocksupplier'}">{l s='Suppliers' mod='blocksupplier'}</a></h4>
	<div class="block_content">
{if $suppliers}
	{if $text_list}
	<ul class="bullet">
	{foreach from=$suppliers item=supplier name=supplier_list}
		{if $smarty.foreach.supplier_list.iteration <= $text_list_nb}
		<li class="{if $smarty.foreach.supplier_list.last}last_item{elseif $smarty.foreach.supplier_list.first}first_item{else}item{/if}">
			<a href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)}" title="{l s='More about' mod='blocksupplier'} {$supplier.name}">{$supplier.name|escape:'htmlall':'UTF-8'}</a>
		</li>
		{/if}
	{/foreach}
	</ul>
	{/if}
	{if $form_list}
		<form action="{$smarty.server.SCRIPT_NAME}" method="get">
			<p>
				<select id="supplier_list" onchange="autoUrl('supplier_list', '');">
					<option value="0">{l s='All suppliers' mod='blocksupplier'}</option>
				{foreach from=$suppliers item=supplier}
					<option value="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)}">{$supplier.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
				</select>
			</p>
		</form>
	{/if}
{else}
	<p>{l s='No supplier' mod='blocksupplier'}</p>
{/if}
	</div>
</div>
<!-- /Block suppliers module -->
