<?php
    $headerData = $args;
    $heading = !empty($headerData['heading']) ? $headerData['heading'] : ''; 
    $copy = !empty($headerData['copy']) ? $headerData['copy'] : ''; 
    $ctaText = !empty($headerData['cta_text']) ? $headerData['cta_text'] : ''; 
?>

<div class="header">

    <div class="header__slideshow-wrapper">
        <?php 
			/* slideshow */
            get_template_part(
                'templates/shared/slideshow',
                'slideshow',
                $headerData['slideshow']
            );
		?>
    </div>
    <div class="header__content">
        <div class="header__flex header__wrapper">
            <?php if(!empty($heading)): ?>
                <h1 class="header__heading styles__h1 color--white spacing__mvn">
                    <?=$heading?>
                </h1>
            <?php endif; ?>
            <div class="header__desc">
                <?php if(!empty($copy)): ?>
                    <p class="header__copy color--white spacing__mtn">
                        <?=$copy?>
                    </p>
                <?php endif; 
                if(!empty($ctaText)): ?>
                    <button class="header__cta links__header button__no-button js-map-jump" type="button">
                        <?=$ctaText?>
                        <svg class="icons__icon">
                            <use xlink:href="#play"></use>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>