<?php
/*
* 2007-2011 PrestaShop 
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

function regenerate_level_depth()
{
	$category = new Category();
	$cats = $category->getSimpleCategories((int)Configuration::get('PS_LANG_DEFAULT'));
	foreach($cats as $cat)
	{
		$category = new Category((int)$cat['id_category']);
		// if the category has no parent, it's the home
		if ((int)$category->id_parent != 0)
		{
			$catParent = new Category((int)$category->id_parent);
			$category->level_depth = $catParent->level_depth +1;
			Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'category` SET `level_depth` = '.(int)$category->level_depth.' WHERE `id_category` = '.(int)$category->id);
		}
	}

	Category::regenerateEntireNtree();

}