import Carousel from '../core/ui/motif.carousel';

/**
 * Media Gallery blocks exist both globally and on perma pages,
 *   so the initialization is not looking for a [data-block=""] attribte like our other js block files.
 */
export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-media-gallery'), elem => {
        const goToSlide = elem.querySelectorAll('.js-media-gallery-go-to')

        const carousel = new Carousel(elem, {
            visibleSlides: 1,
            isDraggable: false,
            playPauseButton: false,
            loop: false,
            progressIndicators: false,
        })

        ;[].forEach.call(goToSlide, (btn, i) => {
            btn.addEventListener('click', ev => {
                carousel.setStateByIndex(i)
            })
        })
    })
}