<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */

get_header(); 

// get the category
?>
		<h3 class="storytitle"><?php make_text_image(single_cat_title('', FALSE),'heading'); ?></h3>
		<div style="height: 20px; clear: both;"></div>
		
		<?php
		if (have_posts()) : while (have_posts()) : the_post();
				$first_img = first_img_css($post);
				?>
				<a href="<?php the_permalink(); ?>">
				<div class="post_link" style="background: <?php echo $first_img; ?>;">
					<p class="post_link">
						<?php make_text_image(get_the_title(),'title_block'); ?>
					
					</p>
					
					<?php
                    $price = get_post_meta($post->ID, 'sell_the_post_item_price', true);
                	if ($price > 0) { 
						$bg = get_option('OAM_title_block_bg');
						$h = get_option('OAM_title_block_size') + 10;
					?>
                       <table class="price" cellpadding="0" cellspacing="0" align="right" style="background: <?php echo $bg; ?>;">
                            <tr><td>
							<?php make_text_image('&#36;' . $price, 'subheading'); ?>
                            </td></tr>
                        </table>
                    <?php    
					}
					?>
					
				</div>
				</a>
		<?php endwhile; else: ?>
			<p><?php _e('Where\'s all the posts bro??!'); ?></p>
		<?php endif; ?>
		

<?php get_footer(); ?>
