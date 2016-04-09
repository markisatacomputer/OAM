<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
require('../../../wp-load.php' );

$value = $_POST['value'];
$option = $_POST['option'];

$layout_options = array(
	'OAM_columns' => 0,
	'OAM_rap_width' => 0,
	'OAM_rap_padding' => 0,
	'OAM_image_block_height' => 0,
	'OAM_image_block_padding' => 0,
	'OAM_image_block_margin' => 0, 
	'OAM_title_block_bg' => '#000', 
	'OAM_title_block_color' => '#000');

foreach ($layout_options as $o => $v) {
	if ($o == $option) { 
		$layout_options[$o] = $value; 
	} else { 
		$layout_options[$o] = get_option($o); 
	}
}

$columns = $layout_options['OAM_columns'];
$rap_width = $layout_options['OAM_rap_width'];
$rap_padding = $layout_options['OAM_rap_padding'];
$block_height = $layout_options['OAM_image_block_height'];
$block_padding = $layout_options['OAM_image_block_padding'];
$block_margin = $layout_options['OAM_image_block_margin'];
$block_width = ($rap_width - $rap_padding*2)/$columns - $block_margin*2;
$title_bg = $layout_options['OAM_title_block_bg'];
$title_color = $layout_options['OAM_title_block_color'];

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
 <div id="rap">
 <?php
for( $i = 0; $i < 5; $i++) { ?>
	<a href="#">
		<div class="post_link">
			<p class="post_link">
				<?php make_text_image('your post title','title_block'); ?>
			</p>
		</div>
	</a>
<?php
}
 ?>
 	<div style="clear: both;"></div>
 </div>
 