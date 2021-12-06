import { uScrollToElem, uCreateCustomEvent } from "../core/utils/motif.utilities"

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-nextPrev'), elem => {
        return new NextPrev(elem, {})
    })
}

export class NextPrev {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.nextPrevBtns = this.element.querySelectorAll('.js-nextPrev-btn')
        
        this.setEventListeners()
    }

    setEventListeners() {
        if (this.nextPrevBtns) {
            ;[].forEach.call(this.nextPrevBtns, btn => {
                btn.addEventListener('click', ev => {  
                    window.dispatchEvent(uCreateCustomEvent(
                        'map/nextPrev/activate', 
                        {
                            location: btn.dataset.cardLink
                        }
                    ))
                })
              })
        }
    }
}
