<?php
    /**
     * Audio Player for mediaGallery
     */
        
    $audio = array(
        'heading' => !empty($data['heading']) ? $data['heading'] : '',
        'copy' => !empty($data['copy']) ? $data['copy'] : '',
        'mp3' => !empty($data['mp3']['url']) ? $data['mp3']['url'] : 'https://countrymusichalloffame.org/content/uploads/2019/03/You_Aint_Going_Nowhere.mp3',
        'image' => !empty($data['image']) ? $data['image'] : [],
        'type' => !empty($data['type']) ? $data['type'] : 'song'        
    );
    $icon = 'audio-' . $audio['type'];

    $flexDirection = !empty($data['flex_direction']) ? $data['flex_direction'] : 'col';
    $nextStory = !empty($data['moving_story']['next_story']) ?  $data['moving_story']['next_story'] : '';
    $id = uniqid();

?>

<div class="mediaGalleryAudio" data-flex-dir="<?=$flexDirection?>">

    <div class="mediaGalleryAudio__media" data-audio-type="<?=$audio['type']?>">
        <?php if(!empty($audio['image']['url'])): ?>
            <figure class="mediaGalleryAudio__figure spacing__mrm images__figure-ratio ratios__1x1">
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $audio['image'],
                            'imgClass' => 'mediaGalleryAudio__figure-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        <?php endif; 
        if ($audio['type']=='song'): ?>
            <div class="mediaGalleryAudio__icon simpleAudio__icon">
                <svg class="icons__icon">
                    <use xlink:href="#<?=$icon?>"></use>
                </svg>
            </div>
        <?php endif; ?>
    </div>

    <div class="mediaGalleryAudio__content">

        <h3 class="mediaGalleryAudio__title styles__h6 color--gold"><?= $audio['heading'] ?></h3>
        <p class="mediaGalleryAudio__copy styles__paragraph--regular"><?= $audio['copy'] ?></p>
        <?php
            /* moving story */
            set_query_var('data', 
                array(
                    'next' => $nextStory,
                    'btn_classes' => 'links__link--small',
                )
            );
            get_template_part('/templates/shared/movingStory');
            wp_reset_query();
    
            /* audio player */
            set_query_var('data', 
                array(
                    'mp3' => $audio['mp3'],
                )
            );
            get_template_part('/templates/shared/audio/audioPlayer');
            wp_reset_query();
        ?>
    </div>
</div>