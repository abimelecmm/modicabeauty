<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('pk_sequenceminicslider.php');

$minicSlider = new pk_sequenceminicslider();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $minicSlider->secure_key || !Tools::getValue('action'))
	die(1);

$success = false;

if (Tools::getValue('action') == 'updateOrder' && Tools::getValue('slides')){  
	parse_str(Tools::getValue('slides'), $slides);	
	$i = 0; 	

	foreach ($slides['order'] as $key => $slide){		
		$i++;		
		$id = split('h', $slide);			

		if(Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'minic_slider_adv` SET id_order = '.$i.' WHERE id_slide = '.$id[0]))
			$success = true;    
		else
			$success = false;    
	}
}
if($success)
	echo '{"success" : "true"}';
else
	echo '{"success" : "false"}';

?>