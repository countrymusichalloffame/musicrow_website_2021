<div class="artistPopup" data-artist="<?= $args['name']; ?>">
    <div class="artistPopup__heading">
        <span class="artistPopup__name styles__h5 color--white">
            <?= $args['name']; ?>
        </span>
        <button class="artistPopup__cta styles__h5" type="button" data-mb-id="<?= $args['location_id']; ?>">
            <?= get_the_title($args['location_id']); ?>
            <svg class="icons__icon spacing__mtn"><use xlink:href="#play"></use></svg>
        </button>

        <button type="button" class="artistPopup__close button__close button__close--small">
            <svg class="icons__icon">
                <use xlink:href="#close"></use>
            </svg>
            <span class="presentational__is-hidden">Close Popup.</span>
        </button>
    </div>
    <?php if (isset($args['acf']) && !empty($args['acf'])): ?>
        <div class="artistPopup__content"
            data-media-type=<?=!empty($args['acf']['media_type']) ? $args['acf']['media_type'] : 'null'?>
            data-has-footer=<?=$args['pager']['total'] > 1 ? 'true' : 'false'?>
        >
            <?php get_template_part('templates/shared/artistPopup/artistPopupContent', null, $args['acf']); ?>
        </div>
    <?php endif; ?>
    <?php if ($args['pager']['total'] > 1): ?>
        <div class="artistPopup__footer">
            <button type="button" class="artistPopup__nav button__no-button styles__h6"
                data-mb-id="<?= $args['prev'] ?? "null"; ?>"
                data-dir="left"
            > 
                <svg class="icons__icon"><use xlink:href="#play"></use></svg>
                <span>Prev</span>
            </button>
            <span class="artistPopup__pager styles__h6">
                Part <?= $args['pager']['count'] . ' of ' . $args['pager']['total']; ?>
            </span>
            <button type="button" class="artistPopup__nav button__no-button styles__h6"
                data-mb-id="<?= $args['next'] ?? "null"; ?>"
                data-dir="right"
            > 
                <span>Next</span>
                <svg class="icons__icon"><use xlink:href="#play"></use></svg>
            </button>
        </div>
    <?php endif; ?>
</div>