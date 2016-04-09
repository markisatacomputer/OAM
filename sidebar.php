<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
?>
<!-- begin sidebar -->

<?php 	/* Widgetized sidebar, if you have the plugin installed. */
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
	<?php wp_meta(); ?>	
<?php endif; ?>

<p></p>


<!-- end sidebar -->
