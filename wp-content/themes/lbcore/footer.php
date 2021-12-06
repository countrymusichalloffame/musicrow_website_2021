<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lbcore
 */

?>

	</div><!-- #content -->

	<?php 
		get_template_part('/templates/shared/foundation/footer'); 
	?>

</div><!-- #page -->

<?php
	wp_footer();
	
	$locationsJS = get_template_directory() . '/resources/dist/js/locations.lbcore.js';
	if (file_exists($locationsJS)):
?>
	<script async><?php include $locationsJS; ?></script>
<?php endif; ?>
</body>
</html>
