<?php
if(!$pos = strrpos($_SERVER['SCRIPT_NAME'], "/"))
  $pos = 0;
$script = substr($_SERVER['SCRIPT_NAME'],$pos+1);
echo '<hr style="border: 1px dotted #CCCCCC;" /><table width="100%"><tr>';
if($script != "cat-edit.php")
  echo '<td width="20%"><a href=cat-edit.php>Category Edit</a></td>';
if($script != "order-edit.php")
  echo '<td width="20%"><a href=order-edit.php>Order Edit</a></td>';
if($script != "product-sort.php")
  echo '<td width="20%"><a href=product-sort.php>Product Sort</a></td>';
if($script != "product-edit.php")
  echo '<td width="20%"><a href=product-edit.php>Product Edit</a></td>';
if($script != "shopsearch.php")
  echo '<td width="20%"><a href=shopsearch.php>Shop search</a></td>';
if($script != "discount-list.php")
  echo '<td width="20%"><a href=discount-list.php>Discounts</a></td>';
if($script != "combi-edit.php")
  echo '<td width="20%"><a href=combi-edit.php>Combinations</a></td>';
echo '</tr></table>';
echo '<center>PS version: '._PS_VERSION_.' &nbsp; &nbsp; &nbsp; <a href=logout1.php>logout</a></center>';
