<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
require('../../../wp-load.php' );

$result = $_POST['result'];
$result = substr($result, 0, strlen($result) - 3);

$font = $_POST['OAM_'. $result . '_font'];
$size = $_POST['OAM_'. $result . '_size'];
$color = $_POST['OAM_'. $result . '_color'];
$text = $_POST['OAM_test_text'];
switch($result) {
	default:
		$width = get_option('OAM_rap_width');
		break;
	case 'title_block':
		$width = find_image_block_width();
		break;
	case 'menu_link':
		$width = 300;
		break;
}
image_maker($font,$text,$size,NULL,$color,$width);
		
?>