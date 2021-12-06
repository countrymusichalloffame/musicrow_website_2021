export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('[data-block="permaHero"]'), elem => {
        return new PermaHero(elem, {})
    })
}

export class PermaHero {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}

        this.hero = this.element.querySelector('.js-perma-hero')

        this.init()
    }

    init () {
        if (this.hero.dataset.type == 'comparison') {
            import('./comparison')
                .then(module => {
                    module.default()
                })
        }
    }
}