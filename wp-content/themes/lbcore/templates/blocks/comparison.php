<?php

    $backgroundImage = $args['background_image']['url'].'?q=70&auto=format&or=0&fm=jpeg&w=1030';
    $thenText = !empty($args['then_text']) ? $args['then_text'] : 'THEN';
    $foregroundImage = $args['foreground_image']['url'].'?q=70&auto=format&or=0&fm=jpeg&w=1030';
    $nowText = !empty($args['now_text']) ? $args['now_text'] : 'NOW';
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
        <p class="comparison__text styles__h7 color--white"> <?=$thenText?> </p>
        <p class="comparison__text styles__h7 color--white"> <?=$nowText?> </p>
    </div>
</div>