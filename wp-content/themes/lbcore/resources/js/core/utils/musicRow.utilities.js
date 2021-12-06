export const AJAX_GET_LOCATION = '/wp-admin/admin-ajax.php?action=ajax_get_location'

export function uImportBlocks(parent = document, blocks = []) {
    return new Promise((resolve, reject) => {
        if (!window.MusicRow.whitelistBlocks) {
            /**
             * Our build process graphs all files that are
             * imported using static analysis before making them 
             * available to use via import(), which means we can't
             * import via computed expressions.
             * 
             * The solution is a module "whitelist" that our build
             * process can read, but does not actually run. Any
             * new blocks must be added below:
             */
            if (false) {
                import('../../blocks/permaHero')
                import('../../blocks/featuredMedia')
                import('../../blocks/profileCarousel')
                import('../../blocks/timeline')
                import('../../blocks/audioGallery')
                import('../../blocks/nextPrev')
                import('../../blocks/mediaGallery')
                import('../../blocks/quote')
                import('../../blocks/storytelling')
                import('../../blocks/permaCTA')
                import('../../blocks/jumplinks')
                import('../../blocks/fullWidthImage')
                import('../../blocks/comparison')
                import('../../blocks/movingStory')
            }
    
            window.MusicRow.whitelistBlocks = true
        }
    
        if (!blocks) {
            reject('no blocks')
        } else {
            blocks.forEach(block => {
                import(`../../blocks/${block}`)
                    .then(module => {
                        /**
                         * Runs the default export for the module
                         * and passes in the block's outer <section> elem.
                         */
                        module.default(parent)
                    })
            })
            resolve(true)
        }
    })
}