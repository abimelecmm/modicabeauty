{*
 * Copyright 2014 Lengow SAS.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *	 http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 *  @author	   Ludovic Drin <ludovic@lengow.com> Romain Le Polh <romain@lengow.com>
 *  @copyright 2014 Lengow SAS
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 *}
<!-- Tag_Lengow -->
{if $page_type == 'confirmation'}
	<script type="text/javascript">
	var page = 'payment';  // #TYPE DE PAGE#
	var order_amt = '{$order_total|escape:"htmlall":"UTF-8"}'; // #MONTANT COMMANDE#
	var order_id = '{$id_order|escape:"htmlall":"UTF-8"}'; // #ID COMMANDE#
	var product_ids = '{$ids_products|escape:"htmlall":"UTF-8"}'; // #ID PRODUCT#
	var basket_products = '{$ids_products_cart|escape:"htmlall":"UTF-8"}'; // #LISTING PRODUCTS IN BASKET#
	var ssl = '{$use_ssl|escape:"htmlall":"UTF-8"}';
	var id_categorie = '{$id_category|escape:"htmlall":"UTF-8"}'; // #ID CATEGORIE EN COURS#
	</script>
	<script type="text/javascript" src="https://tracking.lengow.com/tagcapsule_beta.js?lengow_id={$id_customer|escape:'htmlall':'UTF-8'}&idGroup={$id_group|escape:'htmlall':'UTF-8'}&page=payment"></script>
	<script type="text/javascript">
	var page = 'confirmation';  // #TYPE DE PAGE#
	var order_amt = '{$order_total|escape:"htmlall":"UTF-8"}'; // #MONTANT COMMANDE#
	var order_id = '{$id_order|escape:"htmlall":"UTF-8"}'; // #ID COMMANDE#
	var product_ids = '{$ids_products|escape:"htmlall":"UTF-8"}'; // #ID PRODUCT#
	var basket_products = '{$ids_products_cart|escape:"htmlall":"UTF-8"}'; // #LISTING PRODUCTS IN BASKET#
	var ssl = '{$use_ssl|escape:"htmlall":"UTF-8"}';
	var id_categorie = '{$id_category|escape:"htmlall":"UTF-8"}'; // #ID CATEGORIE EN COURS#
	</script>
	<script type="text/javascript" src="https://tracking.lengow.com/tagcapsule_beta.js?lengow_id={$id_customer|escape:'htmlall':'UTF-8'}&idGroup={$id_group|escape:'htmlall':'UTF-8'}&page=confirmation"></script>
{else}
	<script type="text/javascript">
	var page = '{$page_type|escape:"htmlall":"UTF-8"}';  // #TYPE DE PAGE#
	var order_amt = '{$order_total|escape:"htmlall":"UTF-8"}'; // #MONTANT COMMANDE#
	var order_id = '{$id_order|escape:"htmlall":"UTF-8"}'; // #ID COMMANDE#
	var product_ids = '{$ids_products|escape:"htmlall":"UTF-8"}'; // #ID PRODUCT#
	var basket_products = '{$ids_products_cart|escape:"htmlall":"UTF-8"}'; // #LISTING PRODUCTS IN BASKET#
	var ssl = '{$use_ssl|escape:"htmlall":"UTF-8"}';
	var id_categorie = '{$id_category|escape:"htmlall":"UTF-8"}'; // #ID CATEGORIE EN COURS#
	</script>
	<script type="text/javascript" src="https://tracking.lengow.com/tagcapsule.js?lengow_id={$id_customer|escape:'htmlall':'UTF-8'}&idGroup={$id_group|escape:'htmlall':'UTF-8'}"></script>
{/if}
<!-- /Tag_Lengow -->