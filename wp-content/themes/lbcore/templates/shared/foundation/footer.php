<?php
    $acknowledgements = !empty(get_field('footer_acknowledgements')) ? get_field('footer_acknowledgements') : '';
    $socialLinks = array(
        [
            'icon' => '#facebook',
            'url' => 'https://www.facebook.com/countrymusichof'
        ],
        [
            'icon' => '#twitter',
            'url' => 'https://twitter.com/countrymusichof'
        ],
        [
            'icon' => '#instagram',
            'url' => 'http://instagram.com/officialcmhof'
        ],
        [
            'icon' => '#youtube',
            'url' => 'http://www.youtube.com/user/countrymusichof'
        ]
    );
?>

<footer class="presentational__relative-container spacing__pvx spacing__phx">
    <div class="wrappers__wrapper">
        <?php if(!empty($acknowledgements)): ?>
            <div class="footer__acknowledgements">
                <div class="wysiwyg">
                    <?= $acknowledgements; ?>
                </div>
            </div>
        <?php endif; ?>
        <nav class="footer__nav" aria-label='Footer navigation.'>
            <div class="footer__cta-newsletter">
                <a class="footer__newsletter-link" href="https://cmhof.typeform.com/to/Dks7Gu" target="_blank">
                    Sign up for our newsletter
                    <svg class="icons__icon">
                        <use xlink:href="#arrow-right"></use>
                    </svg>
                </a>
            </div>
            <div class="spacing__mtl">
                <label class="footer__social-label color--action-gold styles__sequel" for="social-links" class="color--action-gold">
                    Follow Us
                </label>
                <ul class="footer__social-list lists__unstyled flex spacing__mts" id="social-links">
                    <?php foreach($socialLinks as $link): ?>
                        <li class="footer__social-list-item spacing__mrm">
                            <a href="<?=$link['url']?>" target="_blank" 
                               class="footer__social--link links__link links__link--dark spacing__man spacing__pas">
                                <svg class="icons__icon color--slate">
                                    <use xlink:href="<?=$link['icon']?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer__cta-small hierarchy__minion-text spacing__mtx">
                COUNTRY MUSIC HALL OF FAME AND MUSEUM • 222 FIFTH AVENUE SOUTH NASHVILLE, TN 37203 • PHONE: <a href="tel:615-416-2001">615.416.2001</a>
                &copy;2021 COUNTRY MUSIC HALL OF FAME&reg; AND MUSEUM ALL RIGHTS RESERVED
                | <a href="https://countrymusichalloffame.org/privacy-policy/" target="_blank">PRIVACY POLICY</a> | <a href="https://countrymusichalloffame.org/terms-of-use/" target="_blank">TERMS OF USE</a>
            </div>
            <div class="footer__logo spacing__mtl">
                <a href="https://www.aam-us.org/" target="_blank">
                    <img class="spacing__mtl" src="https://cmhof-musicrow.imgix.net/wp-content/uploads/2021/09/13180006/American-Alliance-of-Museums-Footer-1.png?w=55&h=55" alt="American Alliance of Museums Logo" width="55" height="55">
                </a>
            </div>
        </nav>
    </div>
    <div class="footer__background"></div>
</footer>