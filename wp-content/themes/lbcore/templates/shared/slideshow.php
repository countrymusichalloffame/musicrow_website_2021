<?php 
    $slides = !empty($args) ? $args : [];

    if (empty($slides)) {
        for($i=0; $i<6; $i++) {
            $slides[] = lbcore_get_placeholder_img();    
        }
    }
?>

<div class="slideshow js-hoverintent" id="slideshow">
    <div class="slideshow__wrapper">
        <?php if (!empty($slides)): ?>
            <div class="slideshow__slides">
                <?php foreach($slides as $key => $slide):
                    $slide['image']['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&h=500';
                    set_query_var('data', 
                        array(
                            'image' => $slide['image'],
                            'imgClass' => 'slideshow__figure-img',
                            'crop' => false,
                            'lazyload' => false
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                endforeach; ?>
            </div>
            <div class="slideshow__slides--dupe">
                <!-- repeaters -->
                <?php foreach($slides as $key => $slide):
                    $slide['image']['additional-params'] = 'q=70&auto=format&or=0&fm=jpeg&h=500';
                    set_query_var('data', 
                        array(
                            'image' => $slide['image'],
                            'imgClass' => 'slideshow__figure-img',
                            'crop' => false,
                            'lazyload' => false
                        )
                    );
                    get_template_part('/templates/shared/dynamicImage');
                    wp_reset_query();
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>