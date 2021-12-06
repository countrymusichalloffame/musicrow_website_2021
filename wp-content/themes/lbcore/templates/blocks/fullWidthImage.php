<?php
    $image = $args['image'];
    $overlay = $args['image_overlay'][0];
?>

<div class="fullWidthImage">
    <?php if(!empty($image)): ?> 
        <div class="fullWidthImage__wrapper"
            data-overlay=<?=$overlay=='overlay' ? 'true' : 'false'?>>

            <figure class="fullWidthImage__figure spacing__mtn">
                <?php
                    set_query_var('data', 
                        array(
                            'image' => $image,
                            'imgClass' => 'fullWidthImage__figure-img'
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                ?>
            </figure>
        </div>
    <?php endif; ?>
</div>