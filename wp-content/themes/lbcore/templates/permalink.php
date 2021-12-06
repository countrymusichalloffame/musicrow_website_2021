<?php
    /** Get permaHero block */
    $hero = isset($args['acf']['permaHero']) ? $args['acf']['permaHero'] : '';   
    $hero['jump_link_id'] =  uniqid('permalink-' . $hero['acf_fc_layout'] . '--'); 

    /** Get theme from building type */
    $buildingType = $hero['details']['building_type']['value'];
    $theme = get_building_theme_color($buildingType);

    /** Get Jumplinks for permaHero and legoblocks */
    $jumplinks = [];
    $jumplinkText = []; // to easily check if there are no jump_link_text values from CMS

    $jumplinks[] = array(
        'id' => $hero['jump_link_id'],
        'text' => $hero['jump_link_text']
    );
    $jumplinkText[] = $hero['jump_link_text'];

    foreach($args['acf']['flexible_content'] as &$block) {
        $block['jump_link_id'] =  uniqid('permalink-' . $block['acf_fc_layout'] . '--');
        if (!empty($block['jump_link_text'])) {
            $jumplinks[] = array(
                'id' => $block['jump_link_id'],
                'text' => $block['jump_link_text']
            );
            $jumplinkText[] = $block['jump_link_text'];
        }
    }
    // filter out jumplink text null values
    $jumplinkText = array_filter($jumplinkText);
    $dropdownId = uniqid('jumplinks-');
?>

<div class="permalink theme--<?=$theme?> js-jumplinks">
    <?php /* jumplinks */
    if(!empty($jumplinkText)): ?>
        <div class="permalink__jumplinks-wrapper color--white">
            <button type="button" id="<?= $dropdownId . '-trigger'; ?>" class="permalink__jumplinks-reveal
                button__no-button
                spacing__pvl
                presentational__align-center
                styles__sequel
                hierarchy__non-pariel-text
                js-jumplinks-reveal"
                data-reveal="<?= $dropdownId; ?>"
            >
                Jump To
                <svg class="icons__icon">
                    <use xlink:href="#arrow-down"></use>
                </svg>
            </button>
            <div class="permalink__jumplinks-dropdown" id="<?= $dropdownId; ?>">
                <?php 
                $isFirst = false;
                foreach($jumplinks as $jumplink): 
                    if (!empty($jumplink['text'])): 
                        $isFirst = !$isFirst ? 'true' : 'false'?>
                        <button class="permalink__jumplink links__link button__no-button js-jumplink-trigger <?=$isFirst == 'true' ? 'js-jumplink-is-first' : ''?>" 
                            type="button" 
                            data-trigger="<?=$jumplink['id']?>"
                            data-reveal-for="<?= $dropdownId . '-trigger'; ?>"
                        >
                            <?=$jumplink['text']?>
                        </button>
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
    <?php endif;
    /* permaHero block */
    if( is_array($hero) && !empty($hero['heading']) ): ?>
        <div class="permalink__block js-jumplink-target" data-block="permaHero" 
             data-target="<?=$hero['jump_link_id']?>"> 
            <?php get_template_part(
            'templates/blocks/permaHero',
                'permaHero',
                $hero
            ); ?>
        </div>    
    <?php endif;
    /* lego blocks */
    foreach($args['acf']['flexible_content'] as &$block):
        $templatePart = 'templates/blocks/' . $block['acf_fc_layout'];
        // if template can't be found in /blocks, check in /blocks/[block-name]/
        if (empty(locate_template($templatePart . '.php'))) {
            $templatePart .= '/' . $block['acf_fc_layout'];
        }
        ?>
        <div class="permalink__block js-jumplink-target" data-block="<?= $block['acf_fc_layout']; ?>" 
             data-target="<?=$block['jump_link_id']?>">
            <?php get_template_part(
                $templatePart,
                $block['acf_fc_layout'],
                $block
            ); ?>
        </div>
    <?php endforeach;
    /* NextPrev cards */
        set_query_var('data', 
            array(
                'cards' => [
                    'prev' => $args['prev'],
                    'next' => $args['next']
                ]
            )
        );
        get_template_part('/templates/blocks/nextPrev');
        wp_reset_query();
    ?>   
</div>