import { uAddClass, uRemoveClass } from "../core/utils/motif.utilities";

const audio = document.querySelector('.js-audio');
var playPromise = undefined;

var allPlayBtns = [];
var allPauseBtns = [];
var allProgressBars = [];

export default function () {
    let playBtns = document.querySelectorAll('.js-audio-play');
    let pauseBtns = document.querySelectorAll('.js-audio-pause');
    let playerWrappers = document.querySelectorAll('.js-audio-player-wrapper');
    let progressWrappers = document.querySelectorAll('.js-audio-progress');

    setAudioControlListeners(playBtns, pauseBtns, playerWrappers, progressWrappers); 

}

function setAudioControlListeners(playBtns, pauseBtns, playerWrappers, progressWrappers) {

    /* play button listener*/
    Array.from(playBtns).forEach(play => {
        
        /* only add click listeners for buttons not already initialized */
        if (allPlayBtns.includes(play)) {
            return;
        } 
        allPlayBtns.push(play);

        play.addEventListener('click', ev => {
            
            /* add 'is-active' class to clicked button and parent wrapper */
            updateBtns(play, playBtns, pauseBtns);
            updateWrapper(play.parentNode, playerWrappers);

            /* prep and play audio */
            prepAudio(play.dataset.track)
                .then(state => {
                    // state == 'play' ? playPromise = audio.play() : pauseAudio()
                    if ( state == 'play') {
                        playPromise = audio.play();
                    } else {
                        playPromise = pauseAudio();
                    }
                })

                /* initialize corresponding progressbar for this specific play button / track. */
                .then( () => {
                    let id = play.dataset.audioId;
                    let progressWrapperSelector = '.js-audio-progress[data-audio-id="' + id + '"]';
                    let currProgress = document.querySelector(progressWrapperSelector);

                    //uAddClass(audioProgress.element, 'is-active'); 
                    updateProgress(currProgress, progressWrappers);
                    let audioProgress = new AudioProgress(currProgress, audio); 
                })
        })
    })
    /* pausse button listeners */
    Array.from(pauseBtns).forEach(pause => {

        if (allPauseBtns.includes(pause)) {
            return;
        } 
        allPauseBtns.push(pause);

        pause.addEventListener('click', ev => {

            /* add 'is-active' class to clicked button */
            updateBtns(pause, playBtns, pauseBtns);
            
            /* pause audio or return if already paused */
            if(audio.paused) {
                return;
            }
            pauseAudio();
        })
    })
}


function prepAudio(track) {

    return new Promise(resolve => {

        if (audio.src == track && !audio.paused) {
            resolve('pause');
        } else if (audio.src == track) {
            resolve('play');
        } else {
            // pauseAudio()
            audio.src = track;
            audio.load();
            resolve('play');
        }
    })
}

/** pauseAudio()
 * 
 * src: https://developers.google.com/web/updates/2017/06/play-request-was-interrupted
 * 
 * TL:DR 
 * we need to have error checking instead of just calling audio.pause
 */
function pauseAudio() {
    if (playPromise !== undefined) {
        playPromise.then( () => {
            // We can now safely pause video...
            audio.pause();
        })
        .catch(error => {
            // Auto-play was prevented
            // Show paused UI.
            // console.log(error)
        });
    }
}

/**
 * updateBtns()
 * 
 * updates the clicked button to 'is-active', 
 * and removes 'is-active' from all other play/pause buttons.
 */
function updateBtns(currentBtn, playBtns, pauseBtns) {

    playBtns.forEach(btn => {
        if (currentBtn == btn) {
            uAddClass(btn, 'is-active');
        } else {
            uRemoveClass(btn, 'is-active');
        }
    })

    pauseBtns.forEach(btn => {
        if (currentBtn == btn) {
            uAddClass(btn, 'is-active');
        } else {
            uRemoveClass(btn, 'is-active');
        }
    })
}

/**
 * updateProgress()
 * 
 * updates the current progress wrapper  to 'is-active', 
 * and removes 'is-active' from all the rest.
 */
function updateProgress(currentProgress, progressWrappers) {
    progressWrappers.forEach(btn => {
        if (currentProgress == btn) {
            uAddClass(btn, 'is-active');
        } else {
            uRemoveClass(btn, 'is-active');
        }
    })
}


/**
 * updateWraper()
 * 
 * adds 'is-active' class to the audio player wrapper currently playing, 
 * removes 'is-active' from the rest.
 */
 function updateWrapper(current, wrappers) {

    wrappers.forEach(wrapper => {
        if (current == wrapper) {
            uAddClass(wrapper, 'is-active');
        } else {
            uRemoveClass(wrapper, 'is-active');
        }
    })
}



/********************************
 * 
 * Audio Progress Slider Class
 *  
 ********************************/
class AudioProgress {
    
    constructor(elem, audio) {

        this.element = elem;
        this.audio = audio;

        if (!this.element) {
            return;
        }

        this.progressInput = this.element.querySelector('.js-audio-progress-input');
        this.currentTimeText = this.element.querySelector('.js-audio-current');
        this.totalTimeText = this.element.querySelector('.js-audio-total');

        this.init();
        this.setProgressListeners();
    }

    init() {
        this.audio.onloadedmetadata = () => {
            this.setDurationText();
        }
    }

    setProgressListeners() {

        if (allProgressBars.includes(this.element)) {
            return;
        } 
        allProgressBars.push(this.element);

        this.audio.addEventListener('timeupdate', ev => {
            if (document.activeElement != this.progressInput && this.audio.duration > 0 && this.progressIsActive()) {;
                this.updateValue();
                this.updateCurrentTimeText();   
            }
        })

        if (this.progressInput) {
            this.progressInput.addEventListener('change', ev => {
                if (this.progressIsActive()) {
                    this.audio.currentTime = this.calculateTimestamp(ev.target.value);
                    this.updateCurrentTimeText();      
                }          
            })
        }

    }

    progressIsActive() {
        return this.element.classList.contains('is-active');
    }

    updateValue() {
        this.progressInput.value = this.calculateProgress();
    }

    calculateProgress() {
        let num = ( this.audio.currentTime / this.audio.duration ) * 100;
        let round = Number(num.toFixed(3));
        return round;
    }

    calculateTimestamp(value) {
        let num = ( this.audio.duration / 100 ) * value;
        let round = Number(num.toFixed(3));
        return round;
    }

    setDurationText() {
        if (this.totalTimeText) {
            this.totalTimeText.innerHTML =  this.formatTime(this.audio.duration);
        }
    }
  
    updateCurrentTimeText() {
        if (this.currentTimeText) {
            this.currentTimeText.innerHTML =  this.formatTime(this.audio.currentTime);
        }
    }

    formatTime(time) {
        let minutes = Math.floor(time / 60);
        let seconds = Math.round(time - minutes * 60);
        
        seconds = (seconds >= 10) ? seconds : "0" + seconds;
        let timestamp =  String(minutes + ":" + seconds);
        
        return timestamp;
    }
}