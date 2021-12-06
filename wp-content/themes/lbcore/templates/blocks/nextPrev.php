<?php
    $cards = !empty($data['cards']) ? $data['cards'] : [];
    $hasPrev = $data['cards']['prev'] == 'null' || $data['cards']['prev'] == null ? 'false' : 'true';
    $hasNext = $data['cards']['next'] == 'null' || $data['cards']['next'] == null ? 'false' : 'true';
?>

<div class="nextPrev js-nextPrev">
    <div class="wrappers__permalink">
        <div class="nextPrev__flex" data-has-prev="<?=$hasPrev?>" data-has-next="<?=$hasNext?>">

            <?php foreach($cards as $key => $card): 
                if($card != 'null' && $card != null): 
                    $title = get_field('permaHero', $card)['heading'] ?? '';
                    $copy = get_field('permaHero', $card)['details']['date']['display_text'] ?? '';
                    $image = array(
                        'url' => get_the_post_thumbnail_url( $card, 'full' ),
                        'alt' => $title . '.'
                    );
                    $linkText = $key == 'prev' ? 'Previous' : 'Next';
                    $linkURL = ''; ?>

                    <div class="nextPrev__card js-nextPrev-card" data-direction="<?=$key?>">
                        <div class="nextPrev__card-border">
                            <figure class="nextPrev__card-figure images__figure-ratio ratios__4x3">
                                <?php
                                    set_query_var(
                                        'data', 
                                        array(
                                            'image' => !empty($image['url']) ? $image : null,
                                            'imgClass' => 'nextPrev__card-img'
                                        )
                                    );
                                    get_template_part('/templates/shared/dynamicImage');
                                    wp_reset_query();
                                ?>
                            </figure>
                            <h3 class="nextPrev__card-title styles__h6"><?=$title?></h3>
                            <p class="nextPrev__card-copy"><?=$copy?></p>
                        </div>
                        <button type="button" 
                                class="nextPrev__card-link js-nextPrev-btn button__no-button links__link links__link--dark spacing__mtl"
                                data-card-link=<?=$card?>
                            >
                            <?php if($key=='prev'): ?>
                                <svg class="icons__icon">
                                    <use xlink:href="#play"></use>
                                </svg>
                            <?php endif; ?>
                            <?=$linkText?>
                            <?php if($key=='next'): ?>
                                <svg class="icons__icon">
                                    <use xlink:href="#play"></use>
                                </svg>
                            <?php endif; ?>
                        </button>
                    </div>           
                <?php endif;
            endforeach; ?>
        </div>
    </div>
</div>