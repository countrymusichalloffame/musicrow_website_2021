export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="quote"]'), elem => {
        return new Quote(elem, {})
    })
}

export class Quote {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init() {

    }
}