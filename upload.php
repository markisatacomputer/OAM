<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
 require('../../../wp-load.php' );
 
 $dir = get_theme_root() . '/OAM/font/';
 
$uploadfile = $dir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "  has been uploaded.<br />";
} else {
  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
  // Otherwise onSubmit event will not be fired
  echo " - there was an error and the upload is incomplete.<br />";
}
?>