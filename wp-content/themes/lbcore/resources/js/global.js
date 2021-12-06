// core
import reveal from "./core/ui/reveal";
import icons from "./core/ui/icons";
import dynamicImages from "./core/images/dynamicImages";
import orientation from './core/images/orientation';
import hoverIntent from './core/vendor/hoverIntent'
import forms from './core/forms/forms';
import carousels from './core/ui/carousels';
import sliders from './core/ui/sliders';
import gridlines from "./core/ui/motif.gridlines";
import accordion from './core/ui/accordion';
import hoverintent from './core/vendor/hoverIntent';

class App {
  constructor() {
    this.core();
    this.pages();
    this.shared();
    this.header();
  }

  core() {
    reveal();
    icons();
    dynamicImages();
    orientation();
    hoverIntent();
    forms();
    carousels();
    sliders();
    gridlines();
    accordion();
    hoverintent();
  }

  pages() {
    if(document.querySelector('.js-map')) {
      import('./pages/map')
        .then(map => {
          map.default();
        })
    }
  }

  shared() {
    if(document.querySelector('.js-audio')) {
      import('./shared/audio')
        .then(audio => {
          audio.default();
        })
    }

    if(document.querySelector('.js-modal')) {
      import('./shared/modal')
        .then(modal => {
          modal.default();
        })
    }

    if(document.querySelector('.js-video')) {
      import('./shared/video')
        .then(video => {
          video.default();
        })
    }
    

    /* initialize global media gallery */
    if(document.querySelector('.js-media-gallery')) {
      import('./blocks/mediaGallery')
        .then(mediaGallery => {
          mediaGallery.default();
        })
    }

    /* initialize global moving story */
    if(document.querySelector('.js-moving-story')) {
      import('./blocks/movingStory')
        .then(movingStory => {
          movingStory.default();
        })
    }
  }

  header() {
    const slideshow = document.getElementById('slideshow');
    const controls = document.querySelectorAll('.js-slideshow-control');
    const nav = document.getElementById('main-nav');

    document.body.style.setProperty('--nav-height', `${nav.offsetHeight}px`);

    window.addEventListener('resize', ev => {
      document.body.style.setProperty('--nav-height', `${nav.offsetHeight}px`);
    })

    ;[].forEach.call(controls, btn => {
      btn.addEventListener('click', ev => {
        if (btn.dataset.action == 'pause') {
          document.body.classList.add('is-paused');
        } else {
          document.body.classList.remove('is-paused');
        }
      })
    })


  }
}

const app = new App();

window.Motif = window.Motif || {};
window.Motif.app = app;

export default app;
