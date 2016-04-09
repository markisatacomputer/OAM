<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
 require('../../../wp-load.php' );
 
 $dir = get_theme_root() . '/OAM/images/';
 
 if (is_dir($dir)) {
	 	if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == "file" && strpos($file, '.png')) {
					unlink(get_theme_root() . '/OAM/images/' . $file);
				}
			}
			closedir($dh);
			?>
			<p>All text image files have been cleared from <?php echo get_theme_root(); ?>/OAM/images/.</p>
			<?php
		}
	}
?>