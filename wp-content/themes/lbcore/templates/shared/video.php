<?php
    $videoUrl = $data['video_source']['url'];
    if(!$videoUrl) {
        return;
    }
    $classes = $data['classes'] ?? '';

    $videoUrl .= '#t=2'; // TEMP? sets the time to 2seconds so we can get an initial cover photo.
    // $coverPhoto = lbcore_get_placeholder_img()['url'];
?>

<div class="video js-video">

    <video class="video__element js-video-element <?=$classes?>" 
           controls preload="metadata" tabIndex="0">
        <source src="<?= $videoUrl; ?>" type="video/mp4">
        Sorry, your browser doesn't support embedded videos.
    </video>

    <div class="video__icon-container">
        <div class="video__icon-border">
            <svg class="video__icon icons__icon">
                <use xlink:href="#play"></use>
            </svg>
        </div>
    </div>

</div>
