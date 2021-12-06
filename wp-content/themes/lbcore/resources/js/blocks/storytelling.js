export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="storytelling"]'), elem => {
        return new Storytelling(elem, {})
    })
}

export class Storytelling {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init() {

    }
}