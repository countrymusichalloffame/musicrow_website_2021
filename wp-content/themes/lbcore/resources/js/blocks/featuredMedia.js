export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="featuredMedia"]'), elem => {
        return new FeaturedMedia(elem, {})
    })
}

export class FeaturedMedia {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.init()
    }

    init () {
        //console.log('featured media: ', this.element)
    }
}