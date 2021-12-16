
<?php
/*
 *  Permalink Hero
 *  
 *  Cases: 
 *   1) Descriptive with Photo
 *   2) Video, descriptive banner below
 *   3) Comaprison Image, descriptive banner below
 */
    $heading = !empty($args['heading']) ? $args['heading'] : '';
    $details = !empty($args['details']) ? $args['details'] : '';
    $mediaType = !empty($args['media']['media_type']) ? $args['media']['media_type'] : '';

    switch ($mediaType) {
        case 'image':
            $image = $args['media']['image'];
            break;
        case 'then-now': 
            $foregroundImage = $args['media']['then_image'];
            $thenText = !empty($args['media']['then_text']) ? $args['media']['then_text'] : 'THEN';
            $backgroundImage = $args['media']['now_image'];
            $nowText = !empty($args['media']['now_text']) ? $args['media']['now_text']: 'NOW';
            break;
        case 'video': 
            $videoSource = $args['media']['video'];
            break;
    }
?>

<div class="permaHero js-perma-hero" data-type="<?= $mediaType; ?>">
    <div class="wrappers__permalink"> 
        <?php if($mediaType=='image' && !empty($image['url'])): 
            /* image */ ?>
            <figure class="permaHero__figure images__figure-ratio ratios__2x1">
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $image,
                            'imgClass' => 'permaHero__figure-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        <?php elseif($mediaType=='video'): 
            /* video */ 
            set_query_var('data', 
                array(
                    'video_source' => $videoSource,
                    'classes' => 'images__figure-ratio ratios__2x1'
                )
            );
            get_template_part('/templates/shared/video');
            wp_reset_query();
        elseif($mediaType=='then-now'): 
            /* before / after */
            get_template_part(
                '/templates/blocks/comparison',
                'comparison',
                array(
                    'background_image' => $backgroundImage,
                    'then_text' => $thenText,
                    'foreground_image' => $foregroundImage,
                    'now_text' => $nowText,
                    'classes' => 'permaHero__comparison'
                )
            ); 
        endif;
        if (!empty($heading)): ?>
            <h2 class="permaHero__heading styles__h1">
                <?= $heading; ?>
            </h2>
        <?php endif; ?>
    </div>
    
    <ul class="permaHero__list lists__unstyled">
        <?php if(!empty($details['date']['display_text'])): 
        /* founded text */?>
            <li class="permaHero__list-item">
                <svg class="icons__icon">
                    <use xlink:href="#date"></use>
                </svg>
                <?= $details['date']['display_text'] ?>
            </li>
        <?php endif; 
        /* building type */
        if(!empty($details['building_type'])): ?>
            <li class="permaHero__list-item">
                <svg class="icons__icon">
                    <use xlink:href="#building"></use>
                </svg>
                <?=$details['building_type']['label'] ?>
            </li>
        <?php endif;
        /* address */
        if(!empty($details['location']['address'])): ?>
            <li class="permaHero__list-item">
                <svg class="icons__icon">
                    <use xlink:href="#location"></use>
                </svg>
                <?=$details['location']['address']?>
            </li>
        <?php endif; 
        /* web link */
        if(!empty($details['web_url'])): ?>
            <li class="permaHero__list-item"> 
                <svg class="icons__icon">
                    <use xlink:href="#globe"></use>
                </svg>
                <a class="permaHero__list-item--link links__unlink" href="<?=$details['web_url']?>" target="_blank">
                    <?=$details['web_url']?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>