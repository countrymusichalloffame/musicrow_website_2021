import { uAddClass, uRemoveClass } from "../core/utils/motif.utilities";

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-video'), elem => {
        return new Video(elem, {});
    })
}

var allVideos = [];


export class Video {
    constructor(elem, options) {
		this.element = elem;
		this.defaults = {};
		this.settings = {...this.defaults, ...options};

        this.video = this.element.querySelector('.js-video-element');	
		this.init();
	}
	
	init() {
        if (!this.video) {
            return;
        }
		this.setVideoListeners();
	}

    setVideoListeners() {
        if (allVideos.includes(this.element)) {
            return;
        } 

        this.element.addEventListener('click', ev => {
            this.toggleVideo();
        })

        this.video.addEventListener('play',  ev => { 
            // for keyboard users
            if (!this.element.classList.contains('is-active')) {
                this.toggleVideo();
            }
        });

        allVideos.push(this.element);
    }

    /* toggles 'is-active' class and video controls */
    toggleVideo() {
        return new Promise( resolve => {
            
            if (this.element.classList.contains('is-active')) {
                uRemoveClass(this.element, 'is-active');
                this.video.pause();
                this.video.controls = false;
            } else {
                uAddClass(this.element, 'is-active');
                this.video.controls = true;
                this.video.play();
            }
            
            resolve(true);
        })
    }
}