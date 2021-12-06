import "lazysizes";
import getAnimationFrame from '../utils/motif.animationFrame.es6.js';
import { uDebounce } from '../utils/motif.utilities'

const animationFrame = getAnimationFrame();
const bpMed = window.matchMedia('(min-width: 767px)')

export default function (IMAGE_CLASS = 'js-dynamic-image', multiple = 1) {
  bindImages(IMAGE_CLASS, multiple);
}

function bindImages (IMAGE_CLASS, multiple) {
  document.addEventListener('lazybeforesizes', function (ev) {
    if (ev.defaultPrevented || !ev.target.classList.contains(IMAGE_CLASS)) {
      return;
    }

    const element = ev.target;
    const url = `${element.getAttribute('data-original')}&w=${element.parentElement.offsetWidth * multiple}&h=${element.parentElement.offsetHeight * multiple}`
    let mobile = null
    
    if (element.hasAttribute('data-mobile')) {
      mobile = `${element.getAttribute('data-mobile')}&w=${element.parentElement.offsetWidth * multiple}&h=${element.parentElement.offsetHeight * multiple}`

      !bpMed.matches ? loadMobile() : loadDefault()

      window.addEventListener('resize', uDebounce( e => handleResize(), 500 ))
    } else {
      loadDefault() 
    }

    function loadDefault () {
      animationFrame(() => {
        element.setAttribute('data-fp', 'default')
        element.setAttribute('data-src', url);
        element.setAttribute('src', url);
      });
    }
    
    function loadMobile () {
      animationFrame(() => {
        element.setAttribute('data-fp', 'mobile')
        element.setAttribute('data-src', mobile);
        element.setAttribute('src', mobile);
      });
    }
    
    function handleResize() {
      if (bpMed.matches) {
        if (element.getAttribute('data-fp') == 'default') {
          return
        } else {
          loadDefault()
        }
      } else {
        if (element.getAttribute('data-fp') == 'mobile') {
          return
        } else {
          loadMobile()
        }
      }
    }
  });

}