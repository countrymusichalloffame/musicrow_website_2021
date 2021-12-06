<?php 
    /* Expected data format: 
        array(
            'image' => [
                'url' => '/path/to/image.jpg',
                'alt' => 'Descriptive text about the image.'
                'imgix' => false
            ],
            'imgClass' => 'class_names',
            'prependImgix' => true
        )

    Set 'prependImgix' to true if the URL is relative to the IMGIX server.
    */ 
    
    // placeholder default
    $image = lbcore_get_placeholder_img();

    if (!empty($data['image'])) {
        $image = $data['image'];
        
        $image['url'] = $data['image']['url'];
    }

    $imageClass = !empty($data['imgClass']) ? $data['imgClass'] : '';
    //$imgix = !empty($data['prependImgix']) ? $data['prependImgix'] : true; // Need to hookup?? 

    // end

    $params = array(
        'crop' => !empty($image['focal-point']) ? $image['focal-point'] : 'fit=crop&crop=faces,entropy,center',
        'other' => !empty($image['additional-params']) ? $image['additional-params'] : 'q=70&auto=format&or=0&fm=jpeg'
    );
    $paramStr = '?';
    $isCropped = isset($data['crop']) ? $data['crop'] : true;
    
    if($isCropped) {
        $paramStr .= $params['crop'] . '&' . $params['other'];
    } else {
        $paramStr .= $params['other'];
    }

    // QUESTION HOW DO WE HOOK THIS PART UP FOR THIS SITE?

    /*if(env('DEVELOPMENT_MODE')) {
        if(empty($image)) {
            echo "No image variable.";
            return;
        } else if (isset($image) && empty($image['url'])) {
            echo "No image url.";
            return;
        }
    }*/
    
    $original = ( isset($imgix) && $imgix ) || ( isset($image['imgix']) && $image['imgix'] )
        ? env('IMGIX_URL')
        : '';
    $default =  $original . $image['url'] . $paramStr;
    $mobile = null;


    if (isset($image['mobile-focal-point'])) {
        $mobileParams = array(
            'crop' => !empty($image['mobile-focal-point']) ? $image['mobile-focal-point'] : 'fit=crop&crop=faces,entropy,center',
            'other' => !empty($image['mobile-additional-params']) ? $image['mobile-additional-params'] : 'q=70&auto=format&or=0&fm=jpeg'
        );
        $mobileParamStr = '?';
        
        if($isCropped) {
            $mobileParamStr .= $mobileParams['crop'] . '&' . $mobileParams['other'];
        } else {
            $mobileParamStr .= $mobileParams['other'];
        }

        $mobile =  $original . $image['url'] . $mobileParamStr;
    }

    $lazyload = isset($data['lazyload']) ? $data['lazyload'] : true;
?>

<img class="<?= $lazyload ? 'js-dynamic-image lazyload' : ''; ?> <?=!empty($imageClass) ? $imageClass : '' ?>"
    data-original="<?= $default ?>"
    <?= !empty($mobile) ? 'data-mobile=' . $mobile : '' ?>
    src="<?=$default?>"
    data-sizes="auto"
    alt="<?= !empty($image['alt']) ? $image['alt'] : $image['title']; ?>" 

    <?php if(!empty($attributes)):
        foreach($data['attributes'] as $attr) {
            echo $attr['key'] . "=" . $attr['value'];
        }
    endif; ?>
>