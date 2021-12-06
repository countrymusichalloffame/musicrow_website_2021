<?php
add_action( 'wp_ajax_nopriv_ajax_get_location', 'ajax_get_location' );
add_action( 'wp_ajax_ajax_get_location', 'ajax_get_location' );
add_action( 'wp_ajax_nopriv_ajax_get_locations', 'ajax_get_locations' );
add_action( 'wp_ajax_ajax_get_locations', 'ajax_get_locations' );

function ajax_get_location() {
    $locationId = lbcore_get_request_string('location_id');
    $prevId = lbcore_get_request_string('prev_id');
    $nextId = lbcore_get_request_string('next_id');
    $postsPerPage = 1;

    /* only add published posts to next/prev */
    if (get_post_status($prevId) != 'publish') {
        $prevId = null;
    }
    if (get_post_status($nextId) != 'publish') {
        $nextId = null;
    }
    
    $permalinkData = prep_permalink_data($locationId, $prevId, $nextId);
    $response = [
        'html' => load_template_part('templates/permalink', null, $permalinkData),
        'blocks' => get_permalink_blocks($permalinkData['acf'])
    ];

    echo json_encode($response);
    wp_die();

}

function ajax_get_locations() {
    $args = array(
        'post_type' => 'location'
    );
    $locations = new WP_Query($args);
    $response = array();
    
    if ($locations->posts)
    {
        foreach($locations->posts as $i => $location)
        {
            $prev = $i == 0 ? null : $locations->posts[$i - 1]->ID;
            $next = $i == count($locations->posts) - 1 ? null :  $locations->posts[$i + 1]->ID;

            /* only add published posts to next/prev */
            if (get_post_status($prev) != 'publish') {
                $prev = null;
            }
            if (get_post_status($next) != 'publish') {
                $next = null;
            }

            $permalinkData = prep_permalink_data( $location->ID, $prev, $next);
            $response[$location->ID] = [
                'html' => load_template_part('templates/permalink', null, $permalinkData),
                'blocks' => get_permalink_blocks($permalinkData['acf'])
            ];
        }
    }

    echo json_encode($response);
    wp_die();

}

function load_template_part($template_name, $part_name=null, $template_data) {
    ob_start();
    get_template_part($template_name, $part_name, $template_data);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}