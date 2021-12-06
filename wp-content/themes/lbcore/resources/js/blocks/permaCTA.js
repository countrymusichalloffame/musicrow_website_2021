export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="permaCTA"]'), elem => {
        return new PermaCTA(elem, {})
    })
}

export class PermaCTA {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init () {}
}