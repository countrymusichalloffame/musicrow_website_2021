import Carousel from '../core/ui/motif.carousel';

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="audioGallery"]'), elem => {
        return new AudioGallery(elem, {})
    })
}

export class AudioGallery {
    constructor(elem, options) {
        this.element = elem;
        this.defaults = {};
        this.settings = {...this.defaults, ...options};

        this.init();
    }

    init() {
        const audioCarousel = this.element.querySelector('.js-audio-gallery');
        const goToSlide = this.element.querySelectorAll('.js-audio-gallery-go-to')

        if (audioCarousel) {
            const carousel = new Carousel( audioCarousel, {
                visibleSlides: 1,
                isDraggable: true,
                playPauseButton: false,
                loop: false,
                progressIndicators: false,
                responsiveHeight: true
            });

            ;[].forEach.call(goToSlide, (btn, i) => {
                btn.addEventListener('click', ev => {
                    carousel.setStateByIndex(i)
                })
            })
        }
    }
}