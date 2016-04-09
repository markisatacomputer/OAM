<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
get_header();
$purl = $_SERVER['REQUEST_URI']; 
?>

<?php the_post(); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<?php if ( (get_option('show_on_front') == 'page') ? (get_option('page_on_front') != $post->ID) : true ) { ?>
	 <h3 class="storytitle"><?php make_text_image(get_the_title(),'heading'); ?></h3>
	<?php } ?>
	<div class="storycontent page">
		<?php the_content(__('(more...)')); ?>
	</div>

</div>

<?php get_footer(); ?>
