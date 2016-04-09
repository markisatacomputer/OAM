<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
get_header();

if (is_home()) { 
	
    if (have_posts()) : while (have_posts()) : the_post();
            $first_img = first_img_css($post);
            ?>
            <a href="<?php the_permalink(); ?>">
            <div class="post_link" style="background: <?php echo $first_img; ?>;">
                <p class="post_link">
                	<?php 
					make_text_image(get_the_title(),'title_block'); 
					$price = get_post_meta($post->ID, 'post_item_price', true);
                	if ($price > 0) { ?>
                    	<span class="price"><?php make_text_image(' --  &#36;' . $price, 'title_block'); ?></span><?php
					}
					?>
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
        <p><?php _e('No posts yet bro.'); ?></p>
    <?php endif; 
	
} else {
	
	the_post(); ?>

	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
    <?php 
	if ( (get_option('show_on_front') == 'page') ? (get_option('page_on_front') != $post->ID) : true ) { ?>
	 <h3 class="storytitle"><?php make_text_image(get_the_title(), 'heading'); ?></h3>
	<?php } ?>
	<div>
		<?php the_content(__('(more...)')); ?>
	</div>
 </div>
	<?php
}

get_footer(); ?>