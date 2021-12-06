<?php
    /**
     * Simple Audio: 
     *   Has no album image. 
     *   Is either an interview or song.
     */
    $audio = array(
        'title' => !empty($data['title']) ? $data['title'] : '',
        'desc' => !empty($data['desc']) ? $data['desc'] : '',
        'mp3' => !empty($data['mp3']['url']) ? $data['mp3']['url'] : '',
        'type' => !empty($data['type']) ? $data['type'] : '' // song or interview
    );
    $icon = 'audio-' . $data['type'] ?? 'song';

    if (empty($audio['mp3'])) {
        return;
    }
?>

<div class="simpleAudio" data-audio-type="<?=$audio['type']?>">
    <div class="simpleAudio__flex flex">
        <div class="simpleAudio__icon">
            <svg class="icons__icon">
                <use xlink:href="#<?=$icon?>"></use>
            </svg>
        </div>
        <div class="simpleAudio__wrapper">
            <div class="simpleAudio__copy">
                <?php if (!empty($audio['title'])): ?>
                    <p class="simpleAudio__copy-title spacing__mtn">
                        <?=$audio['title']?>
                    </p>
                <?php endif;
                if (!empty($audio['desc'])): ?>
                    <p class="simpleAudio__copy-desc styles__paragraph--small"><?=$audio['desc']?></p>
                <?php endif; ?>
            </div>
            <?php 
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
</div>