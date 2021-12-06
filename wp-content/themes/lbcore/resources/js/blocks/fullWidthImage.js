export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="fullWidthImage"]'), elem => {
        return new fullWidthImage(elem, {})
    })
}

export class fullWidthImage {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init() {

    }
}