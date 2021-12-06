<?php
    $ctaData = $args['permaCTA'];
    
    $heading = !empty($ctaData['heading']) ? $ctaData['heading'] : ''; 
    $copy = !empty($ctaData['copy']) ? $ctaData['copy'] : ''; 
    $link = !empty($ctaData['cta']) ? $ctaData['cta'] : ''; 
    $primaryLogo = !empty($ctaData['primary_logo']) ? $ctaData['primary_logo'] : ''; 
    $secondaryLogo = !empty($ctaData['secondary_logo']) ? $ctaData['secondary_logo'] : ''; 

    $backgroundType = !empty($ctaData['background_type']) ? $ctaData['background_type'] : '';
    $backgroundImage = $ctaData['background_image'];
    $backgroundOverlay = $ctaData['background_overlay'][0];
?>

<div class="permaCTA">
    <div class="wrappers__permalink">
        <div class="permaCTA__border">
            <div class="permaCTA__logos-wrapper">
                <?php if (!empty($primaryLogo['url'])): ?>
                    <figure class="permaCTA__logo-figure images__figure-ratio ratios__1x1">
                        <?php
                            set_query_var('data', 
                                array(
                                    'image' => $primaryLogo,
                                    'imgClass' => 'permaCTA__logo-img'
                                )
                            );
                            get_template_part('/templates/shared/dynamicImage');
                            wp_reset_query();
                        ?>
                    </figure>
                <?php endif;
                if (!empty($secondaryLogo['url'])): ?>
                    <figure class="permaCTA__logo-figure images__figure-ratio ratios__1x1">
                        <?php
                            set_query_var('data', 
                                array(
                                    'image' => $secondaryLogo,
                                    'imgClass' => 'permaCTA__figure-img'
                                )
                            );
                            get_template_part('/templates/shared/dynamicImage');
                            wp_reset_query();
                        ?>
                    </figure>
                <?php endif; ?>
            </div>
            <div class="permaCTA__textLockup">
                <?php if(!empty($heading)): ?>
                    <h2 class="permaCTA__textLockup-heading styles__h3 spacing__mtn spacing__pbm"><?=$heading?></h2>
                <?php endif; ?>
                <?php if(!empty($copy)): ?>
                    <div class="permaCTA__textLockup-description styles__paragraph--regular spacing__pbl"><?=$copy?></div>
                <?php endif; ?>
                <?php if(!empty($link['url'])): ?>
                    <a class="permaCTA__textLockup-link links__link spacing__mtl" 
                       href=<?=$link['url']?> target="_blank">
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
    <div class="permaCTA__background" 
         data-background=<?=$backgroundType?>
         data-overlay=<?=$backgroundOverlay=='overlay' ? 'true' : 'false'?>>
        <?php if (!empty($backgroundImage)): ?>
            <figure class="permaCTA__background-figure" >
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $backgroundImage,
                            'imgClass' => 'permaCTA__background-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        <?php endif; ?>
    </div>
</div>