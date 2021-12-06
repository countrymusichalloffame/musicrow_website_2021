<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lbcore
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		
		<!-- TEMP ONLY: include lego blocks here -->
			<?php 

				/* main hero */
				get_template_part(
					'templates/blocks/hero', 'none'
				);

				/* mapbox */
				get_template_part(
					'templates/map', 'none'
				);
				
				get_template_part(
					'templates/shared/foundation/mediaGallery', 'none'
				);
			?>
					
			<?php
				/**
				 * This is the audio element for all tracks on the page.
				 * When a play button is clicked, it's audio track is 
				 * loaded into this audio element's source and begins playing.
				 * This will prevent multiple audio players from clashing with
				 * each other, and will allow for lazy-loading.
				 */
			?>
			<audio class="js-audio" preload="none">
				<source src>
			</audio>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
