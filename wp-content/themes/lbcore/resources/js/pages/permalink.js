import { uImportBlocks } from '../core/utils/musicRow.utilities';
import { uGetCoords, uScrollToElem } from '../core/utils/motif.utilities';

import audio from '../shared/audio';
import Modal from '../shared/modal';
import Video from '../shared/video';

// import comparison from '../blocks/comparison';

export default class Permalink {
    constructor(elem, options) {
        this.panel = elem;
        this.defaults = {
            blocks: {},
            html: ''
        };
        this.config = {...this.defaults, ...options};
        
        this.inner = this.panel.querySelector('.js-permalink-inner');
        this.close = this.panel.querySelector('.js-permalink-close');

        this.close.addEventListener('click', this.reset.bind(this));

        if(!this.config.blocks || !this.config.html) {
            return;
        }
    }

    build() {
        this.prep(this.config.html)
            .then(uImportBlocks(this.panel, this.config.blocks)
                .then(() => {
                    /* initialize audio, modals, video */
                    audio();
                    Modal();
                    Video();

                    /* reveal panel */
                    this.panel.classList.add('is-active');

                    /* scroll to top of perma */
                    this.panel.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });

                    /* focus close btn */
                    if (this.close) {
                        this.close.focus();
                    }

                    /* scroll to map */
                    uScrollToElem(this.panel);
                })
            )
    }

    prep(html) {
        return new Promise(resolve => {
            this.inner.insertAdjacentHTML('beforeend', html)
            resolve(true)
        })
    }

    reset() {
        this.config.blocks = {}
        this.config.html = ''

        return new Promise(resolve => {
            if (this.panel.classList.contains('is-active')) {
                this.panel.classList.remove('is-active')
                this.panel.classList.remove('is-hidden')
    
                window.setTimeout(() => {
                    this.inner.innerHTML = ''
                    resolve(true)
                })
            } else {
                resolve(true)
            }
        })
    }
}