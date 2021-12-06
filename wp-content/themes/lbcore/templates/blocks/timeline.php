<?php

    $heading = !empty($args['timeline']['heading']) ? $args['timeline']['heading'] : '';
    $desc = !empty($args['timeline']['description']) ? $args['timeline']['description'] : '';
    $sections = !empty($args['timeline']['dates']) ? $args['timeline']['dates']: [];

?>

<div class="timeline">
    <div class="wrappers__permalink">

        <div class="timeline__heading">
            <?php if(!empty($heading)): ?>
                <h2 class="timeline__heading-title styles__h2"><?= $heading; ?></h2>
            <?php endif;
            if (!empty($desc)): ?>
                <p class="timeline__heading-desc styles__paragraph--regular"><?=$desc?></p>
            <?php endif; ?>
        </div>
        
        <div class="timeline__sections-wrapper <?=empty($heading) && empty($desc) ? 'spacing__mtn' : ''?>">
            <?php foreach($sections as $section):
                $nextStory = !empty($section['moving_story_clone']['next_story']) ?  $section['moving_story_clone']['next_story'] : '';
                foreach($section['content'] as $key => $sectionBlock): ?>
                    <div class="timeline__section" 
                         data-media-type=<?=!empty($sectionBlock['media_type']) ? $sectionBlock['media_type'] : 'null'?>
                         data-show-date=<?= $key==0 ? 'true' : 'false' ?>>
                        <?php if ($key == 0): ?>
                            <h3 class="timeline__section-year styles__h3">
                                <?= $section['year'] ?>
                            </h3>
                        <?php endif;
                        
                        /** vertical image & circular image */
                        $isCircular = $sectionBlock['media_type'] == 'circularImage';
                        $isHalfImage = $sectionBlock['media_type'] == 'halfImage';
                        $image = $isHalfImage ? $sectionBlock['image'] : $sectionBlock['circular_image'];

                        if($isHalfImage || $isCircular): ?>    
                            <div class="grid__row">
                                <div class="grid__med-7of12 grid__lg-7of12">
                                    <?php if (!empty($image)): ?>
                                        <figure class="timeline__figure spacing__mtn
                                                <?=$isCircular ? 'images__figure-ratio ratios__1x1' : ''?>" 
                                                data-circular="<?=$isCircular ? 'true' : 'false'?>">
                                            <?php
                                                //$image['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&w=500';
                                                set_query_var('data', 
                                                    array(
                                                        'image' => $image,
                                                        'imgClass' => 'timeline__figure-img',
                                                        'crop' => $isCircular ? true : false
                                                    )
                                                );
                                                get_template_part('/templates/shared/dynamicImage');
                                                wp_reset_query();
                                            if(!empty($image['caption'])): ?>
                                                <figcaption class="styles__paragraph--caption color--caption-black">
                                                    <?=$image['caption']?>
                                                </figcaption>
                                            <?php endif; 
                                            if ($isHalfImage) :?>
                                                <button class="timeline__modal-btn modal__open js-modal-open" 
                                                    data-modal="modal--lightbox"
                                                    data-img-src="<?=$image['url']?>"
                                                    data-img-alt="<?=$image['alt']?>"
                                                    data-img-caption="<?=$image['caption']?>">
                                                    <span class="presentational__is-hidden">
                                                        Click to enlarge image.
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </figure>
                                    <?php endif; ?> 
                                </div>
                                <div class="grid__med-5of12 grid__lg-5of12">
                                    <div class="timeline__section-heading">
                                        <?php if(!empty($sectionBlock['heading'])): ?>          
                                            <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                        <?php endif;
                                        if (!empty($storyText)): ?>
                                            <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                        <?php endif;?>
                                    </div> 
                                    <?php if(!empty($sectionBlock['description'])): ?>
                                        <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                    <?php endif; 
                                    /* moving story */
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php /** horizontal imagge **/ 
                        elseif($sectionBlock['media_type'] =='fullImage'): ?>
                            <div class="grid__row">
                                <div class="grid__med-full grid__lg-full">
                                    <?php if(!empty($sectionBlock['image'])): ?>
                                        <figure class="timeline__figure spacing__mtn">
                                            <?php
                                                //$sectionBlock['image']['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&w=900';
                                                set_query_var('data', 
                                                    array(
                                                        'image' => $sectionBlock['image'],
                                                        'imgClass' => 'timeline__figure-img',
                                                        'crop' => false
                                                    )
                                                );
                                                get_template_part('/templates/shared/dynamicImage');
                                                wp_reset_query();
                                            if(!empty($sectionBlock['image']['caption'])): ?>
                                                <figcaption class="styles__paragraph--caption color--caption-black">
                                                    <?=$sectionBlock['image']['caption']?>
                                                </figcaption>
                                            <?php endif; ?>
                                            <button class="timeline__modal-btn modal__open js-modal-open" 
                                                data-modal="modal--lightbox"
                                                data-img-src="<?=$sectionBlock['image']['url']?>"
                                                data-img-alt="<?=$sectionBlock['image']['alt']?>"
                                                data-img-caption="<?=$sectionBlock['image']['caption']?>">
                                                <span class="presentational__is-hidden">
                                                    Click to enlarge image.
                                                </span>
                                            </button>
                                        </figure>
                                    <?php endif; ?>
                                    <div class="timeline__section-heading">
                                        <?php if(!empty($sectionBlock['heading'])): ?>          
                                            <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                        <?php endif;
                                        if (!empty($storyText)): ?>
                                            <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                        <?php endif;?>
                                    </div> 
                                    <?php if(!empty($sectionBlock['description'])): ?>
                                        <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                    <?php endif; 
                                        /* moving story */
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php /** album audio **/
                        elseif($sectionBlock['media_type'] =='albumAudio'): ?>
                            <div class="grid__row">
                                <div class="grid__med-5of12 grid__lg-5of12">
                                    <?php 

                                    $albumImage = $sectionBlock['album_audio']['album_image'];
                                    //$albumImage['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&w=500';

                                    if (!empty($albumImage)): ?>
                                        <figure class="timeline__albumAudio-figure images__figure-ratio ratios__1x1">
                                            <?php
                                                set_query_var('data', 
                                                    array(
                                                        'image' => $albumImage,
                                                        'imgClass' => 'timeline__albumAudio-img albumAudio__figure-img',
                                                        'crop' => false
                                                    )
                                                );
                                                get_template_part('/templates/shared/dynamicImage');
                                                wp_reset_query();
                                            ?>
                                        </figure>
                                    <?php endif;  ?>
                                </div>
                                <div class="grid__med-7of12 grid__lg-7of12">
                                    <div class="timeline__albumAudio flex flex__ai--baseline">
                                        <?php 
                                            /* audio player */
                                            set_query_var('data', 
                                                array(
                                                    'mp3' => $sectionBlock['album_audio']['audio_file']['url'],
                                                    'show_progress' => "false"
                                                )
                                            );
                                            get_template_part('/templates/shared/audio/audioPlayer');
                                            wp_reset_query();
                                        ?>
                                        <div class="timeline__albumAudio-copy spacing__mll">                           
                                            <div class="timeline__section-heading">
                                                <?php if(!empty($sectionBlock['heading'])): ?>          
                                                    <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                                <?php endif;
                                                if (!empty($storyText)): ?>
                                                    <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                                <?php endif;?>
                                            </div> 
                                            <?php if(!empty($sectionBlock['description'])): ?>
                                                <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                            <?php endif; 
                                                /* moving story */
                                                set_query_var('data', 
                                                    array(
                                                        'next' => $nextStory,
                                                        'btn_classes' => 'links__link--small',
                                                    )
                                                );
                                                get_template_part('/templates/shared/movingStory');
                                                wp_reset_query();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php /** simple audio */
                        elseif($sectionBlock['media_type'] =='simpleAudio'): ?>
                            <div class="grid__row">
                                <div class="grid__med-full grid__lg-full">
                                    <div class="timeline__section-simpleAudio">
                                        <?php
                                            set_query_var('data', 
                                                array(
                                                    'title' => $sectionBlock['simple_audio']['audio_title'], 
                                                    'desc' => $sectionBlock['simple_audio']['audio_description'],
                                                    'mp3' => $sectionBlock['simple_audio']['audio_file'],
                                                    'type' => $sectionBlock['simple_audio']['audio_type']
                                                )
                                            );
                                            get_template_part('/templates/shared/audio/simpleAudio');
                                            wp_reset_query();
                                        ?>
                                    </div>
                                </div>
                                <div class="grid__med-full grid__lg-full">
                                    <div class="timeline__section-heading">
                                        <?php if(!empty($sectionBlock['heading'])): ?>          
                                            <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                        <?php endif;
                                        if (!empty($storyText)): ?>
                                            <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                        <?php endif;?>
                                    </div> 
                                    <?php if(!empty($sectionBlock['description'])): ?>
                                        <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                    <?php endif;
                                        /* moving story */
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php /** video */
                        elseif($sectionBlock['media_type'] =='video'): ?>
                            <div class="grid__row">
                                <div class="grid__med-full grid__lg-full">
                                    <figure class="timeline__figure spacing__mtn">
                                        <?php
                                            set_query_var('data', 
                                                array(
                                                    'video_source' => $sectionBlock['video'],
                                                    'classes' => 'images__figure-ratio ratios__2x1'
                                                )
                                            );
                                            get_template_part('/templates/shared/video');
                                            wp_reset_query();
                                        if(!empty($sectionBlock['video']['caption'])): ?>
                                            <figcaption class="styles__paragraph--caption color--caption-black">
                                                <?=$sectionBlock['video']['caption']?>
                                            </figcaption>
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <div class="grid__med-full grid__lg-full">
                                    <div class="timeline__section-heading">
                                        <?php if(!empty($sectionBlock['heading'])): ?>          
                                            <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                        <?php endif;
                                        if (!empty($storyText)): ?>
                                            <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                        <?php endif;?>
                                    </div> 
                                    <?php if(!empty($sectionBlock['description'])): ?>
                                        <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                    <?php endif;
                                        /* moving story */
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php /** no media: default **/
                        else: ?>
                            <div class="grid__row">
                                <div class="grid__med-full grid__lg-full">
                                    <div class="timeline__section-heading">
                                        <?php if(!empty($sectionBlock['heading'])): ?>          
                                            <h4 class="timeline__section-title"><?= $sectionBlock['heading'] ?></h4>
                                        <?php endif;
                                        if (!empty($storyText)): ?>
                                            <p class="timeline__section-story-text movingStory__text"><?=$storyText?></p>
                                        <?php endif;?>
                                    </div> 
                                    <?php if(!empty($sectionBlock['description'])): ?>
                                        <div class="timeline__section-desc"><?= $sectionBlock['description'] ?></div>
                                    <?php endif; 
                                        /* moving story */
                                        set_query_var('data', 
                                            array(
                                                'next' => $nextStory,
                                                'btn_classes' => 'links__link--small',
                                            )
                                        );
                                        get_template_part('/templates/shared/movingStory');
                                        wp_reset_query();
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
            endforeach; ?>
        </div>
    </div>
</div>
