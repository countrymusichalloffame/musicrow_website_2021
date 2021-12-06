<?php
add_filter('allowed_block_types', 'lbcore_allowed_block_types', 10, 2);

function lbcore_allowed_block_types($allowed_blocks, $post)
{
    /**
     * All core blocks and custom blocks.
     *
     * To remove a block from the editor, comment it out here to
     * remove it from the whitelist.
     *
     * To see list of all registered blocks:
     *   1. Load up editor page in CMS
     *   2. Paste this in console:
     *         ;[].forEach.call(wp.blocks.getBlockTypes(), b => { console.log(b.name)})
     *
     */
    $allowed_blocks = array(

        // * ACF Blocks
        //"acf/example",
        "acf/patterns",

        // * Common Blocks *
        "core/paragraph",
        "core/image",
        "core/heading",
        "core/gallery",
        "core/list",
        "core/quote",
        //"core/audio",
        "core/file",
        "core/video",
        //"core/cover",
        //"core/subhead",

        // * Formatting *
        "core/code",
        "core/freeform",
        "core/html",
        //"core/preformatted",
        //"core/pullquote",
        //"core/verse",
        "core/table",

        // * Layout *
        "core/columns",
        "core/column",
        //"core/more",
        //"core/nextpage",
        "core/separator",
        // "core/button",
        "core/spacer",
        "core/text-columns",

        // * Widgets *
        //"core/categories",
        //"core/latest-comments",
        //"core/latest-posts",
        //"core/archives",
        "core/shortcode",

        // * Embeds *
        //"core/embed",
        "core-embed/twitter",
        //"core-embed/youtube",
        "core-embed/facebook",
        "core-embed/instagram",
        //"core-embed/wordpress",
        //"core-embed/soundcloud",
        //"core-embed/spotify",
        "core-embed/flickr",
        //"core-embed/vimeo",
        //"core-embed/animoto",
        //"core-embed/cloudup",
        //"core-embed/collegehumor",
        //"core-embed/dailymotion",
        //"core-embed/funnyordie",
        //"core-embed/hulu",
        //"core-embed/imgur",
        //"core-embed/issuu",
        //"core-embed/kickstarter",
        //"core-embed/meetup-com",
        //"core-embed/mixcloud",
        //"core-embed/photobucket",
        //"core-embed/polldaddy",
        //"core-embed/reddit",
        //"core-embed/reverbnation",
        //"core-embed/screencast",
        //"core-embed/scribd",
        //"core-embed/slideshare",
        //"core-embed/smugmug",
        //"core-embed/speaker",
        //"core-embed/speaker-deck",
        //"core-embed/ted",
        //"core-embed/tumblr",
        //"core-embed/videopress",
        //"core-embed/wordpress-tv",
        //"core/media-text",

        // * Misc *
        "core/missing",
        "core/template",
        "core/block",
    );

    return $allowed_blocks;
}