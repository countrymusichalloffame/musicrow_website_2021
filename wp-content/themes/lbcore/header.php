<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lbcore
 */

 $favicon = get_site_icon_url( 512, 'https://cmhof.imgix.net/content/uploads/2019/04/11072205/cmhof-logo-round.png' );

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="shortcut icon" type="image/jpg" href="<?=$favicon?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	
	<?php if(isset($_GET['grid']) && $_GET['grid'] == 'true'): ?>
		<button type="button" class="button__primary" id="js-gridlines-button">
			Grid
		</button>
		<div class="gridlines" id="js-gridlines"></div>
	<?php endif; ?>

	<?php // header and primary nav goes here
		get_template_part('/templates/shared/foundation/navigation');
	?>

	<div id="content" class="site-content">
