<?php

    $backgroundImage = $args['background_image']['url'].'?q=70&auto=format&or=0&fm=jpeg&w=1030';
    $foregroundImage = $args['foreground_image']['url'].'?q=70&auto=format&or=0&fm=jpeg&w=1030';
    $classes = $data['classes'] ?? '';

    if(!$backgroundImage && !$foregroundImage) {
        return;
    }
?>

<div class="comparison js-comparison <?= $classes ?>">
    <div class="comparison__img comparison__background-img" style="background-image: url('<?= $backgroundImage ?>')"></div>
    <div class="comparison__img comparison__foreground-img" style="background-image: url('<?= $foregroundImage ?>')"></div>
    <input type="range" min="1" max="100" value="50" class="comparison__slider js-comparison-slider" name="slider">
    <div class='comparison__slider-button'></div>

    <div class="comparison__text-wrapper">
        <p class="comparison__text styles__h7 color--white"> THEN </p>
        <p class="comparison__text styles__h7 color--white"> NOW </p>
    </div>
</div>