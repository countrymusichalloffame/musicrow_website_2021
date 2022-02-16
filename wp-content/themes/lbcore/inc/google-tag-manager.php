<?php

function lbcore_google_tag_manager_top() {
    $gtm_tag = "
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WTZF9NV'');</script>
<!-- End Google Tag Manager -->
    ";

    echo $gtm_tag;
}
add_action('wp_head', "lbcore_google_tag_manager_top", 1, 0);

function lbcore_google_tag_manager_body_start() {
    $gtm_tag = '
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WTZF9NV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    ';

    echo $gtm_tag;
}
add_action('wp_body_open', 'lbcore_google_tag_manager_body_start');

?>