<?php
if(!is_writable(session_save_path()))
{ setcookie("triple","");
}
else
{ session_start();
  session_regenerate_id(true);
  // session_unset();  // possible extra for problems
  session_destroy();
  //unset($_SESSION['t67']);	// possible extra for problems
}

header('Location: login1.php'); //Go to login

?> 
