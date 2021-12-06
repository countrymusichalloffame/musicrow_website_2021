<?php
    /**
     * Audio will be either: 
     *   Album Audio, Simple Audio
     */
    $audio = array(
        'title' => !empty($data['title']) ? $data['title'] : '',
        'desc' => !empty($data['desc']) ? $data['desc'] : '',
        'mp3' => !empty($data['mp3']['url']) ? $data['mp3']['url'] : '',
        'image' => !empty($data['image']) ? $data['image'] : '',
    );

    if (empty($audio['mp3'])) {
        return;
    }

    // print_r($audio['mp3']);
?>

<div class="albumAudio" data-audio-type="<?=$audio['type']?>">
    <div class="albumAudio__wrapper">
        <?php if(!empty($audio['image']['url'])): ?>
            <figure class="albumAudio__figure images__figure-ratio ratios__1x1">
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $audio['image'],
                            'imgClass' => 'albumAudio__figure-img',
                            'crop' => false
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        <?php endif;  ?>

        <div class="albumAudio__flex">
            <?php /* audio player */
                set_query_var('data', 
                    array(
                        'mp3' => $audio['mp3'],
                        'show_progress' => "false"
                    )
                );
                get_template_part('/templates/shared/audio/audioPlayer');
                wp_reset_query();
            ?> 
            <div class="albumAudio__copy-wrapper">  
                <?php if (!empty($audio['title'])): ?>
                    <p class="albumAudio__title spacing__mtn styles__sequel"><?=$audio['title']?></p>
                <?php endif;
                if (!empty($audio['desc'])): ?>
                    <p class="albumAudio__desc styles__libre-franklin--regular"><?=$audio['desc']?></p>
                <?php endif; ?>
            </div>
        </div> 
    </div>
</div>