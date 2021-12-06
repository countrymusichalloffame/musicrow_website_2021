<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package lbcore
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function lbcore_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'lbcore_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function lbcore_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'lbcore_pingback_header' );

/**
 * Returns a placeholder image.
 */
function lbcore_get_placeholder_img(string $size = 'large', string $ratio = 'square'): array
{
	$w = ( $size == 'small' ) ? '225' : '900';
	$h = $w;

	if ( $ratio != 'square' )
	{
		$h = ( $size == 'small' ) ? '120' : '480';
	}

	$img = array(
		'url' => 'https://via.placeholder.com/' . $w . 'x' . $h,
		'alt-text' => 'Descriptive alt text that describes the image.'
	);

	return $img;
}

function lbcore_get_placeholder_content(string $contentType = 'textLockup')
{
	$placeholder = array(
		'textLockup' => array(
			[
				'subheading' => 'Website Services',
				'heading' => 'If You Can Dream It, We Can Build It',
				'copy' => 'Let us help you inform, engage and convert your audience with innovative, effective solutions. We put the value of our experience — best-in-class design, development, and integrations — to work for you.'
			],
			[
				'subheading' => 'About Us',
				'heading' => 'Exceptional Results, Driven by Purpose',
				'copy' => 'We\'re a full-service digital agency with end-to-end capabilities and a commitment to exceeding expectations. As a Certified B Corporation, we\'re also proud to be part of a global movement of companies that believe business should be a force for good.'
			],
			[
				'subheading' => 'Marketing Services',
				'heading' => 'Faster Connections, Better Conversions',
				'copy' => 'Let us help you grow your audience — and get them to convert — with innovative campaigns and creative, data-driven strategies. We put the value of our experience to work for you to deliver results that exceed your expectations.'
			],
			[
				'subheading' => 'About Us',
				'heading' => 'Inspired by Our Partners, Guided by Our Purpose',
				'copy' => 'With creativity and collaboration at our core, we get to the heart of your business and deliver results to move you forward faster.'
			],
			[
				'subheading' => 'Our Approach',
				'heading' => 'Relationships That Drive Results',
				'copy' => 'We know that great solutions don’t happen by accident. We\'re intentional about understanding your strengths, values and goals so we can deliver meaningful results.'
			]
		),
		'sellingPoints' => array(
			[
				'Accessibility' => 'Inclusive sites that surpass best practices to reach the widest audience possible without sacrificing exceptional design.',
				'E-Commerce' => 'Digital shopping experiences strategically developed to unlock revenue potential by engaging users and driving sales.',
				'Responsive Design' => 'Dynamic page development that goes beyond mobile design to respond to user environments and increase conversions.',
				'Integrations' => 'Complex software seamlessly integrated to support brand goals while providing users with an unparalleled experience.'
			],
			[
				'Web Design' => 'Best-in-class design, development and integrations for your brand’s piece of the internet.',
				'Marketing' => 'Creative solutions and data-driven strategies flawlessly executed to deliver the best results.',
				'TurnStyle®' => 'Our custom SaaS solution for selling tickets, managing memberships and growing subscriptions.',
				'Content Services' => 'Engaging content for websites, email and ongoing marketing campaigns, built on solid, innovative strategy.'
			],
			[
				'Digital Marketing' => 'Strategic marketing plans that anticipate industry trends to drive sales with quality conversions.',
				'Social Media' => 'Dynamic content calendar creation and implementation to boost engagement and build brand awareness.',
				'Marketing Automation' => 'Effortless management of multi-channel campaigns that increase efficiencies and drive exceptional results.',
				'Brand Development' => 'Immersive exercises and brand guides to competitively position an organization to grow.'
			]
		),
		'cta' => array('Learn More', 'Read More', 'Find Out How', 'Let\'s Get Started')
	);

	if (array_key_exists($contentType, $placeholder)) {
		$rand = array_rand($placeholder[$contentType]);
		return $placeholder[$contentType][$rand];
	}

	$rand = array_rand($placeholder['textLockup']);
	return $placeholder['textLockup'][$rand];
}

function lbcore_get_random_icon()
{
    $icons = glob(get_template_directory() . '/resources/icons/' . '*.*');
    $icon = array_rand($icons);

	return basename($icons[$icon], '.svg');
}

function lbcore_get_navigation(&$menuItems) {
	$navigation = [];
	foreach ($menuItems as $item) {
        if (!$item->menu_item_parent) {
            # root nav item
            $postRaw = get_post($item->object_id);
            $navigation[$item->ID] = [
                'item' => $item,
                'post' => $postRaw,
                'simple' => [
                    'name' => $item->title,
                    'slug' => $postRaw->post_name,
                    'url' => $item->url,
                ],
                'children' => []
            ];
        } else {
            # child or leaf nav item
            lbcore_add_nav_child($navigation, $item);
        }
    }
	return $navigation;
}

function lbcore_add_nav_child(&$nav, $item) {
    foreach ($nav as $id => &$itemData) {
        # I am the parent
        if ($id == $item->menu_item_parent) {
            $post = get_post($item->object_id);
            $itemData['children'][$item->ID] = [
                'item' => $item,
                'post' => $post,
                'simple' => [
                    'name' => $item->title,
                    'slug' => $post->post_name,
                    'url' => $item->url,
                ],
                'children' => []
            ];
        } else {
            # I am not the parent keep looking
            lbcore_add_nav_child($itemData['children'], $item);
        }
    }
}

function lbcore_get_request_string($key, $default = null)
{
    if (isset($_GET[$key])) {
        return (string)filter_var($_GET[$key], FILTER_SANITIZE_STRING);
    }
    if (isset($_POST[$key])) {
        return (string)filter_var($_POST[$key], FILTER_SANITIZE_STRING);
    }
    return $default;
}

function prep_permalink_data($locationId = null, $prevId = null, $nextId = null)
{
	$ret = array(
        'post_id' => $locationId,
        'name' => get_the_title($locationId),
        'acf' => get_fields($locationId)
    );

	if ($prevId) {
		$ret['prev'] = $prevId;
	}

	if ($nextId) {
		$ret['next'] = $nextId;
	}

	return $ret;
}

function get_permalink_blocks($acf)
{
	$blocks = [];

    foreach($acf['flexible_content'] as $block) {
        $blocks[] = $block['acf_fc_layout'];
    }

    if (!empty($acf['permaHero'])) {
        $blocks[] = 'permaHero';
    }

	// globals
    $blocks[] = 'nextPrev';
    $blocks[] = 'jumplinks';
    $blocks[] = 'comparison';
    $blocks[] = 'movingStory';

	return $blocks;
}


/* Returns theme color based on building type from Global Options page */
function get_building_theme_color($buildingType) {

	$themes = get_field('building_theme', 'option');
	
	foreach($themes as $theme) {
		if ($theme['building_type']['value'] == $buildingType) {
			return $theme['color_theme'];
		}
	}

	return 'default';
}