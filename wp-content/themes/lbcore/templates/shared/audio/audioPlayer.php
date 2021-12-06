<?php
    /**
     * Audio Player
     * 
     * Contains:
     *   - play / pause buttons
     *   - progress bar
     *   - time stamps.
     */
    $mp3 = !empty($data['mp3']) ? $data['mp3']: '';
    $showProgress = !empty($data['show_progress']) ? $data['show_progress'] : "true";
    $id = uniqid();
?>

<div class="audioPlayer js-audio-player">
    <div class="audioPlayer__wrapper js-audio-player-wrapper">

        <button class="audioPlayer__button button__no-button js-audio-play"
                type="button" 
                data-track="<?= $mp3 ?>"
                data-control="play"
                data-audio-id="audio-player--<?=$id?>">
            <svg class="icons__icon">
                <use xlink:href="#play"></use>
            </svg>
            <span class="presentational__is-hidden">Play Audio</span>
        </button>

        <button class="audioPlayer__button button__no-button js-audio-pause"
                type="button" 
                data-control="pause"
                data-audio-id="audio-player--<?=$id?>">
            <svg class="icons__icon">
                <use xlink:href="#pause"></use>
            </svg>
            <span class="presentational__is-hidden">Pause Audio</span>
        </button>
        
        <?php if ($showProgress=="true"): ?>
            <div class="audioPlayer__progress js-audio-progress" data-audio-id="audio-player--<?=$id?>">
                <input class="audioPlayer__progress-input js-audio-progress-input" 
                        type="range" id="simple-audio-progress"
                        min="1" max="100" value="0" step="any"
                        data-track="<?= $mp3 ?>"
                >
                <label class="presentational__is-hidden" for="simple-audio-progress">Audio Progress</label>

                <p class="audioPlayer__porgress-time">
                    <span class="js-audio-current" data-time="curr"> 00:00</span>
                    &nbsp;/&nbsp;
                    <span class="js-audio-total" data-time="total">00:00</span>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>