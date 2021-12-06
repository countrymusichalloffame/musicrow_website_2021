import Carousel from './motif.carousel';

export default function () { 
    ;[].forEach.call(document.querySelectorAll('.js-media-gallery'), el => {
        return new Carousel(el, {
            visibleSlides: 1,
            isDraggable: true,
            playPauseButton: false,
            loop: false,
            progressIndicators: true,
        })
    })

    //TEMPORARY for the patterns page - needs to load from musicrow.utilities
    ;[].forEach.call(document.querySelectorAll('.js-profile-carousel'), el => {
        return new Carousel(el, {
            visibleSlides: 3,
            isDraggable: true,
            playPauseButton: false,
            loop: true,
        })
    })
}
