const DEFAULTS = {
    'focalPointInputSelector': '.js-acf-adv-img-fp-input',
    'focalPointMarkerSelector': '.js-acf-adv-img-marker',
    'imageSelector': '.js-acf-adv-img',
    'clearFocalPointButtonSelector': '.js-acf-adv-img-clear-marker'
}

export default class MediaPickerFocalPoint {
    constructor(elem, options) {
        this.elem = elem
        this.options = { ...DEFAULTS, ...options }
        this.image = this.elem.querySelector(this.options.imageSelector)
        this.focalPointInput = this.elem.querySelector(this.options.focalPointInputSelector)
        this.focalPointMarker = this.elem.querySelector(this.options.focalPointMarkerSelector)
        this.clearFocalPointButton = this.elem.querySelector(this.options.clearFocalPointButtonSelector)
        // this.bindEvents()
        this.init()
    }

    init() {
        console.log(this.elem)
        console.log(this.image)
        console.log(this.focalPointInput)
        console.log(this.focalPointMarker)
        console.log(this.clearFocalPointButton)
        // if (this.focalPointInput.value !== '') {
        //     this.renderFocalPoint(this.getFocalPointValuesFromString())
        // } else {
        //     this.disableClearFocalPointButton()
        // }

        // ;[].forEach.call(this.elem.querySelectorAll('input, textarea, select'), field => {
        //     if (field.name.indexOf('[x]') > -1) {
        //         field.name = field.name.replace('[x]', `[${this.options.id}]`)
        //     }
        // })
    }

    disableClearFocalPointButton() {
        this.clearFocalPointButton.disabled = true
        this.clearFocalPointButton.style.display = 'none'
    }

    enableClearFocalPointButton() {
        this.clearFocalPointButton.disabled = false
        this.clearFocalPointButton.removeAttribute('style')
    }

    bindEvents() {
        if (!this.image) {
            return
        }
        this.image.addEventListener('click', ev => {
            this.updateFocalPoint(ev)
        })
        this.clearFocalPointButton.addEventListener('click', ev => {
            this.clearFocalPoint(ev)
        })
    }

    getFocalPointValues(ev) {
        const offset = this.getOffset(this.image)
        const focalPoint = {
            x: ((ev.pageX - offset.left) / this.image.width).toFixed(2),
            y: ((ev.pageY - offset.top) / this.image.height).toFixed(2)
        }

        const percentage = {
            x: `${focalPoint.x * 100}%`,
            y: `${focalPoint.y * 100}%`
        }

        return {
            focalPoint,
            percentage
        }
    }

    getFocalPointValuesFromString(string = this.focalPointInput.value) {
        const urlParams = new window.URLSearchParams(string)
        return {
            x: `${parseFloat(urlParams.get('fp-x')) * 100}%`,
            y: `${parseFloat(urlParams.get('fp-y')) * 100}%`
        }
    }

    renderFocalPoint(coords) {
        if (!coords) {
            this.focalPointMarker.style.display = 'none'
        } else {
            const { x, y } = coords
            this.focalPointMarker.style.display = 'block'
            this.focalPointMarker.style.left = x
            this.focalPointMarker.style.top = y
        }
    }

    saveFocalPoint(coords) {
        if (!coords) {
            this.focalPointInput.value = ''
        } else {
            const { x, y } = coords
            this.focalPointInput.value = `fit=crop&crop=focalpoint&fp-x=${x}&fp-y=${y}`
        }
    }

    updateFocalPoint(ev) {
        const values = this.getFocalPointValues(ev)

        this.renderFocalPoint(values.percentage)
        this.saveFocalPoint(values.focalPoint)
        this.enableClearFocalPointButton()
    }

    clearFocalPoint() {
        this.renderFocalPoint()
        this.saveFocalPoint()
        this.disableClearFocalPointButton()
    }

    getOffset(elem) {
        return getCoords(elem)
    }

    getCoords(el) {
        let box = el.getBoundingClientRect()

        let body = document.body
        let docEl = document.documentElement

        let scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop
        let scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft

        let clientTop = docEl.clientTop || body.clientTop || 0
        let clientLeft = docEl.clientLeft || body.clientLeft || 0

        let top = box.top + scrollTop - clientTop
        let left = box.left + scrollLeft - clientLeft
        let bottom = box.bottom + scrollTop - clientTop

        return { top: Math.round(top), left: Math.round(left), bottom: Math.round(bottom) }
    }
}
