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

$block_width = $rap_width/$columns;
$block_width = floor($block_width);
$block_width = $block_width - $block_margin*2;
$block_width=$block_width-$block_padding*2;

$title_bg = get_option('OAM_title_block_bg');
$title_color = get_option('OAM_title_block_color');

?>
 <div id="rap" style="padding: <?php echo $rap_padding; ?>px; width: <?php echo $rap_width; ?>px; border: solid 1px #666; margin: 0px;">
 	<div style="clear: both;"></div>
 	<a href="#"><h1><?php make_text_image('Category Name will display like so','heading'); ?></h1></a>
 
 <?php
for( $i = 0; $i < 5; $i++) { ?>
	<a href="#">
		<div class="post_link" style="float:left; height: <?php echo $block_height; ?>px; padding: <?php echo $block_padding; ?>px; width: <?php echo $block_width; ?>px; margin: <?php echo $block_margin; ?>px; background: transparent url(http://pictures.rpscollective.com/background.jpg) repeat scroll 50% 50%;">
			<p class="post_link" style="background: <?php echo $title_bg; ?> none repeat scroll 0 0; color: <?php echo $block_height; ?>; margin:0; padding:5px; text-decoration:none;">
				<?php image_maker(get_option('OAM_title_block_font'), 'your post title',get_option('OAM_title_block_size'), NULL, $title_color, $block_width-$block_padding*2-10); ?>
			</p>
            <?php
			$price = 120;
			if ($price > 0) { 
			?>
			   <table class="price" cellpadding="0" cellspacing="0" align="right" style="background: <?php echo $title_bg; ?>; margin-top: 2px;">
					<tr><td style="padding: 22px 2px 4px;">
					<?php make_text_image('&#36;' . $price, 'subheading'); ?>
					</td></tr>
				</table>
			<?php    
			}
			?>
		</div>
	</a>
<?php
}
 ?>
 	<div style="clear: both;"></div>
 </div>
 