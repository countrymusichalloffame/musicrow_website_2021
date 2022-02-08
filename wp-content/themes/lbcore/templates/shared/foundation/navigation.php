<?php
    /* 
     * WP BOILERPLATE NAVIGATION
     */

    $menu = wp_get_nav_menu_object('primary-nav');
    $menuItems = wp_get_nav_menu_items($menu->term_id, array( 'order' => 'DESC' )); 

    /* PRIMARY NAV HOOKUP */
    //$navigation = lbcore_get_navigation($menuItems);
    $navigation = [];

     # last item in nav will be the CTA link
     $lastNavItem = array_pop($navigation);
     $cta = array(
         'copy' => !empty($lastNavItem['simple']['name']) ? $lastNavItem['simple']['name'] : '',
         'url' => !empty($lastNavItem['simple']['url']) ? $lastNavItem['simple']['url'] : ''
     );

    /*  SECONDARY NAV HOOKUP (subnav) */
    $pageParentID = $post->post_parent;
    $subnavigation = [];
    $showSubNav = false;

    # case 1: current page is a child page
    if ($pageParentID != 0) {
        foreach($navigation as $navItem) {
            if ($pageParentID == $navItem['post']->ID) {
                $subnavigation = $navItem['children'];
                $showSubNav = true;
            }
        }
    }
    // case 2: current page has children
    else {
        foreach($navigation as $navItem) {
            if ($navItem['post']->ID == $post->ID && !empty($navItem['children'])) {
                $subnavigation = $navItem['children'];
                $showSubNav = true;
            }
        }
    }

    /* get logo img */
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logoURL = wp_get_attachment_image_src( $custom_logo_id , 'full' )[0];  /* USE WHEN images are hooked up in all environments ***/ 
    //$logoURL = 'https://wpcore.lb2.lifeblue.us/wp-content/uploads/2021/07/motif.png'; 

    $homeURL = get_home_url();

    // get homepage header
    $title = get_field('home_header', '49')['heading'] ?? 'Historic Music Row: Nashville\'s Creative Crossroads';
?>

<div class="navigation">
    <nav class="navigation__flex flex flex__ai--center flex__jc--space-between" id="main-nav" aria-label="Primary Navigation">
        <div class="navigation__logo-container">
            <a class="navigation__logo-link flex flex__ai--center styles__sequel" href="/" rel="home">
                <img class="navigation__logo" src="https://cmhof.imgix.net/content/uploads/2019/04/11072205/cmhof-logo-round.png" alt="Music Row Logo.">  
                <span class="navigation__logo-name"><?=$title?></span>
            </a>
        </div>
        <div class="slideshow__controls">
            <?php
                $controls = ['pause', 'play'];
                foreach($controls as $btn):
            ?>
                <button type="button" class="slideshow__control js-slideshow-control" data-action="<?= $btn; ?>">
                    <span class="slideshow__control-text">
                        <?= ucfirst($btn) . ' Slideshow'; ?>
                    </span>
                    <span class="slideshow__control-icon">
                        <svg class="icons__icon"><use xlink:href="#<?= $btn; ?>"></use></svg>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
</div>