<?php
/**
 * Helper functions for our Mapbox integration.
 *
 * @package lbcore
 */

function lbcore_mapbox_shape_popup_content($location, $prev, $next)
{
    $permaURL = get_permalink($location['id']);
    $postTitle = get_the_title($location['id']);
    $html = '<div class="map__popup-content">
    <div class="map__popup-heading">'.$location['name'].'</div>
    <p class="map__popup-text">'.$location['year_display'].'</p>
    <button type="button" class="map__popup-cta button__no-button" 
            data-mb-id="'.$location['id'].'"
            data-prev="'.$prev.'"
            data-next="'.$next.'"
            data-post-permalink="'.$permaURL.'"
            data-post-title="'.$postTitle.'"
        > 
        View Details
        <span><svg class="icons__icon"><use xlink:href="#play"></use></svg></span>
    </button>
    </div>';

    return $html;
}

function lbcore_mapbox_shape_location_data()
{
    $posts = new WP_Query(
        array(
            'numberposts' => -1,
            'post_type' => 'location'
        )
    );

    while ($posts->have_posts()) : $posts->the_post();
        $id = get_the_ID();
        $fields = get_fields($id);
        $name = get_the_title($id);
        $details = $fields['permaHero']['details'];        

        $locations[] = array(
            'id' => $id,
            'type' => $details['building_type']['value'],
            'longitude' => number_format($details['location']['long'], 7),
            'latitude' => number_format($details['location']['lat'], 7),
            'name' => $name,
            'image' => lbcore_get_placeholder_img('small'),
            'year_display' => $details['date']['display_text'] ?? null,
            'year' => $details['date']['year']
        );
    endwhile;

    if(empty($locations)) {
        return;
    }

    foreach($locations as $i => $location) {

        $prev = $i == 0 ? null : $locations[$i - 1]['id'];
        $next = $i == count($locations) - 1 ? null :  $locations[$i + 1]['id'];

        /* only add published posts to next/prev */
        if (get_post_status($prev) != 'publish') {
            $prev = null;
        }
        if (get_post_status($next) != 'publish') {
            $next = null;
        }
        
        if(!empty($location['longitude']) && !empty($location['latitude'])) {
            $markers[] = array(
                'type' => 'Feature',
                'id' => $location['id'],
                'properties' => array(
                    'id' => $location['id'],
                    'type' => $location['type'],
                    'sort' => $i+1,
                    // 'icon' => 'location-' . strval($i+1),
                    // 'icon-active' => 'location-' . strval($i+1) . '-active',
                    'popup' => lbcore_mapbox_shape_popup_content($location, $prev, $next)
                ),
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array(
                        (float) $location['longitude'],
                        (float) $location['latitude']
                    )
                )
            );

            $locationCoordinates[] = array(
                (float) $location['longitude'],
                (float) $location['latitude']
            );

            $cards[] = array(
                'post_id' => $location['id'],
                'name' => $location['name'],
                'year_display' => $location['year_display'],
                'sort_date' => $location['year'],
                'sort_location' => $i,
                'image' => $location['image'],
                'type' => $location['type'],
                'coords' => $location['longitude'] . ',' . $location['latitude']
            );
        };
    }

    return array(
        'markers' => $markers,
        'cards' => $cards
    );
}

function lbcore_mapbox_shape_people_data($geoJSON = array())
{
    $posts = new WP_Query(
        array(
            'numberposts' => -1,
            'post_type' => 'people'
        )
    );

    while ($posts->have_posts()) : $posts->the_post();
        $id = get_the_ID();
        $fields = get_fields($id);
        $name = get_the_title($id);
        $markers = array();
        $acf = array();

        // grab marker data from repeater field
        foreach($fields['locations'] as $location) {
            foreach($geoJSON as $marker) {
                if($marker['id'] == $location['location']) {
                    $markerId = uniqid();
                    $marker['properties']['markerId'] = $markerId;
                    $markers[] = $marker;
                    $acf[$markerId] = $location['content'];
                }
            }
        }

        if (!empty($markers)) {

            $markersNew = array();

            foreach ($markers as $key => $item) {
                $markersNew[$item['id']][$key] = $item;
            }

            ksort($markersNew, SORT_NUMERIC);
            
            $offset = 0;

            foreach($markersNew as $markerGroup) {
                foreach($markerGroup as $i => $marker) {
                    if (count($markerGroup) > 1) {    
                        $marker['geometry']['coordinates'][0] += $offset;
                        // $marker['geometry']['coordinates'][1] += $offset;

                        $offset += .0001;
                    }


                    $markers[$i] = $marker;
                }
            }

            foreach($markers as $i => $marker) {
                $next = null;
                $prev = null;

                if (count($markers) > 1) {
                    if (array_key_exists($i+1, $markers)) {
                        $next = $markers[$i+1]['properties']['markerId'];
                    }
                    
                    if (array_key_exists($i-1, $markers)) {
                        $prev = $markers[$i-1]['properties']['markerId'];
                    }
                }

                // echo "<pre>";
                // print_r($acf);
                // echo "</pre>";
                
                $popupData = array(
                    'name' => $name,
                    'location_id' => $marker['id'],
                    'next' => $next,
                    'prev' => $prev,
                    'pager' => ['count' => $i+1, 'total' => count($markers)],
                    'acf' => $acf[$marker['properties']['markerId']]
                );

                $markers[$i]['properties']['popup'] = load_template_part('templates/shared/artistPopup/artistPopup', null, $popupData);
                $markers[$i]['properties']['sort'] = $i+1;
            }
        }

        $people[] = array(
            'id' => $id,
            'name' => $name,
            'first_name' => $fields['display_name']['first_name'],
            'last_name' => $fields['display_name']['last_name'],
            'markers' => $markers,
            'default_coords' => $markers[0]['geometry']['coordinates'][0] . ',' . $markers[0]['geometry']['coordinates'][1],
            'theme' => $fields['theme']['color_theme']
        );
    endwhile;

    if(empty($people)) {
        return;
    }

    return $people;
}

 // store hardcoded border coordinates
function lbcore_mapbox_shape_border_data() {
    $borderCoords = array(
        [ -86.789761, 36.155508 ],
        [ -86.786111, 36.150724 ],
        [ -86.789057, 36.151038 ],
        [ -86.789158, 36.150458 ],
        [ -86.788994, 36.150408 ],
        [ -86.789268, 36.149147 ],
        [ -86.789853, 36.149172 ],
        [ -86.790103, 36.147892 ],
        [ -86.791657, 36.148055 ],
        [ -86.791657, 36.148055 ],
        [ -86.793864, 36.136223 ],
        [ -86.796327, 36.137078 ],
        [ -86.795814, 36.139595 ],
        [ -86.796447, 36.139671 ],
        [ -86.795042, 36.147010 ],
        [ -86.799280, 36.147443 ],
        [ -86.799124, 36.148575 ],
        [ -86.792882, 36.154282 ],
        [ -86.789704, 36.155612 ],
    );

    return $borderCoords;
}