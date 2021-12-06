<?php
    $id = get_the_ID(); // ID of the current item in the WordPress Loop

    if ( have_rows( 'flexible_content', $id ) ):
        foreach(get_field('flexible_content') as $block):
            $templatePart = 'templates/blocks/' . $block['acf_fc_layout'];
            
            // if template can't be found in /blocks, check in /blocks/[block-name]/
            if (empty(locate_template($templatePart . '.php'))) {
                $templatePart .= '/' . $block['acf_fc_layout'];
            }
?>
<section class="flexibleBlocks__block" data-template="<?= $block['acf_fc_layout']; ?>">
    <?php get_template_part(
        $templatePart,
        $block['acf_fc_layout'],
        $block
    ); ?>
</section>
<?php   endforeach;
    endif;
?>