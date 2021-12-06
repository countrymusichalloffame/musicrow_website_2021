<?php
add_action('acf/init', 'register_all_blocks');

function register_all_blocks() {

    function lbcore_block_categories( $categories, $post ) {
        return array_merge(
            array(
                array(
                    'slug' => 'lbcore',
                    'title' => __( 'LBCORE', 'lbcore' )
                ),
            ),
            $categories
        );
    }
    add_filter( 'block_categories', 'lbcore_block_categories', 10, 2 );

    if (function_exists('acf_register_block')) {

        /*
        // Block: Example Block
        acf_register_block([
            'name'              => 'example',
            'title'             => __('Example'),
            'description'       => __('An example of how to include a block.'),
            'render_template'   => 'blocks/example/example.php',
            'mode' => 'edit',
            'category'          => 'lbcore'
        ]);
        */

        // Block: Patterns
        acf_register_block([
            'name' => 'patterns',
            'title' => __('Patterns'),
            'description' => __('Patterns Page.'),
            'render_template' => 'templates/shared/blocks/patterns/patterns.php',
            'mode' => 'edit',
            'category' => 'lbcore',
            'icon' => 'dashicon-plus'
        ]);
    }
}