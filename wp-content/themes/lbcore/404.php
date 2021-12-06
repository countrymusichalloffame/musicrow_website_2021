<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package lbcore
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<section class="error-404 not-found spacing__pvx">
				<div class="flex flex__dir--column flex__jc--center spacing__pax spacing__max">
					<h1 class="styles__h1">
						<span class="styles__h2 color--gold">Uh oh. </span><br>
						The page you are looking for does not exist.
					</h1>
					<p class="styles__paragraph--large spacing__mtx">
						That's okay though. You can still explore Music Row
						<span>
							<a class="links__inline links__inline--large" href="/">here</a>
						</span>
						.
					</p>
				</div>
			</section>
		</main>
	</div>

<?php
get_footer();
