<?php
    $title = !empty($args['audio_gallery']['heading']) ? $args['audio_gallery']['heading'] : '';
    $desc =  !empty($args['audio_gallery']['description']) ? $args['audio_gallery']['description'] : '';
    $albumGallery = !empty($args['audio_gallery']['album_audio']) ? $args['audio_gallery']['album_audio'] : [];
    $simpleAudioGallery = !empty($args['audio_gallery']['simple_audio']) ? $args['audio_gallery']['simple_audio'] : [];
    $nextStory = !empty($args['audio_gallery']['moving_story_clone']['next_story']) ?  $args['audio_gallery']['moving_story_clone']['next_story'] : '';
?>

<div class="audioGallery">
    <div class="wrappers__permalink">
        <?php 
        /* header */
        if(!empty($title)): ?>
            <h2 class="audioGallery__title styles__h2"><?=$title?></h2>
        <?php endif; 
        if (!empty ($desc)): ?>
            <p class="audioGallery__desc styles__paragraph--regular spacing__mtl"><?=$desc?></p>
        <?php endif;

        /* albums slider */
        if(!empty($albumGallery)): ?>
            <div class="audioGallery__slides-wrapper js-audio-gallery">
                <div class="audioGallery__slides-overflow">
                    <ul class="audioGallery__slides js-slides <?=empty($title) && empty($desc) ? 'spacing__mtn' : ''?>">
                        <?php foreach($albumGallery as $i=>$slide): ?>
                            <li class="audioGallery__slide spacing__phl js-slide">
                                <?php 
                                    /* album audio player */
                                    set_query_var('data', 
                                        array(
                                            'title' => $slide['audio_album_clone']['audio_title'],
                                            'desc' => $slide['audio_album_clone']['audio_description'],
                                            'mp3' => $slide['audio_album_clone']['audio_file'],
                                            'image' => $slide['audio_album_clone']['album_image'],
                                        )
                                    );
                                    get_template_part('/templates/shared/audio/albumAudio');
                                    wp_reset_query();
                                ?>
                                <button type="button" class="audioGallery__slide-btn js-audio-gallery-go-to">
                                    <span class="presentational__is-hidden">
                                        Play the track <?= $slide['audio_album_clone']['audio_title'] . ' by ' . $slide['audio_album_clone']['audio_description']; ?>.
                                    </span>
                                </button>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <nav class="audioGallery__nav">
                    <button class="audioGallery__nav-item button__slider js-slider__direction-nav-prev" data-direction="prev">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-left"></use>
                        </svg>
                        <span class="presentational__is-hidden">Previous Gallery Item</span>
                    </button>
                    <button class="audioGallery__nav-item button__slider js-slider__direction-nav-next" data-direction="next">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-right"></use>
                        </svg>
                        <span class="presentational__is-hidden">Next Gallery Item</span>
                    </button>
                </nav>
            </div>
        <?php endif;
        /* simple audio list */
        if(!empty($simpleAudioGallery)): ?>
            <ul class="audioGallery__list lists__unstyled <?=empty($title) && empty($desc) ? 'spacing__mtn' : ''?>">
                <?php foreach ($simpleAudioGallery as $item): ?>
                    <li class="audioGallery__list-item">
                        <?php 
                            /* simple audio player */
                            set_query_var('data', 
                                array(
                                    'desc' => $item['audio_simple_clone']['audio_description'],
                                    'title' => $item['audio_simple_clone']['audio_title'],
                                    'mp3' => $item['audio_simple_clone']['audio_file'],
                                    'type' => $item['audio_simple_clone']['audio_type']
                                )
                            );
                            get_template_part('/templates/shared/audio/simpleAudio');
                            wp_reset_query();
                        ?>  
                    </li>
                <?php endforeach; ?>
            </ul>
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