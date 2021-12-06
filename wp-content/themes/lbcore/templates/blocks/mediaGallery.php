<?php
    /**
     * Media Gallery: mixed media carousel
     * 
     * Media variants:
     *  - image
     *  - video
     *  - audio
     *  - profile
     */
    $isHome = !empty($args['is_home']) ? $args['is_home'] : false;
    $args = $isHome ? $args : $args['mixed_media_gallery'];
    
    $title = !empty($args['heading']) ? $args['heading'] : '';
    $desc = !empty($args['description']) ? $args['description'] : '';
    $slides = !empty($args['gallery']) ? $args['gallery'] : [];
    $background = !empty($args['background_texture']) ? $args['background_texture'] : '';

    $mediaGalleryId = uniqid('image-gallery-');
    $goToBtn = '<button type="button" class="mediaGallery__slide-btn js-media-gallery-go-to">
    <span class="presentational__is-hidden">
        Advance to this slide.
    </span></button>';
?>

<div class="mediaGallery js-media-gallery" id="<?= $mediaGalleryId; ?>" data-is-home="<?=$isHome ? 'true' : 'false'?>">    
    <div class="wrappers__permalink">
        <div class="mediaGallery__flex">

            <div class="mediaGallery__header">
                <?php if(!empty($title)) : ?>
                    <h2 class="mediaGallery__header-title styles__h2">
                        <?= $title ?>
                    </h2>
                <?php endif; 
                if(!empty($desc)): ?>
                    <p class="mediaGallery__header-desc styles__paragraph--regular"><?=$desc?></p>
                <?php endif; ?>
            </div>

            <?php /** slider */
            if (count($slides) > 1): ?>
                <nav class="mediaGallery__controls">
                    <button class="mediaGallery__control-btn button__slider js-slider__direction-nav-prev" 
                            type="button" data-control="prev">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-left"></use>
                        </svg>
                        <span class="presentational__is-hidden">Previous Gallery Image</span>
                    </button>
                    <button class="mediaGallery__control-btn button__slider  js-slider__direction-nav-next" 
                            type="button" data-control="next">
                        <svg class="icons__icon">
                            <use xlink:href="#arrow-right"></use>
                        </svg>
                        <span class="presentational__is-hidden">Next Gallery Image</span>
                    </button>
                </nav>
            <?php endif;?> 
         </div>
        <div class="mediaGallery__slides-wrapper">
            <ul class="mediaGallery__slides lists__unstyled js-slides <?=empty($title) && empty($desc) ? 'spacing__mtn spacing__ptn' : ''?>" 
                data-id="<?= $mediaGalleryId; ?>">
                <?php foreach($slides as $key => $slide): ?>
                    <li class="mediaGallery__slide js-slide" data-media-type="<?=$slide['media']['media_type']?>">
                        <?php /* image */
                        if($slide['media']['media_type'] =='image' || $slide['media']['media_type'] =='profile' ): ?>
                            <figure class="mediaGallery__figure spacing__mtn">
                                <?php
                                    set_query_var('data', 
                                        array(
                                            'image' => $slide['media']['image'],
                                            'imgClass' => 'mediaGallery__figure-img'
                                        )
                                    );
                                    get_template_part('/templates/shared/dynamicImage');
                                    wp_reset_query();

                                if(!empty($slide['media']['image']['caption'])): ?>
                                    <figcaption class="styles__paragraph--caption color--caption-black">
                                        <?=$slide['media']['image']['caption']?>
                                    </figcaption>
                                <?php endif; 

                                if($slide['media']['media_type'] =='image'): ?>
                                    <button class="mediaGallery__modal-btn modal__open js-modal-open" 
                                            data-modal="modal--lightbox"
                                            data-img-src="<?=$slide['media']['image']['url']?>"
                                            data-img-alt="<?=$slide['media']['image']['alt']?>"
                                            data-img-caption="<?=$slide['media']['image']['caption']?>">
                                            <span class="presentational__is-hidden">
                                                Click to enlarge image.
                                            </span>
                                    </button>
                                <?php endif; ?>
                            </figure>
                        <?php /** video */
                        elseif($slide['media']['media_type'] =='video'): ?>
                            <figure class="mediaGallery__figure spacing__mtn">
                                <?php
                                    set_query_var('data', 
                                        array(
                                            'video_source' => $slide['media']['video'],
                                            'classes' => 'images__figure-ratio ratios__2x1'
                                        )
                                    );
                                    get_template_part('/templates/shared/video');
                                    wp_reset_query();
                                
                                if(!empty($slide['media']['video']['caption'])): ?>
                                    <figcaption class="styles__paragraph--caption color--caption-black">
                                        <?=$slide['media']['video']['caption']?>
                                    </figcaption>
                                <?php endif; ?>
                            </figure>
                            
                        <?php /** album audio **/
                        elseif($slide['media']['media_type'] =='albumAudio'): 
                            set_query_var('data', 
                                array(
                                    'heading' => $slide['text_content']['media_heading'],
                                    'copy' => $slide['text_content']['media_description'], 
                                    'mp3' => $slide['media']['album_audio']['audio_file'],
                                    'image' => $slide['media']['album_audio']['album_image'],
                                    'flex_direction' => $key % 2 == 0 ? 'col' : 'col-reverse', // to account for alternating slide positions
                                    'moving_story' => $slide['moving_story_clone'],
                                )
                            );
                            get_template_part('/templates/shared/audio/mediaGalleryAudio');
                            wp_reset_query();
                            
                        /** simple audio **/
                        elseif($slide['media']['media_type'] =='simpleAudio'):
                            $reelImage = array(
                                'url' =>  '/wp-content/themes/lbcore/resources/images/reel-recorder.png',
                                'alt' => 'Reel recorder illustration.'
                            );
                            $type = $slide['media']['simple_audio']['audio_type'] ?? 'interview';
                            $icon = 'audio-' . $type;
                        ?>
                            <div class="mediaGallery__simpleAudio-wrapper" data-audio-type="<?=$type?>">
                                <figure class="mediaGallery__simpleAudio-figure spacing__mtn">
                                    <?php
                                        set_query_var('data', 
                                            array(
                                                'image' => $reelImage,
                                                'imgClass' => 'mediaGallery__simpleAudio-img'
                                            )
                                        );
                                        get_template_part('/templates/shared/dynamicImage');
                                        wp_reset_query();
                                    ?>
                                </figure>
                                <div class="mediaGalleryAudio__icon simpleAudio__icon">
                                    <svg class="icons__icon">
                                        <use xlink:href="#<?=$icon?>"></use>
                                    </svg>
                                </div>
                            </div>
                            <?php 
                            set_query_var('data', 
                                array(
                                    'heading' => $slide['text_content']['media_heading'],
                                    'copy' => $slide['text_content']['media_description'], 
                                    'mp3' => $slide['media']['simple_audio']['audio_file'],
                                    'type' => $slide['media']['simple_audio']['audio_type'],
                                    'flex_direction' => $key % 2 == 0 ? 'col' : 'col-reverse', // to account for alternating slide positions
                                    'moving_story' => $slide['moving_story_clone'] 
                                )
                            );
                            get_template_part('/templates/shared/audio/mediaGalleryAudio');
                            wp_reset_query();                  
                        endif; 
                        
                        /** heaing and copy for all slides EXCEPT audio blocks (they have a separate format) */
                        if($slide['media']['media_type'] != 'albumAudio' 
                           && $slide['media']['media_type'] != 'simpleAudio' 
                           && ( !empty($slide['text_content']['media_heading']) || !empty($slide['text_content']['media_description']) )
                        ): 
                            $nextStory = !empty($slide['moving_story_clone']['next_story']) ?  $slide['moving_story_clone']['next_story'] : '';
                        ?>
                            <div class="mediaGallery__slide-heading">
                                <h3 class="mediaGallery__slide-title styles__h6 color--gold"><?= $slide['text_content']['media_heading'] ?></h3>
                                <p class="mediaGallery__slide-copy styles__paragraph--regular"><?= $slide['text_content']['media_description'] ?></p>
                                <?php /* moving story */
                                    if (!$isHome):
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    endif;
                                ?>
                            </div>
                        <?php endif; 
                        echo $goToBtn; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="mediaGallery__background <?=empty($background) ? 'mediaGallery__background--default' : ''?>">
        <?php if(!empty($background)): ?>
            <figure class="mediaGallery__background-figure spacing__mtn">
                <?php /* uploaded texture */
                    set_query_var('data', 
                        array(
                            'image' => $background,
                            'imgClass' => 'mediaGallery__background-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        <?php endif; ?>
    </div>
</div>