<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";
echo $_POST['products'];
echo $_POST['fields'];
if(!isset($_POST['products']))
{ echo "No products";
  return;
}
$products = preg_replace('/[a-zA-Z]/', "", $_POST['products']);
if(!isset($_POST['fields']))
{ echo "No fields";
  return;
}
$pattern = '/,\.\"\' /';
$fields = preg_replace($pattern, "", $_POST['fields']);
if(!isset($_POST['id_shop']))
{ echo "No shop";
  return;
}
$id_shop = strval(intval($_POST['id_shop']));
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head><body onload=update_parent()>';

$field_array = explode(",", $fields);
$queryfields = array();
if (in_array("name", $field_array))
  $queryfields[] = "name";
if (in_array("description_short", $field_array))
  $queryfields[] = "description_short";
if (in_array("description", $field_array))
  $queryfields[] = "description";
if (in_array("meta_title", $field_array))
  $queryfields[] = "meta_title";
if (in_array("meta_keywords", $field_array))
  $queryfields[] = "meta_keywords";
if (in_array("link_rewrite", $field_array))
  $queryfields[] = "link_rewrite";
if (in_array("meta_description", $field_array))
  $queryfields[] = "meta_description";
$myfields = implode(",", $queryfields);

if(count($myfields) == 0)
  die("<b>No Fields</b>");

$query = "SELECT id_product,".$myfields." FROM ". _DB_PREFIX_."product_lang WHERE id_product IN (".$products.") AND id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
$res = dbquery($query);
echo '<script type="text/javascript">function update_parent() { top.prepare_update(); ';
while ($row=mysqli_fetch_array($res)) 
{ foreach($queryfields AS $qfield)
  { echo '
  top.update_field("'.$row["id_product"].'", "'.$qfield.'", '.json_encode($row[$qfield]).');';
//  top.update_field("'.$row["id_product"].'", "'.$qfield.'", "'.str_replace("\n","\\n",str_replace('"','\\"',$row[$qfield])).'");';
  }

}
echo "} </script>Finished successfully!</body></html>";

?>
