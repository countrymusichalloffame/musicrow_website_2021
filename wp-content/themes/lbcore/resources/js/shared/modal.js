import {
    uAddClass,
    uRemoveClass,
    uHasClass,
    uStartPageScrollable,
    uStopPageScrollable,
    uCreateCustomEvent
  } from "../core/utils/motif.utilities";
  import { a11yChildTabindexToggle, a11yFocusElements } from "../core/utils/motif.a11y";

  export default function() {
    [].forEach.call(document.querySelectorAll(".js-modal"), modal => {
      return new Modal(modal);
    });
  }
  
  export class Modal {
    constructor(element, options = {}) {
      this.element = element;
      const defaults = {
        openClass: "is-open",
        customTrigger: false,
        onClose: null,
        onOpen: null
      };
      this.defaults = defaults;
      this.settings = { ...this.defaults, ...options };
      this.identity = this.element.dataset.modal
      this.btnOpen = document.querySelectorAll(
        '.js-modal-open[data-modal="' + this.identity + '"]'
      );
      this.btnClose = this.element.querySelectorAll(".js-modal-close");
      this.focusFirst = this.element.querySelector(".js-modal-focus-first");
      this.focusElements = this.element.querySelectorAll(a11yFocusElements)
      
      this.modalFigure = this.element.querySelector('.js-modal-figure');
      this.modalImage = this.element.querySelector('.js-modal-img');
      this.modalFigcaption = this.element.querySelector('.js-modal-figcaption');
      this.btnZoom = this.element.querySelectorAll('.js-modal-zoom');

      this.currentBtnOpen = null;

      // this.bpMedium = window.matchMedia('(max-width: 1035px)');
  
      this.init();
    }
  
    init() {
      this.prepModal();
      this.setEventListeners();
      this.bindCallbacks();
    }
  
    bindCallbacks() {
      if (typeof this.settings.onOpen === "function") {
        document.addEventListener(
          `modal/${this.identity}/open`,
          this.settings.onOpen.bind(this)
        );
      }
      if (typeof this.settings.onClose === "function") {
        document.addEventListener(
          `modal/${this.identity}/close`,
          this.settings.onClose.bind(this)
        );
      }
    }
  
    prepModal() {
      if (!this.element.hasAttribute("hidden")) {
        this.element.setAttribute("hidden", "");
      }
  
      ;[].forEach.call(this.focusElements, elem => {
        if (!elem.hasAttribute('tabindex')) {
          elem.tabIndex = -1
        }
      })
    }
  
    setEventListeners() {
      if (this.btnOpen.length) {
        [].forEach.call(this.btnOpen, button => {
          button.addEventListener("click", ev => {
            this.currentBtnOpen = button;
            this.openModal(button, ev)
          });
        });
      }
  
      [].forEach.call(this.btnClose, button => {
        button.addEventListener("click", ev => {
          this.closeModal(ev)
        });
      });
    }
  
    openModal(btn, ev) {
      if (!uHasClass(this.element, this.settings.openClass)) {
        this.element.removeAttribute("hidden");
        if (this.focusFirst) {
          this.focusFirst.focus();
        }
        /* set modal image and figcaption */
        this.modalImage.src = btn.dataset.imgSrc;
        this.modalImage.alt = btn.dataset.imgAlt;
        if (btn.dataset.imgCaption) {
          this.modalFigcaption.innerHTML = btn.dataset.imgCaption;
        }

        /* set zoom listeners */
        this.setZoomListeners();

        uAddClass(this.element, this.settings.openClass);
        a11yChildTabindexToggle(document.body);
        uStopPageScrollable();
      }
  
      document.dispatchEvent(
        uCreateCustomEvent(
          `modal/${this.identity}/open`,
          ev
        )
      )
    }
  
    closeModal(ev) {
      if (uHasClass(this.element, this.settings.openClass)) {
        this.element.setAttribute("hidden", "");

        /* reset modal image and figcaption */
        this.modalImage.src = "";
        this.modalImage.alt = "";
        this.modalFigcaption.innerHTML = "";

        //this.zoomOut();
        uRemoveClass(this.modalFigure, 'is-zoomed');
        
        uRemoveClass(this.element, this.settings.openClass);
        a11yChildTabindexToggle(document.body);
        uStartPageScrollable();
      }
  
      document.dispatchEvent(
        uCreateCustomEvent(
          `modal/${this.identity}/close`,
          ev
        )
      );
      // reset focus state
      if (this.currentBtnOpen) {
        this.currentBtnOpen.focus(); 
      }
    }

    setZoomListeners() {
      [].forEach.call(this.btnZoom, button => {
        // enable zoom by default
        if(button.dataset.zoom=="zoom-in") {
          button.disabled = false;
        } else {
          button.disabled = true;
        }

        button.addEventListener("click", ev => {

          // scale image appropriately
          if(button.dataset.zoom=="zoom-in") {
            //this.zoomIn() 
            uAddClass(this.modalFigure, 'is-zoomed');
            
          } else {
            //this.zoomOut()
            uRemoveClass(this.modalFigure, 'is-zoomed');
          }
          // toggle buttons 
          this.toggleZoomBtns(button);
        });
      });
    }

    /**
     * set the clicked zoom button to disabled, 
     * and the other to enabled
     */
    toggleZoomBtns(btn) {
      this.btnZoom.forEach(zoomBtn => {
        if (btn == zoomBtn) {
          zoomBtn.disabled = true;
        } else {
          zoomBtn.disabled = false;
        }
      });
    }

  }
  