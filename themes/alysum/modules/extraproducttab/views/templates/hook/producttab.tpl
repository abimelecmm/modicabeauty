{*
* 2007-2015 mitsos1os
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
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize the module for your
* needs please refer to
*
*  @author mitsos1os
*  @copyright  2014-2015 mitsos1os
*}
{foreach from = $productTabs item = productTab}
    <h3 data-title="extraTab_{$productTab['id_Tab']|intval}" >{$productTab['displayname']|escape:'htmlall':'UTF-8'}</h3>
{/foreach}