
export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-comparison'), elem => {
        return new Comparison(elem, {})
    })
}

export class Comparison {
	constructor(elem, options) {
		this.element = elem
		this.defaults = {}
		this.settings = {...this.defaults, ...options}

		this.comparisonInput = this.element.querySelector('.js-comparison-slider');
	
		this.init()
	}
	
	init() {
		if (!this.comparisonInput) {
			return
		}
		this.setEventListeners()
	}

	setEventListeners() {
		var foreground = this.element.querySelector('.comparison__foreground-img');
		var button = this.element.querySelector('.comparison__slider-button');

		this.comparisonInput.addEventListener('input', (e) =>{
			const sliderPos = e.target.value;

			// Update the width of the foreground image
			foreground.style.width = `${sliderPos}%`;

			// Update the position of the slider button
			button.style.left = `${sliderPos}%`;
		})
	}
}

