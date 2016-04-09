/*
Theme Name: Oakland Art Murmur
Theme URI: http://oaklandartmurmur.com/
Description: Just for the murmur.
Version: 3.0
Author: Mark Nicola
Tags: murmur

Oakland Art Murmur by Mark Nicola || http://homemademess.com
This is just a basic layout, with only the bare minimum defined.

*/
<?php

require('../../../wp-load.php' );

$columns = get_option('OAM_columns');
$rap_width = get_option('OAM_rap_width');
$rap_padding = get_option('OAM_rap_padding');
$block_height = get_option('OAM_image_block_height');
$block_padding = get_option('OAM_image_block_padding');
$block_margin = get_option('OAM_image_block_margin');
$block_width = ($rap_width - $rap_padding*2);
//echo $block_width . "<br />";
$block_width = floor($block_width/$columns);
//echo $block_width . "<br />";
$block_width = $block_width - $block_margin*2;
//echo $block_width . "<br />";
$title_bg = get_option('OAM_title_block_bg');
$title_color = get_option('OAM_title_block_color');

?>
<style type="text/css" media="screen">  
   div.post_link {
     float:left;
	 height: <?php echo $block_height; ?>px;
	 padding: <?php echo $block_padding; ?>px;
	 width: <?php echo $block_width; ?>px;
	 margin: <?php echo $block_margin; ?>px;
	 background: transparent url(http://farm3.static.flickr.com/2536/4238738964_fc2bc5065c.jpg) repeat scroll 50% 50%;
   }
   p.post_link {
     background: <?php echo $title_bg; ?> none repeat scroll 0 0;
	 color: <?php echo $block_height; ?>;
	 line-height:165%;
	 margin:0;
	 padding:5px;
	 text-decoration:none;
   }
   #rap {
   	 padding: <?php echo $rap_padding; ?>px; 
   	 width: <?php echo $rap_width; ?>px;
   	 border: solid 1px #666;
   	 margin: 20px;
   }
</style>