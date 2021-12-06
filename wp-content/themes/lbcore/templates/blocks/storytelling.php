<?php
    $wysiwyg = !empty($args['wysiwyg']) ? $args['wysiwyg'] : '';
	$nextStory = !empty($args['moving_story_clone']['next_story']) ?  $args['moving_story_clone']['next_story'] : '';
?>


<div class="storytelling">
	<div class="wrappers__permalink">
		<?php if(!empty($wysiwyg)): ?>
			<div class="wysiwyg wysiwyg--first-letter">
                <?= $wysiwyg; ?>
            </div>
		<?php endif; 
            /* moving story */
            set_query_var('data', 
                array(
                    'next' => $nextStory,
                    'btn_wrapper_classes' => 'flex flex__jc--space-between',
                    'btn_classes' => 'links__link--small',
                )
            );
            get_template_part('/templates/shared/movingStory');
            wp_reset_query();
        ?>
	</div>
</div>