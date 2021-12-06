<?php 
    /**
     * Moving Story Navigation
     *  Takes user to a Person layer on the Map
     * 
     *  @next : Array. Next Story Information
     *          @next['person'] : person layer id 
     *          @next['button_text'] : text for button
     *  
     *  @btn__wrapper_classes : String. Additional classes for button wrapper.
     *          Ex. flex classes
     * 
     *  @btn_classes : String. Additional classes for each next/ prev button.
     *          
     */
    $nextStory = !empty($data['next']) ? $data['next'] : '';
    $btnWrapperClasses = !empty($data['btn_wrapper_classes']) ? $data['btn_wrapper_classes'] : '';
    $btnClasses = !empty($data['btn_classes']) ? $data['btn_classes'] : '';

?>

<div class="movingStory js-moving-story">
    <div class="movingStory__button-wrapper <?=$btnWrapperClasses?>">
        <?php if(!empty($nextStory['button_text'])):?>
            <button type="button" 
                class="movingStory__button 
                    button__no-button 
                    links__link 
                    js-story-btn
                    <?=$btnClasses?>"
                data-person=<?=$nextStory['person']?>
            >
                <?=$nextStory['button_text']?>
                <svg class="icons__icon">
                    <use xlink:href="#play"></use>
                </svg>    
            </button>
        <?php endif;?>
    </div>
</div>