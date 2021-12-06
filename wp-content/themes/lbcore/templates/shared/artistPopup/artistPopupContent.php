<div class="artistPopupContent" data-media-type="<?$args['media_type'] ?? ''?>">
    <div class="grid__lg-row grid__med-row">
        <?php /* image */
        if($args['media_type'] == 'halfImage'): ?>    
            <div class="grid__med-5of12 grid__lg-5of12">
                <?php if (!empty($args['image'])): 
                    $args['image']['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&h=auto';
                ?>
                    <figure class="timeline__figure spacing__mtn" data-circular="false">
                        <?php
                            set_query_var('data', 
                                array(
                                    'image' => $args['image'],
                                    'imgClass' => 'timeline__figure-img'
                                )
                            );
                            get_template_part('/templates/shared/dynamicImage');
                            wp_reset_query();
                        if(!empty($args['image']['caption'])): ?>
                            <figcaption class="styles__paragraph--caption color--caption-black">
                                <?=$args['image']['caption']?>
                            </figcaption>
                        <?php endif; ?>
                        <button class="timeline__modal-btn modal__open js-modal-open" 
                            data-modal="modal--lightbox"
                            data-img-src="<?=$args['image']['url']?>"
                            data-img-alt="<?=$args['image']['alt']?>"
                            data-img-caption="<?=$args['image']['caption']?>">
                            <span class="presentational__is-hidden">
                                Click to enlarge image.
                            </span>
                        </button>
                    </figure>
                <?php endif; ?> 
            </div>
            <div class="grid__med-7of12 grid__lg-7of12">
                <?php if(!empty($args['heading'])): ?>          
                    <div class="timeline__section-heading">
                        <h4 class="timeline__section-title"><?= $args['heading'] ?></h4>
                    </div> 
                <?php endif; ?>
                <?php if(!empty($args['description'])): ?>
                    <div class="timeline__section-desc styles__paragraph--regular"><?= $args['description'] ?></div>
                <?php endif; ?>
            </div>
        <?php /* audio */
        elseif($args['media_type'] =='albumAudio' || $args['media_type'] =='simpleAudio'): ?>
            <div class="grid__med-5of12 grid__lg-5of12">
                <?php 
                    $argsType = $args['media_type'] =='albumAudio' ? 'album_audio' : 'simple_audio';
                    $title = $args[$argsType]['audio_title'];
                    $desc = $args[$argsType]['audio_description'];
                    $mp3 = $args[$argsType]['audio_file']['url'];
                    $reelImage = array(
                        'url' =>  '/wp-content/themes/lbcore/resources/images/reel-recorder.png',
                        'alt' => 'Reel recorder illustration.'
                    );
                    $albumImage = $args['media_type'] =='albumAudio' ? $args['album_audio']['album_image'] : $reelImage;
                    $albumImage['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&w=500';

                    if (!empty($albumImage)): ?>
                        <figure class="timeline__albumAudio-figure images__figure-ratio ratios__1x1"
                                data-audio-type="<?=$argsType?>">
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
                    <?php endif;
                ?>                        
            </div>
            <div class="grid__med-7of12 grid__lg-7of12">
                <div class="timeline__albumAudio flex flex__ai--baseline">
                    <?php 
                        /* audio player */
                        set_query_var('data', 
                            array(
                                'mp3' => $mp3,
                                'show_progress' => "false"
                            )
                        );
                        get_template_part('/templates/shared/audio/audioPlayer');
                        wp_reset_query();
                    ?>
                    <div class="timeline__albumAudio-copy spacing__mll">                           
                        <div class="timeline__section-heading">
                            <?php if(!empty($args['heading'])): ?>          
                                <h4 class="timeline__section-title"><?= $args['heading'] ?></h4>
                            <?php endif; ?>
                        </div> 
                        <?php if(!empty($args['description'])): ?>
                            <div class="timeline__section-desc styles__paragraph--regular">
                                <?= $args['description'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php /* video */
        elseif($args['media_type'] =='video'): ?>
            <div class="grid__med-5of12 grid__lg-5of12">
                <figure class="timeline__figure spacing__mtn">
                    <?php
                        set_query_var('data', 
                            array(
                                'video_source' => $args['video'],
                                'classes' => 'images__figure-ratio ratios__2x1'
                            )
                        );
                        get_template_part('/templates/shared/video');
                        wp_reset_query();
                    if(!empty($args['video']['caption'])): ?>
                        <figcaption class="styles__paragraph--caption color--caption-black">
                            <?=$args['video']['caption']?>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            </div>
            <div class="grid__med-7of12 grid__lg-7of12">
                <?php if(!empty($args['heading'])): ?>          
                    <div class="timeline__section-heading">
                        <h4 class="timeline__section-title"><?= $args['heading'] ?></h4>
                    </div> 
                <?php endif; ?>
                <?php if(!empty($args['description'])): ?>
                    <div class="timeline__section-desc styles__paragraph--regular"><?= $args['description'] ?></div>
                <?php endif; ?>
            </div>
        <?php /** no media **/ 
            else: ?>
            <div class="grid__med-full grid__lg-full">
                <?php if(!empty($args['heading'])): ?>          
                    <div class="timeline__section-heading">
                        <h4 class="timeline__section-title"><?= $args['heading'] ?></h4>
                    </div> 
                <?php endif; ?>
                <?php if(!empty($args['description'])): ?>
                    <div class="timeline__section-desc styles__paragraph--regular"><?= $args['description'] ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>