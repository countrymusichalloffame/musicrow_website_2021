<?php
    $ctaData = $args;
    
    $heading = !empty($ctaData['heading']) ? $ctaData['heading'] : ''; 
    $copy = !empty($ctaData['copy']) ? $ctaData['copy'] : ''; 
    $link = !empty($ctaData['cta']) ? $ctaData['cta'] : ''; 
    $primaryLogo = !empty($ctaData['primary_logo']) ? $ctaData['primary_logo'] : ''; 
    $secondaryLogo = !empty($ctaData['secondary_logo']) ? $ctaData['secondary_logo'] : ''; 
    
    $backgroundType = !empty($ctaData['background_type']) ? $ctaData['background_type'] : '';
    $backgroundImage = $ctaData['background_image'];
?>

<div class="cta">
    <div class="wrappers__wrapper">
        <div class="cta__border">
            <div class="cta__logos-wrapper">
                <?php if (!empty($primaryLogo['url'])): ?>
                    <figure class="cta__logo-figure images__figure-ratio ratios__1x1">
                        <?php
                            set_query_var('data', 
                                array(
                                    'image' => $primaryLogo,
                                    'imgClass' => 'cta__logo-img'
                                )
                            );
                            get_template_part('/templates/shared/dynamicImage');
                            wp_reset_query();
                        ?>
                    </figure>
                <?php endif;
                if (!empty($secondaryLogo['url'])): ?>
                    <figure class="cta__logo-figure images__figure-ratio ratios__1x1">
                        <?php
                            set_query_var('data', 
                                array(
                                    'image' => $secondaryLogo,
                                    'imgClass' => 'cta__figure-img'
                                )
                            );
                            get_template_part('/templates/shared/dynamicImage');
                            wp_reset_query();
                        ?>
                    </figure>
                <?php endif; ?>
            </div>
            <div class="cta__textLockup">
                <?php if(!empty($heading)): ?>
                    <h2 class="cta__heading spacing__mtn styles__sequel color--gold"><?=$heading?></h2>
                <?php endif; ?>
                <?php if(!empty($copy)): ?>
                    <p class="cta__description styles__paragraph--large spacing__mvl"><?=$copy?></p>
                <?php endif; ?>
                <?php if(!empty($link['url'])): ?>
                    <a class="cta__link links__link spacing__mtn"  href=<?=$link['url']?> target="_blank">
                        <?=$link['title']?>
                        <span>
                            <svg class="icons__icon">
                                <use xlink:href="#play"></use>
                            </svg>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="cta__background"
         data-background=<?=$backgroundType?>>
        <figure class="cta__background-figure images__figure-ratio ratios__1x1">
            <?php
                set_query_var('data', 
                    array(
                        'image' => $backgroundImage,
                        'imgClass' => 'cta__background-img'
                    )
                );
                get_template_part('/templates/shared/dynamicImage');
                wp_reset_query();
            ?>
        </figure>
    </div>
</div>