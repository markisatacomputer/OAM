<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */

add_action('wp_print_styles', 'OAM_print_layout_styles');
register_sidebar(array('name'=>'Right Widget Hangout'));
register_sidebar(array('name'=>'Bottom Widget Hangout'));

function first_img_css($post) {
	$first_img = '';
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];
	if (!empty($first_img)) {
		if (strpos($first_img, 'http') === false) { $first_img = bloginfo('template_url') . 'timthumb/timthumb.php?src=' . $first_img . '&w=235&h=200&zc=1'; }
		$first_img = "url('" . $first_img . "') 50% 50%";
	} else { 
		$first_img = '#FFF';
	}
	return $first_img;
}

function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function cleanname($name) {
	//$name = strip_tags($name, );
	//$nonos = array(" ", "'", '"', ",", "/", ";", ":", "$", "?");
	//$name = str_replace($nonos, "", $name);
	$name = preg_replace('/[^A-Za-z0-9_]*/','',$name);
	return $name;
}

function wrapttf($text, $size, $font, $width) {
	$ret = "";
    $arr = explode(' ', $text);
   
    foreach ( $arr as $word ){
        $teststring = $ret.' '.$word;
        $testbox = imagettfbbox($size, 0, $font, $teststring);
        if ( $testbox[2] > $width ){
            $ret.=($ret==""?"":"\n").$word;
        } else {
            $ret.=($ret==""?"":' ').$word;
        }
    }
    return $ret;
}


function image_maker($font = NULL, $text = NULL, $size = 18, $bg = NULL , $c = '000', $defaultwidth = 800) {
	
	if (!$font || !$text) { return; }
	
	//get rid of the #
	$c = str_replace('#','',$c); 
	if ($bg) { $bg = str_replace('#','',$bg); }
	
	//  definitions
	$fontroot = get_theme_root() . "/OAM/font/" . $font;
	
	//filename
	$thefilename= $font . $size . $bg . $c . $defaultwidth . cleanname($text);
	if (strlen($thefilename) > 252) { $thefilename = substr($thefilename, 0 , 252); }
	$thefilename .= ".png";
	//  has it already been created?
	if (!file_exists(get_theme_root() . "/OAM/images/" . $thefilename)) {
		//wrap
		$offset = 0.0125*$defaultwidth;
		$text = wrapttf($text,$size,$fontroot,$defaultwidth-$offset);
		
		//  dimensions
		if (!$text) { 
			$size = 0; 
			$height = 0; 
			$width = 0;
		} else  { 
			$bb = imagettfbbox($size, 0, $fontroot, $text); 
			$height = abs($bb[7] - $bb[1])*1.025 + 2; 
			$width = abs($bb[2] - $bb[0]) + $offset + 2;
		}
		
		$im = imagecreatetruecolor($width, $height);
		
		// white background and white text
		if ($bg) { 
			$bg = html2rgb($bg);
			$bg = imagecolorallocate($im, $bg[0], $bg[1], $bg[2]); } 
		else { 
			$bg = imagecolorallocatealpha($im, 87, 30, 0, 127); }
		imagefill($im, 0, 0, $bg);
		
		$c = html2rgb($c);
		$textcolor = imagecolorallocate($im, $c[0], $c[1], $c[2]); 
		
		// figure out the y
		$they = imagettfbbox($size, 0, $fontroot, 'T'); 
		$they = abs($they[7] - $they[1]) + 1;
		
		// write the string at the top left
		$points = imagettftext($im, $size, 0, 2, $they, $textcolor, $fontroot, html_entity_decode($text));
		
		imagealphablending($im, FALSE);
		imagesavealpha($im, TRUE);
		
		// save the image
		imagepng($im, get_theme_root() . '/OAM/images/' . $thefilename);
		imagedestroy($im);
	}
	echo '<img src="'; bloginfo('template_url'); echo '/images/' . $thefilename . '" alt="' . addslashes($text) . '" border="0" />';
}
function find_image_block_width() {
	
	$width = floor(get_option('OAM_rap_width')/get_option('OAM_columns')) - get_option('OAM_title_block_margin')*2 - $block_padding*2;
	return $width;
}
function make_text_image($text,$type) {
	$font = get_option('OAM_'. $type . '_font');
	$size = get_option('OAM_'. $type . '_size');
	$color = get_option('OAM_'. $type . '_color');
	switch($type) {
		case 'title_block':
			$width = find_image_block_width();
			break;
		case 'menu_link':
			$width = 300;
			break;
		default:
			$width = get_option('OAM_rap_width');
			break;
	}
	image_maker($font,$text,$size,NULL,$color,$width);
}
function OAM_print_layout_styles() {
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
   	 margin: 0px auto;
	 min-height: <?php echo $rap_width - 200; ?>px;
   }
</style>
<?php
}
/*

			SETTINGS

*/
add_action('admin_menu', 'OAM_create_menu');

function OAM_create_menu() {

	//create new top-level menu
	$page = add_menu_page('Change Everything', 'Change Everything', 'edit_themes', 'OAMoptions', 'OAM_fonts' );
	$page0 = add_submenu_page('OAMoptions','Layout', 'Layout', 'edit_themes', 'layout', 'OAM_layout');
	$page1 = add_submenu_page('OAMoptions','Clear Image Cache', 'Clear Image Cache', 'edit_themes', 'clearcache', 'OAM_cache');
	$page2 = add_submenu_page('OAMoptions','Upload Fonts', 'Upload Fonts', 'edit_themes', 'uploadfonts', 'OAM_upload');
	$page3 = add_submenu_page('OAMoptions','Header Image', 'Header Image', 'edit_themes', 'uploadheader', 'OAM_upload_header');

	//call register settings function
	add_action('admin_init', 'OAM_add_options' );
	add_action('admin_head-' . $page, 'OAM_settings_head_fonts');    
	add_action('admin_print_scripts-' . $page, 'OAM_settings_scripts_fonts');
	add_action('admin_head-' . $page1, 'OAM_settings_head_cache');    
	add_action('admin_head-' . $page2, 'OAM_settings_head_upload');    
	add_action('admin_print_scripts-' . $page2, 'OAM_settings_scripts_upload');
	add_action('admin_head-' . $page3, 'OAM_settings_head_upload_header');    
	add_action('admin_print_scripts-' . $page3, 'OAM_settings_scripts_upload_header');
	add_action('admin_head-' . $page0, 'OAM_settings_head_layout');    
	//add_action('admin_print_scripts-' . $page0, 'OAM_settings_scripts_layout');
}

function OAM_add_options() {
	//register our settings
	add_option("OAM_test_text", 'I am testing this out 12345^&*', '', 'yes');
	add_option("OAM_header_font", 'Garamond.ttf', '', 'yes');
	add_option("OAM_header_size", '60', '', 'yes');
	add_option("OAM_header_color", '#000', '', 'yes');
	add_option("OAM_heading_font", 'Garamond.ttf', '', 'yes');
	add_option("OAM_heading_size", '45', '', 'yes');
	add_option("OAM_heading_color", '#000', '', 'yes');
	add_option("OAM_subheading_font", 'Garamond.ttf', '', 'yes');
	add_option("OAM_subheading_size", '32', '', 'yes');
	add_option("OAM_subheading_color", '#000', '', 'yes');
	add_option("OAM_title_block_font", 'Garamond.ttf', '', 'yes');
	add_option("OAM_title_block_size", '20', '', 'yes');
	add_option("OAM_title_block_color", '#fff', '', 'yes');
	add_option("OAM_title_block_bg", '#000', '', 'yes');
	add_option("OAM_menu_link_font", 'Garamond.ttf', '', 'yes');
	add_option("OAM_menu_link_size", '22', '', 'yes');
	add_option("OAM_menu_link_color", '#000', '', 'yes');
	add_option("OAM_header_img", '', '', 'yes');
	
	add_option("OAM_columns", '3', '', 'yes');
	add_option("OAM_rap_width", '800px', '', 'yes');
	add_option("OAM_rap_padding", '20px', '', 'yes');
	add_option("OAM_image_block_height", '250px', '', 'yes');
	add_option("OAM_image_block_padding", '5px', '', 'yes');
	add_option("OAM_image_block_margin", '2px', '', 'yes');
}
/*

			write the Fonts and Colors options page

*/
function OAM_fonts() {
	if(get_option('OAM_header_font')) {$header = get_option('OAM_header_font'); }
	if(get_option('OAM_subheading_font')) {$subheading = get_option('OAM_subheading_font'); }
	if(get_option('OAM_heading_font')) {$heading = get_option('OAM_heading_font'); }
	if(get_option('OAM_title_block_font')) {$titleblock = get_option('OAM_title_block_font'); }
	if(get_option('OAM_menu_link_font')) {$menulink = get_option('OAM_menu_link_font'); }
	$test_text = get_option('OAM_test_text');
	$dir = get_theme_root() . '/OAM/font/';
	$all_font = array();

	if (is_dir($dir)) {
	 	if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == "file" && strpos($file, '.ttf')) {
					array_push($all_font,$file);
				}
			}
			closedir($dh);
		}
	sort($all_font);
	}
?>
<div class="wrap">
<h2>OAM Theme Options</h2>
<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
    <div class="alignleft">
    	<big><strong>Heading Text and Color</strong></big>
    </div>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Test Heading Text</th>
        <td><input type="text" size="60" name="OAM_test_text" value="<?php echo get_option('OAM_test_text'); ?>" /></td>
        </tr>
        <tr>
        <th scope="row">Preview</th>
        <td>
        Header (if there's no header image set)<br />
        <div id="header_eg">
        	<?php make_text_image($test_text,'header'); ?>
        </div>
        Heading<br />
        <div id="heading_eg">
        	<?php make_text_image($test_text,'heading'); ?>
        </div>
        Subheading<br />
         <div id="subheading_eg">
        	<?php make_text_image($test_text,'subheading'); ?>
        </div>
        Title in Image Block<br />
        <div class="post_link_eg">
			<p class="post_link_eg" id="title_block_eg">
					<?php make_text_image($test_text,'title_block'); ?>
        	</p>
		</div>
        Menu Link<br />
        <div id="menu_link_eg">
        	<?php make_text_image($test_text,'menu_link'); ?>
        </div>
        </td></tr>
        <tr valign="top">
        <th scope="row">Font Family</th>
        <td>
        	<table>
        	<tr>
        		<td>
					<label>Site Title Font</label><br />
					<select name="OAM_header_font" onchange="update_eg('header_eg');">
					<?php
					foreach ($all_font as $font) { ?>
					<option value="<?php echo $font; ?>"<?php if ($font == $header) { echo 'selected="selected"';} ?>><?php echo $font; ?></option><?php
					}
					?>
					</select>
				</td>
                <td>
					<label>Heading Font</label><br />
					<select name="OAM_heading_font" onchange="update_eg('heading_eg');">
					<?php
					foreach ($all_font as $font) { ?>
					<option value="<?php echo $font; ?>"<?php if ($font == $heading) { echo 'selected="selected"';} ?>><?php echo $font; ?></option><?php
					}
					?>
					</select>
				</td>
				<td>
					<label>Subheading Font</label><br />
					<select name="OAM_subheading_font" onchange="update_eg('subheading_eg');">
					<?php
					foreach ($all_font as $font) { ?>
					<option value="<?php echo $font; ?>"<?php if ($font == $subheading) { echo 'selected="selected"';} ?>><?php echo $font; ?></option><?php
					}
					?>
					</select>
				</td> 
			</tr>
			<tr>
				<td>
					<label>Title in Image Block Font</label><br />
					<select name="OAM_title_block_font" onchange="update_eg('title_block_eg');">
					<?php
					foreach ($all_font as $font) { ?>
					<option value="<?php echo $font; ?>"<?php if ($font == $titleblock) { echo 'selected="selected"';} ?>><?php echo $font; ?></option><?php
					}
					?>
					</select>
        		</td>
        		<td>
					<label>Menu Link Font</label><br />
					<select name="OAM_menu_link_font" onchange="update_eg('menu_link_eg');">
					<?php
					foreach ($all_font as $font) { ?>
					<option value="<?php echo $font; ?>"<?php if ($font == $menulink) { echo 'selected="selected"';} ?>><?php echo $font; ?></option><?php
					}
					?>
					</select>
        		</td>
        	</tr>
        	</table>
        </td>        
        </tr>
        <tr>
        <th scope="row">Font Size</th>
        <td>
            <table>
                <tr valign="top">
                    <td>
                    <label>Site Title Font Size</label><br />
                    <input type="text" name="OAM_header_size" maxlength="3" size="3" value="<?php echo get_option('OAM_header_size'); ?>" onchange="update_eg('header_eg');" />
                    </td>
                    <td>
                    <label>Heading Font Size</label><br />
                    <input type="text" name="OAM_heading_size" maxlength="3" size="3" value="<?php echo get_option('OAM_heading_size'); ?>" onchange="update_eg('heading_eg');" />
                    </td>
                    <td>
                    <label>Subheading Font Size</label><br />
                    <input type="text" name="OAM_subheading_size" maxlength="2" size="3" value="<?php echo get_option('OAM_subheading_size'); ?>" onchange="update_eg('subheading_eg');" />
                    </td>
                    <td>
                    <label>Title Block Font Size</label><br />
                    <input type="text" name="OAM_title_block_size" maxlength="2" size="3" value="<?php echo get_option('OAM_title_block_size'); ?>" onchange="update_eg('title_block_eg');" />
                    </td>
                    <td>
                    <label>Menu Link Font Size</label><br />
                    <input type="text" name="OAM_menu_link_size" maxlength="2" size="3" value="<?php echo get_option('OAM_menu_link_size'); ?>" onchange="update_eg('menu_link_eg');" />
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
         </td>
         </tr>
        <tr>
        <th scope="row">Font Color</th>
        <td>
             
             <table>   
                <tr valign="top">
					<td rowspan=2">
					<div id="picker"></div>
					<td>
					<label>Heading Color</label><br />
					<input type="text" name="OAM_heading_color" id="OAM_heading_color" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_heading_color'); ?>" onblur="update_eg('heading_eg');" />
					</td>
					<td>
					<label>Subheading Color</label><br />
					<input type="text" name="OAM_subheading_color" id="OAM_subheading_color" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_subheading_color'); ?>" onblur="update_eg('subheading_eg');" />
					</td>
					<td>
					<label>Site Title Color</label><br />
					<input type="text" name="OAM_header_color" id="OAM_heading_color" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_header_color'); ?>" onblur="update_eg('header_eg');" />
					</td>
                </tr>
                <tr>
                	<td>
					<label>Menu Link Color</label><br />
					<input type="text" name="OAM_menu_link_color" id="OAM_menu_link_color" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_menu_link_color'); ?>" onblur="update_eg('menu_link_eg');" />
					</td>
                	<td>
                	<label>Title Block Color</label><br />
					<input type="text" name="OAM_title_block_color" id="OAM_title_block_color" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_title_block_color'); ?>" onblur="update_eg('title_block_eg');" />
					</td>
					<td>
					<label>Title Block BG Color</label><br />
					<input type="text" name="OAM_title_block_bg" id="OAM_title_block_bg" class="colorwell" maxlength="7" size="7" value="<?php echo get_option('OAM_title_block_bg'); ?>" onblur="document.getElementById('title_block_eg').style.backgroundColor =this.value;" />
					</td>
				</tr>
            </table>
        </td>
        </tr>
    </table>
    
    <input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="OAM_header_font,OAM_header_size,OAM_header_color,OAM_heading_font,OAM_heading_size,OAM_heading_color,OAM_subheading_font,OAM_subheading_size,OAM_subheading_color,OAM_title_block_font,OAM_title_block_size,OAM_title_block_color,OAM_title_block_bg,OAM_menu_link_font,OAM_menu_link_size,OAM_menu_link_color" />
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php 
}
function OAM_settings_scripts_fonts() {
	wp_enqueue_script('jquery-colorpicker', get_bloginfo('template_directory') . '/farbtastic/farbtastic.js', array('jquery'));
}
function OAM_settings_head_fonts() {
	?>
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo(get_bloginfo('template_directory')); ?>/farbtastic/farbtastic.css" />
	<script type="text/javascript">
	function update_eg(result){
		thedatastring = '';
		for(i=0; i<document.forms[0].elements.length; i++){
			if (document.forms[0].elements[i].type != 'hidden' ) {
   				thedatastring += '&' + document.forms[0].elements[i].name + '=' + escape(document.forms[0].elements[i].value);
   			}
   		}
		jQuery.ajax({
		   type: "POST",
		   url: "<?php echo(get_bloginfo('template_directory')); ?>/eg.php",
		   data: "result="+result+thedatastring,
		   dataType: "html",
		   success: function(html){
			 document.getElementById(result).innerHTML = html;
		   }
		 });
	}
	
	jQuery(document).ready(function($) {
	//color picker
	
	 var f = $.farbtastic('#picker');
	 var p = $('#picker').css('opacity', 0.25);
	 var selected;
	 $('.colorwell')
	   .each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
	   .focus(function() {
		 if (selected) {
		   $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
		 }
		 f.linkTo(this);
		 p.css('opacity', 1);
		 $(selected = this).css('opacity', 1).addClass('colorwell-selected');
	   });
      
    });
	</script>
    <style type="text/css" media="screen">
   .colorwell {
     border: 2px solid #fff;
     width: 6em;
     text-align: center;
     cursor: pointer;
   }
   body .colorwell-selected {
     border: 2px solid #000;
     font-weight: bold;
   }
   div.post_link_eg {
	 height: <?php echo get_option('OAM_image_block_height'); ?>px;
	 padding: <?php echo get_option('OAM_image_block_padding'); ?>px;
	 width: <?php echo find_image_block_width(); ?>px;
	 margin: <?php echo get_option('OAM_image_block_margin'); ?>px;
	 background: transparent url(http://pictures.rpscollective.com/background.jpg) repeat scroll 50% 50%;
   }
   p.post_link_eg {
	 background: <?php echo get_option('OAM_title_block_bg'); ?> none repeat scroll 0 0;
	 color: #000;
	 line-height:165%;
	 margin:0;
	 padding:5px;
	 text-decoration:none;
   }
 </style>
	<?php
}
/*

			write Clear Cache page

*/
function OAM_cache() { ?>
	<div class="wrap">
	<h2>OAM Theme Options</h2>
		<div class="alignleft">
			<big><strong>Cleanup</strong></big>
		</div>
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Text Image Cache</th>
			<td>
			<input type="submit" class="button-primary" value="<?php _e('Clear Text Image Cache') ?>" onclick="clear_cache();" /><br />
			<p>All Header, Subheader, Menu Link, and Title Box Images are cached in <?php echo get_theme_root(); ?>/OAM/images/ for faster load times.  You may need to clear the cache - this button is where you do it.</p>
			<p>When clearing the cache, please wait for a confirmation message to display here.  Thanks!</p>
			<strong><p id="cache_result"></p></strong>
			</td>
			</tr>
	   </table>
   </div>
   <?php
}
function OAM_settings_head_cache() {
	?>
	<script type="text/javascript">
	function clear_cache(){
		jQuery.ajax({
		   type: "POST",
		   url: "<?php echo(get_bloginfo('template_directory')); ?>/cache.php",
		   dataType: "html",
		   success: function(html){
			 document.getElementById('cache_result').innerHTML = html;
		   }
		 });
	}
	</script>
	<?php
}
/*

			write Font Upload page

*/
function OAM_upload() { ?>
	<div class="wrap">
	<h2>OAM Theme Options</h2>
		<div class="alignleft">
			<big><strong>Upload</strong></big>
		</div>
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Add More Fonts</th>
			<td>
			<input type="submit" id="font_upload" class="button-primary" value="<?php _e('Upload Font') ?>" /><br />
			<p>You may want to add some more fonts to your choices.  All fonts are stored in <?php echo get_theme_root(); ?>/OAM/font/ and you can ftp font files there.  However this may be more convenient for you.</p>
			<p><strong>This theme only supports ttf fonts.</strong></p>
			<p>When uploading a font, please wait for a confirmation message to display here.  You will have to reload the page before the font will show up in the dropdowns above.  Thanks!</p>
			<p id="upload_result"></p>
			</td>
			</tr>
	   </table>
	</div>
	<?php
}
function OAM_settings_scripts_upload() {
	wp_enqueue_script('jquery-ajaxupload', get_bloginfo('template_directory') . '/js/ajaxupload.js', array('jquery'));
} 
function OAM_settings_head_upload() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		//  ajax uploader
		 new AjaxUpload('font_upload', {
		 action: '<?php echo(get_bloginfo('template_directory')); ?>/upload.php',
			 onSubmit : function(file , ext){
				 if (! (ext && /^(ttf)$/i.test(ext))){
						 // extension is not allowed
						 alert('Error: invalid file extension');
						 // cancel upload
						 return false;
				 }
			 },
			 onComplete : function(file,response){
				 document.getElementById('upload_result').innerHTML += file + ' ' + response;				
			 }
		 });
    });
	</script>
	<?php
}

/*

			write Header Upload page

*/
function OAM_upload_header() { ?>
	<div class="wrap">
	<h2>OAM Theme Options</h2>
		<div class="alignleft">
			<big><strong>Upload</strong></big>
		</div>
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Select A Header Image</th>
			<td>
			<p>The Header Image will display above the menu options.  If no header image is selected, an image of the Wordpress blog name will be generated using your heading font preferences.</p>
			<?php
			
			$dir = get_theme_root() . '/OAM/header/';
			$all_img = array();
		
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						if (filetype($dir . $file) == "file") {
							array_push($all_img,$file);
						}
					}
					closedir($dh);
				}
			sort($all_img);
			} ?>
			<form method="post" action="options.php">
				<?php wp_nonce_field('update-options'); ?>
				<p>Choose the header image here: 
				<select name="OAM_header_img" onchange="update_eg('menu_link_eg');">
					<option value="nada">I don't want to use a header image.</option>
						<?php
						foreach ($all_img as $img) { ?>
						<option value="<?php echo $img; ?>"<?php if ($img == $headerimg) { echo 'selected="selected"';} ?>><?php echo $img; ?></option><?php
						}
						?>
				</select>.  Don't forget to save!  And If you'd like to upload more you can do so below!
				</p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="OAM_header_img" />
				
				<div id="header"></div>
				
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				
			</form>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row">Upload More Header Images</th>
			<td>
			
			<p><strong>This theme only supports jpg and png images for the header.</strong></p>
			<p>When uploading an image, please wait for a confirmation message to display here.  You will have to reload the page before the image will show up in the dropdown above.  Thanks!</p>
			<p id="upload_result"></p>
			<input type="submit" id="header_upload" class="button-primary" value="<?php _e('Upload Image') ?>" /><br />
			
			</td>
			</tr>
	   </table>
	</div>
	<?php
}
function OAM_settings_scripts_upload_header() {
	wp_enqueue_script('jquery-ajaxupload', get_bloginfo('template_directory') . '/js/ajaxupload.js', array('jquery'));
} 
function OAM_settings_head_upload_header() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		//  ajax uploader
		 new AjaxUpload('header_upload', {
		 action: '<?php echo(get_bloginfo('template_directory')); ?>/upload_header.php',
			 onSubmit : function(file , ext){
				 if (! (ext && /^(jpg|png)$/i.test(ext))){
						 // extension is not allowed
						 alert('Error: invalid file extension');
						 // cancel upload
						 return false;
				 }
			 },
			 onComplete : function(file,response){
				 document.getElementById('upload_result').innerHTML += response;				
			 }
		 });
    });
    function update_eg(img){
		html = "<img src='<?php echo get_bloginfo('template_directory'); ?>/header/"+img + "' /><p>Your header will look like this - but not until you hit the <strong>Save Changes</strong> button.</p>";
		document.getElementById('header').innerHTML = html;
	}
	</script>
	<?php
}

/*

			write Layout page

*/
function OAM_layout() {
	if(get_option('OAM_subheading_font')) {$subheading = get_option('OAM_subheading_font'); }
	if(get_option('OAM_heading_font')) {$heading = get_option('OAM_heading_font'); }
	if(get_option('OAM_title_block_font')) {$titleblock = get_option('OAM_title_block_font'); }
	if(get_option('OAM_menu_link_font')) {$menulink = get_option('OAM_menu_link_font'); }
	$test_text = get_option('OAM_test_text');
	$dir = get_theme_root() . '/OAM/font/';
	$all_font = array();

	if (is_dir($dir)) {
	 	if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == "file" && strpos($file, '.ttf')) {
					array_push($all_font,$file);
				}
			}
			closedir($dh);
		}
	sort($all_font);
	}
?>
<div class="wrap">
<h2>OAM Theme Options</h2>
<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
    <div>
    	<big><strong>Category Page Layout</strong></big>
    </div>
    <p></p>
    <div id="mockup">
    Please wait one moment for Layout Preview
    <script type="text/javascript">
    	jQuery(document).ready(function($) {
    		update_eg();
    	});
    </script>
    </div>
    
    <table class="form-table">
        
          
                <tr valign="top">
					<td>
					<label>Number of Columns</label><br />
					<input type="text" name="OAM_columns" id="OAM_columns" maxlength="1" size="7" value="<?php echo get_option('OAM_columns'); ?>" onchange="update_eg();" />
					</td>
					<td>
					<label>Content Area Width</label><br />
					<input type="text" name="OAM_rap_width" id="OAM_rap_width" maxlength="3" size="7" value="<?php echo get_option('OAM_rap_width'); ?>" onchange="update_eg();" />
					</td>
					<td>
					<label>Content Area Padding</label><br />
					<input type="text" name="OAM_rap_padding" id="OAM_rap_padding" maxlength="12" size="12" value="<?php echo get_option('OAM_rap_padding'); ?>" onchange="update_eg();" />
					</td>
				</tr>
				<tr>
					<td>
					<label>Image Block Height</label><br />
					<input type="text" name="OAM_image_block_height" id="OAM_image_block_height" maxlength="7" size="7" value="<?php echo get_option('OAM_image_block_height'); ?>" onchange="update_eg();" />
					</td>
                	<td>
                	<label>Image Block Padding</label><br />
					<input type="text" name="OAM_image_block_padding" id="OAM_image_block_padding" maxlength="12" size="12" value="<?php echo get_option('OAM_image_block_padding'); ?>" onchange="update_eg();" />
					</td>
					<td>
					<label>Image Block Margin</label><br />
					<input type="text" name="OAM_image_block_margin" id="OAM_image_block_margin" maxlength="12" size="12" value="<?php echo get_option('OAM_image_block_margin'); ?>" onchange="update_eg();" />
					</td>
				</tr>
            </table>
            
    <input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="OAM_columns,OAM_rap_width, OAM_rap_padding,OAM_image_block_height,OAM_image_block_padding,OAM_image_block_margin" />
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php 
}
function OAM_settings_head_layout() {
	?>
	<script type="text/javascript">
	function update_eg(){
		thedatastring = '';
		for(i=0; i<document.forms[0].elements.length; i++){
			if (document.forms[0].elements[i].type != 'hidden' ) {
   				thedatastring += '&' + document.forms[0].elements[i].name + '=' + document.forms[0].elements[i].value;
   			}
   		}
		jQuery.ajax({
		   type: "POST",
		   url: "<?php echo(get_bloginfo('template_directory')); ?>/layout.php",
		   data: thedatastring,
		   dataType: "html",
		   success: function(html){
			 document.getElementById('mockup').innerHTML = html;
		   }
		 });
	}
	</script>
	<?php
}
?>