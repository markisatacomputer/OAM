<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
?>

<!-- begin footer -->
<div style="clear: both;"></div>
</div>



<div id="footer">
	<ul id="widgets">
	 	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
		
        <?php endif; ?>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
           
        <?php endif; ?>
    </ul>

<?php wp_footer(); ?>
</div>
<div id="bottom">
	<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
    	<td align="left" style="color: #999;"> Site Design by homemademess _dot_ com.  &copy; <?php echo date('Y') . ' ' . get_bloginfo('name'); ?>.</td>        
		<td align="right"><?php wp_loginout(); ?>	</td>
    </tr>
	<table>
</div>
</body>
</html>