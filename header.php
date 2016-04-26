<?php
/**
 * @package WordPress
 * @subpackage Oakland_Art_Murmur
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="description" content="Roy Slaper's denim related products" />
	<meta name="keywords" content="Roy Slaper,Denim,Jeans,Vintage Sewing Machines" />
	<meta name="author" content="Roy Slaper" />

	<title><?php wp_title('&#124;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_url'); ?> );
	</style>
    <!--[if lt IE 7.]>
		<script defer type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/supersleight-min.js"></script>
	<![endif]-->

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>

<body>

<div id="header">
	<div id="head_img">
	<a href="/">
	<?php
	$header = get_option('OAM_header_img');
	if ($header != "nada") { ?>
    <img src="<?php bloginfo('template_url'); ?>/header/<?php echo $header; ?>" border="0" /><?php
    } else {
    	make_text_image(get_bloginfo('name'),'header');
    } ?>
    </a>
    </div>
    <div id="menu">
		<table cellpadding="0" cellspacing="0" align="center" style="margin: 0px auto;">
		<tr>
			<?php
			if (get_option('page_on_front') && get_option('show_on_front') == 'page' ) {
				$home = '&exclude=' . get_option('page_on_front');
			}
			$menu = get_pages('parent=0' . $home);
			$toplinks = array();
			foreach ($menu as $menu_link) {
				$toplinks[get_page_link($menu_link->ID)] = $menu_link->post_title;
			}
			/*   Remove post categories
			$cats = get_categories();
			foreach ($cats as $cat) {
				if ($cat->parent == 0) {
					$toplinks[get_category_link( $cat->term_id )] = $cat->name;
				}
			}
			*/
			asort($toplinks);
			foreach ($toplinks as $link => $title) { ?>
				<td valign="top" align="left"><a href="<?php echo $link; ?>"><?php make_text_image($title, 'menu_link'); ?></a></td>
			<?php } ?>
		</tr>
		</table>
    </div>

</div>

<div id="rap">


