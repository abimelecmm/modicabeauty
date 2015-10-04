<?php
// Located in /modules/mymodule/ajax.php
require_once(dirname(__FILE__).'../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../init.php');

require_once('pninlineeditor.php');


$id_product = Tools::getValue("id_product");
$id_product_attribute = Tools::getValue("id_product_attribute");
$nombre = Tools::getValue("nombre");
$precio = Tools::getValue("precio");
$cantidad = Tools::getValue("cantidad");
$referencia = Tools::getValue("referencia");
$descripcion = Tools::getValue("descripcion");
$descripcion_corta = Tools::getValue("descripcion_corta");
$red_amount = Tools::getValue("red_amount");
$red_percent = Tools::getValue("red_percent");

// Envía los datos
$pninlineeditor = new PnInlineEditor();
echo $pninlineeditor->actualizarProducto($id_product, $id_product_attribute, $nombre, $precio, $cantidad, $referencia, $descripcion, $descripcion_corta, $red_amount, $red_percent);



exit;



?>