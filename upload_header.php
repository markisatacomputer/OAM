<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
 require('../../../wp-load.php' );
 
 $dir = get_theme_root() . '/OAM/header/';
 
$uploadfile = $dir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "Success!  Check out your new header:<br />
       <img src='" . get_bloginfo('template_directory') . "/header/" . $_FILES['userfile']['name'] . "' />";
} else {
  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
  // Otherwise onSubmit event will not be fired
  echo "Sorry - there was an error and the upload is incomplete.<br />";
}
?>