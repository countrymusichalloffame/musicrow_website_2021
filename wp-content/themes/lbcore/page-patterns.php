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
			
			<?php 
			/* slideshow */
				get_template_part(
					'templates/shared/slideshow', 'none'
				);
			?>

			<!-- Blocks -->
			<?php /* media gallery */
				get_template_part(
					'templates/shared/foundation/mediaGallery', 'none'
				);
			?>
			<div class="patterns__block" data-block="storytelling"> 
				<?php /* storytelling */
					get_template_part(
						'templates/blocks/storytelling', 'none'
					); 
				?>
			</div>
			<div class="patterns__block" data-block="quote"> 
				<?php /* quote */
					get_template_part(
						'templates/blocks/quote', 'none'
					);
				?>
			</div>
			<div class="patterns__block" data-block="featuredMedia"> 
				<?php /* featured media */
					get_template_part(
						'templates/blocks/featuredMedia', 'none'
					);
				?>
			</div>
			<div class="patterns__block" data-block="profileCarousel"> 
				<?php
					/* profile carousel */
					get_template_part(
						'templates/blocks/profileCarousel', 'none'
					);
				?>
			</div>
			<div class="patterns__block" data-block="audioGallery"> 
				<?php /* audio gallery */
					get_template_part(
						'templates/blocks/audioGallery', 'none'
					);
				?>
			</div>
			<div class="patterns__block" data-block="permaCTA"> 
				<?php /* perma cta */
					get_template_part(
						'templates/blocks/permaCTA', 'none'
					);
				?>
			</div>
			<?php /* global cta */
				get_template_part(
					'templates/shared/foundation/cta', 'none'
				);
			?>
	
            <audio class="js-audio" preload="none">
                <source src>
            </audio>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
// get_sidebar();
get_footer();
