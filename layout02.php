<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
require('../../../wp-load.php' );

$columns = $_POST['OAM_columns'];
$rap_width = $_POST['OAM_rap_width'];
$rap_padding = $_POST['OAM_rap_padding'];
$block_height = $_POST['OAM_image_block_height'];
$block_padding = $_POST['OAM_image_block_padding'];
$block_margin = $_POST['OAM_image_block_margin'];
$block_width = ($rap_width - $rap_padding*2);
echo $block_width . "<br />";
$block_width = floor($block_width/$columns);
echo $block_width . "<br />";
$block_width = $block_width - $block_margin*2;
echo $block_width . "<br />";
$title_bg = get_option('OAM_title_block_bg');
$title_color = get_option('OAM_title_block_color');

?>
<style type="text/css" media="screen">
   div.post_link, table.post_link {
     float:left;
	 height: <?php echo $block_height; ?>px;
	 padding: <?php echo $block_padding; ?>px;
	 width: <?php echo $block_width; ?>px;
	 margin: <?php echo $block_margin; ?>px;
	 //background: #666;
	 background: transparent url(http://pictures.rpscollective.com/background.jpg) repeat scroll 50% 50%;
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
   	 border: solid 1px #666;
   	 margin: 0px;
   }
   #rap td {
   	 padding: <?php echo $rap_padding; ?>px; 
   	 width: <?php echo $rap_width; ?>px;
   }
   #rapper {
   	 margin: 0px auto;
   	 padding: 0px;
   }
 </style>
 <div id="rapper">
 <table id="rap" cellpadding="0" cellspacing="0">
 <tr><td align="center">
 <a href="#"><h1><?php make_text_image('Category Name will display like so','heading'); ?></h1></a>
 
 <?php
for( $i = 0; $i < 5; $i++) { ?>
	<a href="#">
		<table class="post_link">
		<tr><td>
			<p class="post_link">
				<?php image_maker(get_option('OAM_title_block_font'), 'your post title',get_option('OAM_title_block_size'), NULL, $title_color, $block_width-$block_padding*2-10); ?>
			</p>
		</tr></td>
		</table>
	</a>
<?php
}
 ?>
 	<div style="clear: both;"></div>
 </td></tr>
 </table>
 </div>
 