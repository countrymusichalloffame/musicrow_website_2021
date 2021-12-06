import { uScrollToElem, uCreateCustomEvent } from "../core/utils/motif.utilities"

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-moving-story'), elem => {
        return new NextPrev(elem, {
            location: (parent.dataset && parent.dataset.location) ? parent.dataset.location : null
        })
    })
}

export class NextPrev {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {
            location: null
        }
        this.settings = {...this.defaults, ...options}

        this.storyBtns = this.element.querySelectorAll('.js-story-btn')
        
        this.setEventListeners()
    }

    setEventListeners() {
        if (this.storyBtns) {
            ;[].forEach.call(this.storyBtns, button => {
                button.addEventListener('click', ev => {  
                    window.dispatchEvent(uCreateCustomEvent(
                        'map/story/activate', 
                        {
                            person: button.dataset.person,
                            location: this.settings.location
                        }
                    ))
                })
              })
        }
    }
}
