import Carousel from '../core/ui/motif.carousel';

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="profileCarousel"]'), elem => {
        return new ProfileCarousel(elem, {})
    })
}

export class ProfileCarousel {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init () {
        return new Carousel(this.element.querySelector('.js-profile-carousel'), {
            visibleSlides: 1,
            isDraggable: true,
            playPauseButton: false,
            loop: false,
            progressIndicators: false,
        })
    }
}

/*
export default function () { 
    ;[].forEach.call(document.querySelectorAll('.js-profile-carousel'), el => {
        return new Carousel(el, {
            visibleSlides: 3,
            isDraggable: true,
            playPauseButton: false,
            loop: false,
            progressIndicators: false,
        })
    })
}
*/