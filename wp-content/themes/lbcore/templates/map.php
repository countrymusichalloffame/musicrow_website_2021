<?php
    $locationData = lbcore_mapbox_shape_location_data();
    $peopleData = lbcore_mapbox_shape_people_data($locationData['markers']);
    $borderData = lbcore_mapbox_shape_border_data();
?>

<section class="map js-map">
    <div class="map__scroll-overlay">
        <div class="map__sidebar js-map-sidebar"> <!--js-map-sidebar-->
            <div class="map__sidebar-controls js-map-sidebar-controls" data-active-sort="places">
                <p class="map__sidebar-label styles__sequel spacing__mtn">Explore</p> 
                <ul class="map__sort lists__unstyled flex flex__jc-space-between spacing__mtn">
                    <li class="map__sort-item flex__col">
                        <button type="button" class="map__sort-btn is-active button__primary js-map-sort" data-by="places">
                            Places
                        </button>
                    </li>
                    <li class="map__sort-item flex__col">
                        <button type="button" class="map__sort-btn button__primary js-map-sort" data-by="people">
                            People
                        </button>
                    </li>
                </ul>
                <p class="map__sidebar-desc styles__paragraph--small color--white" data-sort="places">
                    Select a location to trace its history through videos, audio, historic photos, and more.
                </p>
                <p class="map__sidebar-desc styles__paragraph--small color--white" data-sort="people">
                    Select a person to follow in the footsteps of key figures in Music Row history.
                </p>    
            </div>
            <!-- location cards -->
            <ul class="map__cards lists__unstyled js-map-cards" data-by="places">
                <?php foreach($locationData['cards'] as $i => $card): ?>
                    <li class="map__card-item">
                        <?php
                            $prev = $i == 0 ? null : $locationData['cards'][$i - 1]['post_id'];
                            $next = $i == count($locationData['cards']) - 1 ? null :  $locationData['cards'][$i + 1]['post_id'];

                            $theme = get_building_theme_color($card['type']);
                        ?>
                        <button class="map__card button__no-button js-map-card theme--<?=$theme?>" 
                            data-mb-coords="<?= $card['coords']; ?>"
                            data-mb-id="<?= $card['post_id']; ?>"
                            data-mb-sort="<?= $card['sort_location']; ?>"
                            data-prev="<?=$prev?>"
                            data-next="<?=$next?>"
                            data-mb-layer-sort="places"
                            data-post-permalink="<?=get_permalink($card['post_id'])?>"
                            data-post-title="<?=get_the_title($card['post_id'])?>"
                        >
                            <figure class="map__card-figure presentational__relative-container spacing__mtn images__figure-ratio">
                                <?php
                                    $featuredImage = array(
                                        'url' => get_the_post_thumbnail_url( $card['post_id'], 'large' ),
                                        'alt' => $card['name'] . '.'
                                    );
                                    set_query_var('data', 
                                        array(
                                            'image' => !empty($featuredImage['url']) ? $featuredImage : null,
                                            'imgClass' => 'map__card-img'
                                        )
                                    );
                                    get_template_part('/templates/shared/dynamicImage');
                                    wp_reset_query();
                                ?>
                            </figure>
                            <div class="map__card-text" aria-label="Location card name and description.">
                                <p class="map__card-order js-map-card-order styles__sequel color--action-gold spacing__mtn">
                                    <?= $i+1 ;?>
                                </p>
                                <div>
                                    <p class="map__card-name">
                                        <?=$card['name']; ?>
                                    </p>
                                    <?php if(!empty($card['year_display'])): ?>
                                        <p class="map__card-desc spacing__mts">
                                            <?= $card['year_display']; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- people cards -->
            <ul class="map__cards lists__unstyled js-map-cards" data-by="people">
                <?php foreach($peopleData as $i => $person): ?>
                    <li class="map__card-item">
                        <button class="map__card button__no-button js-map-card theme--<?=$person['theme']?>" 
                                data-mb-id="<?= $person['id'] ?>"
                                data-mb-coords="<?= $person['default_coords']?>"
                                data-mb-sort="<?=$i?>"
                                data-mb-layer-sort="people"
                                data-post-permalink="<?=get_permalink($person['id'])?>"
                                data-post-title="<?=get_the_title($person['id'])?>"
                            >
                            <figure class="map__card-figure--round images__figure-ratio ratios__1x1 spacing__mtn">
                                <?php
                                    $featuredImage = array(
                                        'url' => get_the_post_thumbnail_url( $person['id'], 'large' ),
                                        'alt' => get_the_title($person['id'])
                                    );
                                    set_query_var('data', 
                                        array(
                                            'image' => !empty($featuredImage['url']) ? $featuredImage : null,
                                            'imgClass' => 'map__card-img map__card-img--people'
                                        )
                                    );
                                    get_template_part('/templates/shared/dynamicImage');
                                    wp_reset_query();
                                ?>
                            </figure>
                            <div class="map__card-name">
                                <span class="map__card-name--first profileCarousel__slide-heading color--white">
                                    <?= $person['first_name']; ?>
                                </span>
                                <br>
                                <span class="map__card-name--last profileCarousel__slide-heading--lastName">
                                    <?= $person['last_name']; ?>
                                </span>
                            </div>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="map__sticky js-map-sticky">
        <div class="map__permalink js-map-permalink" data-location>
            <button type="button" class="map__permalink-close button__close button__close--small button__close--dark js-permalink-close">
                <svg class="icons__icon">
                    <use xlink:href="#close"></use>
                </svg>
                <span class="presentational__is-hidden">Close Map.</span>
            </button>
            <div class="presentational__relative-container js-permalink-inner"></div>
        </div>
        <div class="map__mapbox" id="mapbox"></div>
    </div>
</section>
<?php
    $script = '<script>
	window.MusicRow.locations = ' . json_encode($locationData['markers']) .'
    window.MusicRow.people = ' . json_encode($peopleData) .'
    window.MusicRow.borders = ' . json_encode($borderData) .'
	</script>';

    echo $script;
?>