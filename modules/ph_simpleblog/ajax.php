<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

$status = 'success';
$message = '';

include_once(dirname(__FILE__).'/ph_simpleblog.php');
$blog = new ph_simpleblog();

$action = Tools::getValue('action');

switch ($action){
	case 'addRating':
		$item_id = Tools::getValue('item_id');
		$reply = $blog->addRating($item_id);		
		$message = $reply[0]["likes"];
	break;
	case 'removeRating':
		$item_id = Tools::getValue('item_id');
		$reply = $blog->removeRating($item_id);		
		$message = $reply[0]["likes"];
	break;
	default:
		$status = 'error';
		$message = 'Unknown parameters!';
	break;
}
$response = new stdClass();
$response->status = $status;
$response->message = $message;
$response->action = $action;
echo json_encode($response);

?>