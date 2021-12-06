 import { uAddClass, uRemoveClass, uGetCoords, uGetScrollPosition } from "../core/utils/motif.utilities"
 import Reveal from "../core/ui/motif.reveal.es6.js";

export default function (parent = document) {
    ;[].forEach.call(parent.querySelectorAll('.js-jumplinks'), elem => {
        return new Jumplinks(elem, {})
    })
}

export class Jumplinks {
    constructor(elem, options) {
        this.element = elem
        this.defaults = {}
        this.settings = {...this.defaults, ...options}
        this.bpSmall = window.matchMedia('(max-width: 767px)');

        this.jumplinkWrapper = this.element.querySelector('.permalink__jumplinks-wrapper');
        this.jumplinkReveal = this.element.querySelector('.js-jumplinks-reveal');

        this.wrapperHeight = this.jumplinkWrapper.offsetHeight;
        if (this.bpSmall.matches) {
            this.wrapperHeight = this.jumplinkReveal.offsetHeight;
        }
        
        this.jumplinkTriggers = this.element.querySelectorAll('.js-jumplink-trigger');
        this.jumplinkTargets = this.element.querySelectorAll('.js-jumplink-target');
        this.perma = document.querySelector('.js-map-permalink');
        this.scrollPosition = uGetScrollPosition();
        this.legoBlocks = [];
        this.currentBlock = [];

        this.init()
    }

    init () {
        /**
         * our images load with a slight lag, 
         * so in order to get the correct lego block coordinates
         * we need to run our functions after a 3second timeout. 
         */ 
        window.setTimeout( () => {
            this.getlegoBlocks()
                .then( (legoBlocks ) => {
                    this.legoBlocks = legoBlocks;
                    this.setEventListeners(); 
                    this.handleScroll();
                });
        }, 3000);  // todo: try to optimize so wait is not as long.
                    // todo: check timeut for mobile

        // Init dropdown reveal
        const dropdown = new Reveal('.js-jumplinks-reveal', {
            type: "exclusive",
            activeClass: "is-revealed",
            visitedClass: "was-revealed"
        });
    }

    /** 
     * updates this.legoBlocks with the coordinates 
     * of all lego blocks for this location
     */
    getlegoBlocks() {
        return new Promise( resolve => {
            let legoReturn = [];

            this.jumplinkTriggers.forEach( (jumplink, i) => {
        
                let legoID = jumplink.dataset.trigger;
                let legoSelector = "[data-target='" + legoID + "']";
                let legoElement = this.element.querySelector(legoSelector);

                legoReturn.push({ 
                    id: legoID,
                    coords: uGetCoords(legoElement),
                    order: i
                });
            })
            resolve(legoReturn);
        })
    }

    /**
     * updates lego block coordinates and wrapper height on window resize
     */
    updateLegoCoords() {
        window.addEventListener('resize', ev => {
            this.wrapperHeight = this.jumplinkWrapper.offsetHeight;
            if (this.bpSmall.matches) {
                this.wrapperHeight = this.jumplinkReveal.offsetHeight;
            }
            this.legoBlocks = this.getlegoBlocks();
        })
    }

    /**
     * sets even listeners for jumplink clicks
     */
    setEventListeners() {
        if (this.jumplinkTriggers) {
            ;[].forEach.call(this.jumplinkTriggers, button => {

                button.addEventListener('click', ev => { 

                    let id = button.dataset.trigger;
                    let targetSelector = "[data-target='" + id + "']";
                    let target = this.element.querySelector(targetSelector);

                    if (target) {
                        this.scrollToTarget(target);
                    }

                    // todo: take this out once handleScroll is working properly.
                    this.toggleJumplinks(button);
                })
            })
        }
    }

    /**
     * scroll to clicked target so long as it is not a repeat click
     */
    scrollToTarget(target) {
        let newScrollPosition = 0;

        /* set scroll position by grabbing block coordinates */
        this.legoBlocks.forEach( lego => {
            if (lego.id == target.dataset.target) {
               // newScrollPosition = lego.coords.top - window.innerHeight - this.wrapperHeight;
               newScrollPosition = lego.coords.top - window.scrollY - this.wrapperHeight;
            }
        })

        /* only scroll if our desired scroll position is different than the current one */
        if (newScrollPosition != this.scrollPosition && newScrollPosition > 0) {
            this.perma.scrollTo({
                top: newScrollPosition,
                left: 0,
                behavior: 'smooth'
            }); 
            this.scrollPosition = this.perma.scrollTop; 
        }
    }

    /**
     * Updates active jumplink as user scrolls
     */
     handleScroll() {
        let newScrollPos =  this.perma.scrollTop;
        // console.log('new scroll:', newScrollPos);

        for (const legoBlock of this.legoBlocks) {
        
            if ( newScrollPos >= (legoBlock.coords.top - window.scrollY - this.wrapperHeight ) 
                    && ( newScrollPos <= (legoBlock.coords.bottom - window.scrollY) ) 
                    && ( this.currentBlock.coords != legoBlock.coords)
            ) {
            
                this.currentBlock.coords = legoBlock.coords;
                this.currentBlock.jumpLinkTrigger = Array.from(this.jumplinkTriggers).filter(elem => elem.dataset.trigger == legoBlock.id)[0];
                this.currentBlock.jumplinkTarget = legoBlock;
                
                this.toggleJumplinks(this.currentBlock.jumpLinkTrigger);
            }
        }
        this.scrollPosition = newScrollPos;
        let trigger = requestAnimationFrame(this.handleScroll.bind(this));
    }

    /** 
     * sets current jumplink to 'is-active'
     * removes 'is-active' from all other jumplinks
     */
     toggleJumplinks(currJumplink) {
        this.jumplinkTriggers.forEach(trigger => {
            if (currJumplink == trigger) {
                uAddClass(trigger, 'is-active');
            } else {
                uRemoveClass(trigger, 'is-active');
                trigger.blur(); // temp
            }
        });
    }

}