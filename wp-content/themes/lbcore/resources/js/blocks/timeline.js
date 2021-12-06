export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="timeline"]'), elem => {
        return new Timeline(elem, {})
    })
}

export class Timeline {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init () {
        //console.log(this.element)
    }
}