import Reveal from "../../core/ui/motif.reveal.es6"
import { a11yToggleAria } from '../../core/utils/motif.a11y'

export default function () {
    ;[].forEach.call(document.querySelectorAll('.js-accordion'), el => {
        new Accordion(el, {})
    })
}

export class Accordion {
  constructor(element, options) {
    this.element = element
    this.defaults = {}
    this.settings = { ...this.defaults, ...options }

    this.init()
  }

  init() {
    let self = this

    new Reveal(".js-accordion-reveal", {
        type: "default",
        activeClass: "is-expanded",
        visitedClass: "was-expanded",
        onHide: function() {
          self.togglePanel(this.elem, this.reference.targets[0])
        },
        onShow: function () {
          self.togglePanel(this.elem, this.reference.targets[0])
        }
      })
  }

  togglePanel(trigger, target) {
    if (target.hasAttribute('hidden')) {
      a11yToggleAria(trigger)
      target.removeAttribute('hidden')
    } else {
      a11yToggleAria(trigger)
      target.setAttribute('hidden', '')
    }
  }
}
