<?php

    $title = !empty($args['featured_media']['heading']) ? $args['featured_media']['heading'] : '';
    $wysiwyg = !empty($args['featured_media']['description']) ? $args['featured_media']['description'] : '';
    $mediaType = !empty($args['featured_media']['media']['media_type']) ? $args['featured_media']['media']['media_type'] : '';

    switch ($mediaType) {
        case 'image':
            $tempImage = lbcore_get_placeholder_img();
            $image = !empty($args['featured_media']['media']['image']) ? $args['featured_media']['media']['image'] : [];
            $imageCaption = !empty($image['caption']) ? $image['caption'] : '';
            break;
        case 'video': 
            $tempVideo = 'https://static.videezy.com/system/resources/previews/000/005/578/original/Windmill_BRoll.mp4';
            $videoSource = !empty($args['featured_media']['media'][$mediaType]) ? $args['featured_media']['media'][$mediaType] : [];
            $videoCaption = !empty($videoSource['caption']) ? $videoSource['caption'] : '';
            break;
        case 'then-now':
            $foregroundImage = !empty($args['featured_media']['media']['then_image']) ? $args['featured_media']['media']['then_image'] : [];
            $thenText = !empty($args['featured_media']['media']['then_text']) ? $args['featured_media']['media']['then_text'] : 'THEN';
            $backgroundImage = !empty($args['featured_media']['media']['now_image']) ? $args['featured_media']['media']['now_image'] : [];
            $nowText = !empty($args['featured_media']['media']['now_text']) ? $args['featured_media']['media']['now_text']: 'NOW';
            break;
    }
    $nextStory = !empty($args['featured_media']['moving_story_clone']['next_story']) ?  $args['featured_media']['moving_story_clone']['next_story'] : '';
?>

<div class="featuredMedia">
    <div class="wrappers__permalink">
        <?php /** image **/
        if($mediaType=='image' && !empty($image['url'])): ?>
            <figure class="featuredMedia__figure images__figure-ratio ratios__2x1">
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $image,
                            'imgClass' => 'featuredMedia__figure-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                if(!empty($imageCaption)): ?>
                    <figcaption class="styles__paragraph--caption color--grey">
                        <?=$imageCaption?>
                    </figcaption>
                <?php endif; ?>
                <button class="featuredMedia__modal-btn modal__open js-modal-open" 
                    data-modal="modal--lightbox"
                    data-img-src="<?=$image['url']?>"
                    data-img-alt="<?=$image['alt']?>"
                    data-img-caption="<?=$image['caption']?>">
                    <span class="presentational__is-hidden">
                        Click to enlarge image.
                    </span>
                </button>
            </figure>
        <?php /** video **/
        elseif($mediaType=='video'): ?>
            <figure class="featureMedia__figure spacing__mtn">
                <?php
                    set_query_var('data', 
                        array(
                            'video_source' => $videoSource,
                            'classes' => 'images__figure-ratio'
                        )
                    );
                    get_template_part('/templates/shared/video');
                    wp_reset_query();
                if(!empty($videoCaption)): ?>
                    <figcaption class="styles__paragraph--caption color--grey">
                        <?=$videoCaption?>
                    </figcaption>
                <?php endif; ?>
            </figure>
        <?php /** before / after **/
        elseif($mediaType=='then-now'): ?>
            <?php
                get_template_part(
                    '/templates/blocks/comparison',
                    'comparison',
                    array(
                        'background_image' => $backgroundImage,
                        'then_text' => $thenText,
                        'foreground_image' => $foregroundImage,
                        'now_text' => $nowText,
                        'classes' => 'featuredMedia__comparison'
                    )
                ); 
            ?>
        <?php endif; ?>

        <div class="featuredMedia__heading">
            <?php if(!empty($title)): ?>
                <h2 class="featuredMedia__heading-title color--action-gold styles__h5">
                    <?=$title?>
                </h2>
            <?php endif;
            if (!empty($storyText)): ?>
                <p class="featuredMedia__story-text movingStory__text color--white">
                    <?=$storyText?>
                </p>
            <?php endif; ?>
        </div>

        <?php if(!empty($wysiwyg)): ?>
            <div class="featuredMedia__wysiwyg wysiwyg styles__paragraph--small color--white">
                <?=$wysiwyg?>
            </div>
        <?php endif;
        /* moving story */
            set_query_var('data', 
                array(
                    'next' => $nextStory,
                    'btn_wrapper_classes' => 'flex flex__jc--space-between',
                    'btn_classes' => 'links__link--small links__link--dark',
                )
            );
            get_template_part('/templates/shared/movingStory');
            wp_reset_query();
        ?>
    </div>
</div>
