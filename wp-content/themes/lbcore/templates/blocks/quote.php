<?php
	$quote = !empty($args['quote']) ? $args['quote'] : ''; 
	$attribution = !empty($args['attribution']) ? $args['attribution'] : '';

	$backgroundType = !empty($args['background_type']) ? $args['background_type'] : '';
    $backgroundImage = $args['background_image'];
    $backgroundOverlay = $args['background_overlay'][0];
	
?>
<?php if(!empty($quote)): ?>
	<div class="quote" 
	     data-overlay=<?=$backgroundOverlay=='overlay' ? 'true' : 'false'?>
		 data-background=<?=$backgroundType?>>
		<div class="wrappers__permalink">
			<div class="quote__wrapper">
				<p class="quote__copy styles__libre-franklin--bold">
					<?=$quote?>
					<?php if(!empty($attribution)): ?>
						<br>
						<span class="quote__attribution styles__libre-franklin">
							— <?=$attribution?>
						</span>
					<?php endif; ?>
				</p>
			</div>
			<div class="quote__background">
				<?php if (!empty($backgroundImage)): ?>
					<figure class="quote__background-figure images__figure-ratio ratios__2x1" >
						<?php
							set_query_var('data', 
								array(
									'image' => $backgroundImage,
									'imgClass' => 'quote__background-img'
								)
							);
							get_template_part('/templates/shared/dynamicImage');
							wp_reset_query();
						?>
					</figure>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>