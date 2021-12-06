<?php

    //print_r($args);

    $heading = !empty($args['profile_slider']['heading']) ? $args['profile_slider']['heading'] : '';
    $desc = !empty($args['profile_slider']['description']) ? $args['profile_slider']['description'] : '';
    $slides = !empty($args['profile_slider']['people']) ? $args['profile_slider']['people'] : '';
?>

<div class="profileCarousel">
    <div class="wrappers__permalink">
        <?php if(!empty($heading)): ?>
            <h2 class="profileCarousel__heading styles__h2"><?= $heading; ?></h2>
        <?php endif;
        if (!empty($desc)): ?>
            <p class="profileCarousel__desc styles__paragraph--regular color--white"><?=$desc?></p>
        <?php endif;
        if(!empty($slides)): ?>
            <div class="profileCarousel__slides-wrapper js-profile-carousel <?=empty($heading) && empty($desc) ? 'spacing__mtn spacing__ptn' : ''?>">
                <ul class="profileCarousel__slides js-slides">
                    <?php foreach($slides as $slide):
                        $nextStory = !empty($slide['moving_story_clone']['next_story']) ?  $slide['moving_story_clone']['next_story'] : '';
                    ?>
                        <li class="profileCarousel__slide js-slide">
                            <div class="profileCarousel__slide-person">
                                <?php if(!empty($slide['image']['url'])): ?>
                                    <figure class="profileCarousel__slide-figure images__figure-ratio ratios__1x1">
                                        <?php
                                            set_query_var(
                                                'data', 
                                                array(
                                                    'image' => $slide['image'],
                                                    'imgClass' => 'profileCarousel__slide-img'
                                                )
                                            );
                                            get_template_part('/templates/shared/dynamicImage');
                                            wp_reset_query();
                                        ?>
                                    </figure>
                                <?php endif;
                                if (!empty($slide['content']['first_name'])): ?>
                                    <h4 class="profileCarousel__slide-heading color--white styles__h6 spacing__mtl">
                                        <?= $slide['content']['first_name'] ?></br>
                                        <span class="profileCarousel__slide-heading--lastName">
                                            <?= $slide['content']['last_name'] ?>
                                        </span>
                                    </h4>
                                <?php endif;
                                if (!empty($slide['content']['role'])): ?>
                                    <p class="profileCarousel__slide-role color--white styles__paragraph--small spacing__mts">
                                        <?= $slide['content']['role'] ?>
                                    </p>
                                <?php endif;?>
                            </div>
                            
                            <div class="profileCarousel__slide-copy">
                                <?php if(!empty($slide['content']['heading'])): ?>
                                    <h4 class="styles__h4 color--white"><?=$slide['content']['heading']?></h4>
                                <?php endif;
                                if(!empty($storyText)): ?>
                                    <p class="profileCarousel__slide-story-text color--white movingStory__text">
                                        <?=$storyText?>
                                    </p>
                                <?php endif;?>
                                <?php if (!empty($slide['content']['description'])): ?>
                                    <div class="profileCarousel__slide-desc color--white">
                                        <?=$slide['content']['description']?>
                                    </div>
                                <?php endif;
                                    /* moving story */
                                    set_query_var('data', 
                                        array(
                                            'next' => $nextStory,
                                            'btn_wrapper_classes' => 'flex flex__wrp--wrap',
                                            'btn_classes' => 'flex__col links__link--small links__link--dark',
                                        )
                                    );
                                    get_template_part('/templates/shared/movingStory');
                                    wp_reset_query();
                                ?>
                            </div>                    
                        </li>
                    <?php endforeach;?>
                </ul>
                <nav class="profileCarousel__nav">
                    <button class="profileCarousel__nav-item button__slider button__slider--dark js-slider__direction-nav-prev" 
                        data-direction="prev">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-left"></use>
                        </svg>
                        <span class="presentational__is-hidden">Previous Gallery Image</span>
                    </button>
                    <button class="profileCarousel__nav-item button__slider button__slider--dark js-slider__direction-nav-next" 
                        data-direction="next">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-right"></use>
                        </svg>
                        <span class="presentational__is-hidden">Next Gallery Image</span>
                    </button>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>