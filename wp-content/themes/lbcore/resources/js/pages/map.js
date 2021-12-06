import mapboxgl from 'mapbox-gl';
import { uAjaxWithResponse, uScrollToElem, uGetScrollPosition, uGetCoords, uAddClass, uRemoveClass, uDebounce } from '../core/utils/motif.utilities';
import { AJAX_GET_LOCATION } from '../core/utils/musicRow.utilities';
import Permalink from './permalink';
import audio from '../shared/audio';
import Modal from '../shared/modal';
import Video from '../shared/video';

export default function () {
    return new Map(document.querySelector('.js-map'), {});
}

export class Map {
    constructor(element, options = {}) {
        this.element = element;
        this.config = {
            layers: [],
            mbAccessToken: window.MusicRow.mbAccessToken,
            defaultZoom: 14, // 16
            sortDefault: {
                layers: [],
                order: [],
                html: ''
            },
            sortDate: {
                layers: [],
                order: [],
                html: ''
            },
            bpMedium: window.matchMedia('(max-width: 1034px)'),
            bpSmall: window.matchMedia('(max-width: 767px)'),
        };
        
        this.sortBtns = this.element.querySelectorAll('.js-map-sort');
        this.sidebar = this.element.querySelector('.js-map-sidebar');
        this.sidebarCoords = uGetCoords(this.sidebar);
        this.sidebarControls = this.element.querySelector('.js-map-sidebar-controls');

        this.locationCardsContainer = this.element.querySelector('.js-map-cards[data-by="places"]');
        this.peopleCardsContainer = this.element.querySelector('.js-map-cards[data-by="people"]');
        this.cards = this.element.querySelectorAll('.js-map-card');
        this.locationCards = this.locationCardsContainer.querySelectorAll('.js-map-card[data-mb-layer-sort="places"]');
        this.peopleCards = this.peopleCardsContainer.querySelectorAll('.js-map-card[data-mb-layer-sort="people"]');
        this.defaultPersonCard = this.peopleCards[0];
        
        this.cardCoords = [];
        this.activeCard = null;

        //this.activeLayer = null
        this.permalink = new Permalink(this.element.querySelector('.js-map-permalink'), {});
        this.permalinkElement = this.element.querySelector('.js-map-permalink');
        this.permalinkInner = this.element.querySelector('.js-permalink-inner');
        this.jumpLink = document.querySelector('.js-map-jump');

        this.scrollPosition = uGetScrollPosition;
        this.scrollTrigger = requestAnimationFrame(this.handleScroll.bind(this));
        this.elemCoords = uGetCoords(this.element);

        this.mapContainer = null
        this.mapMarkers = [];
        this.mapPopups = [];
        this.popupsWithListeners = [];
        this.mapSticky = this.element.querySelector('.js-map-sticky');

        this.urlHash = window.location.hash;
        
        this.init();
    }
    
    init() {   
        if(this.jumpLink) {
            this.jumpLink.addEventListener('click', ev => {
            uScrollToElem(this.element);
        })}

        this.setMapWidth();

        window.addEventListener('resize', uDebounce(() => {
            requestAnimationFrame(() => {
                this.elemCoords = uGetCoords(this.element);
                this.setMapWidth(map);
            })
        }))

        mapboxgl.accessToken = this.config.mbAccessToken; // get our styles from studio.mapbox

        const map = new mapboxgl.Map({
            container: 'mapbox',
            style: 'mapbox://styles/musicrow/ckrch8tvr0k3a17tq6tc3uy7k',
            center: window.MusicRow.locations[0].geometry.coordinates, // center on first marker
            zoom: this.config.defaultZoom
        });

        this.mapContainer = map.getContainer()

        // disable scroll
        map.scrollZoom.disable(); 
        if (this.config.bpMedium.matches) {
            map.dragPan.disable();
            map.touchZoomRotate.disable();
        }
        
        map.addControl(new mapboxgl.NavigationControl(), 'bottom-right');

        /* add LOCATION markers and popups to map */
        for (const { geometry, properties } of window.MusicRow.locations.reverse() ) {

            // create a HTML element for each marker and popup
            const markerContent = document.createElement('div');
            markerContent.className = 'map__marker map__marker--locations is-active-layer theme--' + properties.theme;
            markerContent.innerHTML = properties.sort;
            markerContent.dataset.id = properties.id;
            markerContent.dataset.lngLat = geometry.coordinates;
            markerContent.dataset.layer = 'places';

            // make popup to bind to maker
            const popup = new mapboxgl.Popup( 
                { 
                    offset: 20, 
                    className: 'map__popup theme--' + properties.theme,
                    closeButton: false,
                    focusAfterOpen: false
                }) 
                .setHTML(properties.popup)
                .on('open', () => {
                    const popupElement = popup.getElement();
                    
                    // dont set listeners if they are already on the popup element
                    if (this.popupsWithListeners.includes(popupElement)) {
                        return;
                    }
                    this.popupsWithListeners.push(popupElement)

                    if (popupElement) {
                        this.setLocationPopupClickListener(popupElement);
                    }
                })
            
            // make a marker for each feature and add to the map
            const marker = new mapboxgl.Marker(markerContent)
                .setLngLat(geometry.coordinates)
                .setPopup(popup)
                .addTo(map);

            this.mapMarkers.push(marker);
            this.mapPopups.push(popup);
        }

        /* add PEOPLE markers and popups to map */
        for ( const person of window.MusicRow.people ) {

            for (const { geometry, properties } of person.markers.reverse() ) {
            
                // create a HTML element for each marker and popup
                const markerContent = document.createElement('div');
                markerContent.className = 'map__marker map__marker--people theme--' + person.theme;
                markerContent.innerHTML = properties.sort;
                markerContent.dataset.id = properties.id;
                markerContent.dataset.markerId = properties.markerId;
                markerContent.dataset.lngLat = geometry.coordinates;
                markerContent.dataset.layer = person.id;
                markerContent.dataset.sort = properties.sort;

                // make popup to bind to maker
                const popup = new mapboxgl.Popup( 
                    { 
                        offset: 20, 
                        className: 'map__popup map__popup--people theme--' + person.theme,
                        closeButton: false,
                        focusAfterOpen: false,
                    }) 
                    .setHTML(properties.popup)
                    .on('open', () => {
                        const popupElement = popup.getElement();
                        
                        // dont set listeners if they are already on the popup element
                        if (this.popupsWithListeners.includes(popupElement)) {
                            return;
                        }
                        this.popupsWithListeners.push(popupElement)
    
                        if (popupElement && popupElement.classList.contains('map__popup--people')) {
                            this.setPeoplePopupClickListener(popupElement);
                        }
                    })
                
                // make a marker for each feature and add to the map
                const marker = new mapboxgl.Marker(markerContent)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popup)
                    .addTo(map);

                this.mapMarkers.push(marker);
                this.mapPopups.push(popup);
            }

            // reset markers order
            window.MusicRow.locations.reverse()
            person.markers.reverse();
        }

        map.on("load", () => {
            // grab location coordinates JSON from window object
            map.addSource('locations', {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': window.MusicRow.locations
                }
            });

            // grab border source coords from window object 
            map.addSource('borders', {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Polygon',
                        'coordinates': [ window.MusicRow.borders ]
                    }
                }
            });

            // add layer for border
            map.addLayer({
                'id': 'border-fill',
                'type': 'fill',
                'source':  'borders',
                'layout': {},
                'paint': {
                    'fill-color': '#f0b23a',
                    'fill-opacity': 0.3
                },
            });

            this.initMarkerListeners(map)            
            this.initCards(map)
            this.initSortBtns(map)
            this.initStoryListeners()
            this.initNextPrevListeners()
            this.handleURL()
        });

        // Set an event listener that fires when an error occurs.
        // 'has-error' class will trigger a UI response as well.
        map.on('error', (e) => {
            console.log('Error:' , e);
            uAddClass(this.element, 'has-error');
            return;
        });
    }

    /************************************
     * Initializer & Listener Functions 
     ************************************/

    /**
     * Sets a click listener on map markers to center on the marker, 
     * set the marker and corresponding card to active, and open the map popup.
     */
    initMarkerListeners(map) {

        ;[].forEach.call(this.mapMarkers, (marker) => {

            const markerElement = marker.getElement();
            const popup = marker.getPopup();

            markerElement.addEventListener('click', ev => {

                this.toggleMarkerIcon(markerElement); // set marker to active

                let card = Array.from(this.peopleCards).filter(card => card.dataset.mbId == markerElement.dataset.layer);
       
                // people markers
                if(markerElement.classList.contains('map__marker--people')) {
                    let coords = markerElement.dataset.lngLat.split(',');

                    // y-offset for people popup
                    if (this.config.bpSmall.matches) {
                        coords[1] -= 0.0016; // small screens
                    }
                    else if (this.config.bpMedium.matches) {
                        coords[1] -= 0.0048;  // med screens
                    }
                    else {
                        coords[1] -= 0.0084; // lg and greater
                    }
                    this.centerMap(map, coords);
                    
                    if (card.length) {
                        this.setActiveCard(card[0]);
                    }
                } 
                // location markers
                else {
                    this.centerMap(map, markerElement.dataset.lngLat.split(','));
                    card = Array.from(this.locationCards).filter(card => card.dataset.mbId == markerElement.dataset.id);

                    if (card.length) {
                        this.beautifyURL(window.location.origin); 
                        this.scrollSidebar(card[0]); 
                    }
                }
                
                if (marker.getPopup().isOpen()) {
                    marker.togglePopup();
                }
            })
        })
    }

    /**
     * initializes map location and cards
     */    
    initCards(map) {
        this.initLocationCardClickListeners(map);
        this.initPeopleCardClickListeners(map);
    }

    /**
     * Listens for changes in our Places / People tabs
     */
     initSortBtns(map) {
        ;[].forEach.call(this.sortBtns, btn => {
            
            // click listener
            btn.addEventListener('click', ev => {

                this.toggleSortBtns(btn.dataset.by);
                this.toggleMarkers(btn.dataset.by);

                // center map if we click location tab
                if (btn.dataset.by=="places") {
                    this.centerMap(map, this.locationCards[0].dataset.mbCoords.split(','));
                }
                // set first person card to active if we click places tab
                else {
                    this.defaultPersonCard.click();
                }
            })

            // enter key listener (same functionality. just for keyboard users)
            btn.addEventListener('keyup',  ev => {
                if (ev.keyCode === 13) {
                    this.toggleSortBtns(btn.dataset.by);
                    this.toggleMarkers(btn.dataset.by);

                    // center map if we click location tab
                    if (btn.dataset.by=="places") {
                        this.centerMap(map, this.locationCards[0].dataset.mbCoords.split(','));
                    }
                    // set first person card to active if we click places tab
                    else {
                        this.defaultPersonCard.click();
                    }
                }
            })
        })
    }

    /**
     * Story Call To Action Listeners
     */
    initStoryListeners() {
        window.addEventListener('map/story/activate', ev => {
            this.toggleSortBtns('people');
            
            let card = Array.from(this.peopleCards).filter(card => card.dataset.mbId == ev.detail.person);

            if (card.length) {
                card[0].click();
            }
        })
    }

    /**
     * Next/Prev Permalink Action Listeners
     */
     initNextPrevListeners() {
        window.addEventListener('map/nextPrev/activate', ev => {

            let card = Array.from(this.locationCards).filter(card => card.dataset.mbId == ev.detail.location);

            if (card.length) {
                card[0].click();
            }
        })
    }

    /**
     * Location Card Click Listeners
     */
    initLocationCardClickListeners(map) {
        return new Promise(resolve => {
    
            ;[].forEach.call(this.locationCards, (card, i) => {
                /**
                 * Set card data-pre / data-next attributes after sorting
                */
                let prevID = i==0 ? null : this.cards[i-1].dataset.mbId;
                let nextID = i==this.cards.length-1 ? null : this.cards[i+1].dataset.mbId;
                
                card.dataset.prev = prevID;
                card.dataset.next = nextID;

                /**
                 * If user clicks a card, do the following:
                 * 1) Reset / beautify url with card permalink and title
                 * 2) Close any open popups
                 * 3) Center the map on the corresponding marker
                 * 4) Set the card's marker to "active"
                 * 5) Sroll sidebar to card and the card to "active"
                 * 6) Flyout the location panel
                 */
                card.addEventListener('click', () => {

                    // return if card is already active & permalink is open
                    if (this.activeCard == card && this.permalinkElement.classList.contains('is-active')) {
                        return;
                    }

                    let newURL = card.dataset.postPermalink;
                    let newTitle = card.dataset.postTitle;
                    this.beautifyURL(newURL, newTitle); 
                    this.closeAllPopups();
                    this.centerMap(map, card.dataset.mbCoords.split(','));
                    this.mapMarkers.forEach(marker => {
                        const markerElement = marker.getElement();
                        if (markerElement.dataset.id == card.dataset.mbId && markerElement.dataset.layer=="places") {
                            this.toggleMarkerIcon(markerElement);
                        }
                    });
                    this.getLocation(card.dataset.mbId, card.dataset.prev, card.dataset.next)
                        .then( () => {
                            // scroll sidebar
                            window.setTimeout( () => {
                                this.scrollSidebar(card); // timeout so it doesnt get overridden
                            }, 500);         
                        })
                })
            })
            resolve(true);
        })
    }

    /**
     * People Card Click Listeners
     */
    initPeopleCardClickListeners(map) {
        
        return new Promise(resolve => {

            ;[].forEach.call(this.peopleCards, (card, i) => {
                /**
                 * If user clicks a card, do the following:
                 * 1) reset / beautify url with card permalink and title
                 * 2) Center map coords
                 * 3) Close All popups
                 * 4) Toggle the person's markers to active, all others to inactive
                 * 5) Scroll sidebar
                 * 6) Reveal first popup for that person's layer
                 */
                card.addEventListener('click', ev => {  
                    let newURL = card.dataset.postPermalink;
                    let newTitle = card.dataset.postTitle;
                    this.beautifyURL(newURL, newTitle); 
                    this.centerMap(map, card.dataset.mbCoords.split(','));
                    this.closeAllPopups();
                    this.toggleMarkers(card.dataset.mbId);
                    this.revealFirstPopup(); 
                    window.setTimeout( () => {
                        this.scrollSidebar(card); // timeout so it doesnt get overridden
                    }, 500);
                })
            })
            resolve(true);
        })
    }

    /**
     * Location Popups:
     * finds the open popup on map, and sets click listener for it's CTA
     */
    setLocationPopupClickListener(popup) {
        if (popup) {
            let cta = popup.querySelector('.map__popup-cta');

            if (cta) {
                cta.addEventListener('click', ev => {
                    // update url
                    let newURL = cta.dataset.postPermalink;
                    let newTitle = cta.dataset.postTitle;
                    this.beautifyURL(newURL, newTitle); 
                    // close popups
                    this.closeAllPopups();
                    // open permalink
                    let id = cta.dataset.mbId;
                    this.getLocation(cta.dataset.mbId, cta.dataset.prev, cta.dataset.next);
                })        
            }
        }
    }

    /**
     * People Popups:
     * finds the open popup on map, and sets click listener for it's CTA.
     */
    setPeoplePopupClickListener(popup) {
        if (popup) {
            // initialize audio, modals, video
            audio();
            Modal();
            Video();
 
            // location CTA
            let cta = popup.querySelector('.artistPopup__cta');
            if (cta) {
                cta.focus();

                // click event
                cta.addEventListener('click', ev => {
                    let id = cta.dataset.mbId;
                    this.closeAllPopups();
                    this.toggleSortBtns('places');
                    this.toggleMarkers('places');
                    let card = Array.from(this.locationCards).filter(el => el.dataset.mbId == id);
                    if (card.length) {
                        card[0].click();
                    }
                    //this.getLocation(cta.dataset.mbId, cta.dataset.prev, cta.dataset.next);
                })        
            }

            // close button 
            let close = popup.querySelector('.artistPopup__close');
            if (close) {
                close.addEventListener('click', ev => {
                    this.closeAllPopups();
                })
            }

            // next & prev CTAs
            let markerCtas = popup.querySelectorAll('.artistPopup__nav');
            markerCtas.forEach( markerCta => {
                markerCta.addEventListener('click', ev => {
                    this.goToMarker(markerCta.dataset.mbId);
                })
            });
        }
    }


    /****************************************
     * Location Injection & URL Handling
     ****************************************/

    /**
     * checks if url has a people/place parameter
     * and if so toggles the tabs and activates that people/place card.
     * focuses the corresponding card for keyboard users.
     * 
     * parameter format: https://musicrow.lb2.lifeblue.us/?id=7&type=location
     */
     handleURL() {
         
        const params = new URLSearchParams(window.location.search);
        let id = params.get('id');
        let type = params.get('type');
        let sort = (type == 'location') ? 'places' : type; // 'place' post types are registered as 'location'
        
        if ( type && id ) {

            // go to the corresponding tab (people / places)
            this.toggleSortBtns(sort)
                .then(() => {
                    let card = Array.from(this.cards).filter(el => el.dataset.mbId == id);

                    // activate card
                    if (card.length) {
                        card[0].click(); // see initLocationCardClickListeners() and initPeopleCardClickListeners
                        //card[0].focus();
                    }

                    // scroll to map 
                    uScrollToElem(this.element);
                })
        }
    }

    /**
     * Resets page url without reloading the page.
     */
    beautifyURL(newURL, title="", stateInformation="") {
        if (!newURL) {
            return;
        }
        const nextState = { additionalInformation: stateInformation };
        // This will create a new entry in the browser's history, without reloading
        window.history.pushState(nextState, title, newURL);
        // This will replace the current entry in the browser's history, without reloading
        window.history.replaceState(nextState, title, newURL);
    }

    /**
     * Get the location data, initialize the Permalink panel.
     */
    getLocation(id, prevId=null, nextId=null) {

        return new Promise( resolve => {

            if (!sessionStorage.getItem('location-'+id)) {
                let path = AJAX_GET_LOCATION + "&location_id=" + id + "&prev_id=" + prevId + "&next_id=" + nextId;
                uAjaxWithResponse("POST", path, true)
                    .then(response => {
                        let data = JSON.parse(response);
        
                        this.buildPermalink(data, id)
                            .then( () => {
                                resolve(true);
                            })
                    })
            } else {           
                this.buildPermalink(JSON.parse(sessionStorage.getItem('location-'+id)), id)
                    .then( () => {
                        resolve(true);
                    })
            }
            resolve(true);
        })
    
    }

    buildPermalink(data, id) {
        return new Promise( resolve => {
            this.permalink.reset().then(() => {
                this.permalink.config.blocks = data.blocks;
                this.permalink.config.html = data.html;
                this.permalink.panel.dataset.location = id;
                this.permalink.build();
            }).then( () => {
                resolve(true);
            })
        })
    }


    /*********************
     * Class Toggling 
     * *******************/

    /**
     * Toggles a card between active and inactive.
     */
    setActiveCard(card) {

        if(!card || this.activeCard == card) {
            return;
        }

        if (this.activeCard == null) {
            this.activeCard = card;
            this.activeCard.classList.add('is-active');
            
        } else {
            this.unsetActiveCard()
                .then(() => {
                    this.activeCard = card;
                    this.activeCard.classList.add('is-active');
                })
        }
    }

    /**
     * Unset the current active card.
     */
    unsetActiveCard() {
        return new Promise(resolve => {

            this.activeCard.classList.remove('is-active');
            //this.activeLayer = null
    
            resolve(true);
        })
    }

    /**
     * Toggles a marker's icon colors from active to inactive state.
     * If no marker is passed in, all are set to inactive
    */
    toggleMarkerIcon(marker=null) {
        this.mapMarkers.forEach( mapMarker => {
            const mapMarkerElement = mapMarker.getElement();

            if (mapMarkerElement == marker) {
                uAddClass(mapMarkerElement, 'is-active');
            }
            else {
                uRemoveClass(mapMarkerElement, 'is-active');
            }
        });
    }

    /**
     * Toggles popup state from active to inactive.
     */
    togglePopupElements(popup) {
        const popupElement = popup.getElement();

        this.mapPopups.forEach ( mapPopup => {
            const mapPopupElement = mapPopup.getElement();

            if (popupElement == mapPopupElement) {
                uAddClass(mapPopupElement, 'is-active');
            } else {
                uRemoveClass(mapPopupElement, 'is-active');
            }
        })
    }

    /**
     * Toggles markers to only show those corresponding to the layer passed in.
     */
    toggleMarkers(layer) {
        this.mapMarkers.forEach ( mapMarker => {
            
            const markerElement = mapMarker.getElement();

            if (markerElement.dataset.layer == layer) {
                uAddClass(markerElement, 'is-active-layer');
            } else {
                uRemoveClass(markerElement, 'is-active-layer');
            }
        })
    }

    /** 
     * Toggles 'people/places' tabs  and map sidebar description depending on active tab
     * 
     * @param: 'sort'  will either be "people" or "places"
     */
    toggleSortBtns(sort) {
        return new Promise(resolve => {
            this.sidebarControls.dataset.activeSort = sort;

            this.sortBtns.forEach(btn => {
                if (btn.dataset.by==sort) {
                    uAddClass(btn, 'is-active');
                } else {
                    uRemoveClass(btn, 'is-active');
                }
            });

            this.elemCoords = uGetCoords(this.element);

            // toggle all map markers to inactive
            this.toggleMarkerIcon();
            // close all popups
            this.closeAllPopups();

            if (sort == 'people') {
                this.permalink.reset()
            }

            if (sort == "places") {
                this.beautifyURL(window.location.origin); // reset url for places tab 
            }
            resolve(true);
        })
    }

    /****************************
     * Map Specific Functions
     ****************************/

    /**
     * Utility function to center the map.
     */
    centerMap(map, center, zoom = this.config.defaultZoom, scrollTo = true) {
        // scroll map into full viewport height 
        uScrollToElem(this.element);
        
        // center map
        map.easeTo({
            center: center,
            zoom: zoom
        });
    }

    /**
     * Closes all map popups
     */
    closeAllPopups() {

        this.mapMarkers.forEach(marker => {

            if (marker.getPopup().isOpen()) {
                marker.togglePopup();
            }
        });
    }

    /**
     * Goes to marker with given ID within the active layer and opens its popup.
     * Will automatically close any other open popups.
     */
    goToMarker(markerID) {

        this.mapMarkers.forEach(marker => {
            
            const markerElement = marker.getElement();

            if (markerElement.dataset.markerId == markerID && markerElement.classList.contains('is-active-layer')) {
                marker.getElement().click();
            }
        });
    }

    /**
     * Finds the first marker in the active layer, 
     * and reveals its popup by clicking the marker.
     */
    revealFirstPopup() {

        this.mapMarkers.forEach(marker => {
            
            const markerElement = marker.getElement();

            if ((markerElement.dataset.sort == 1) && (markerElement.classList.contains('is-active-layer')))  {
                markerElement.click();

            }
        });
    }

    
    /*************************
     * Scroll Functions
     ************************/

    handleScroll() {
        let newScrollPos = uGetScrollPosition()

        if (this.stickyOn(newScrollPos)) {

            if (!this.element.classList.contains('is-sticky')) {
                this.element.classList.add('is-sticky');
           
                if (this.permalink.panel.classList.contains('is-hidden')) {
                    this.permalink.panel.classList.remove('is-hidden');
                }
            }
        }

        if (this.stickyOff(newScrollPos)) {
    
            if (this.element.classList.contains('is-sticky')) {
                this.element.classList.remove('is-sticky');
                this.element.classList.add('is-grounded');
            }
        }

        if (this.panelClosePos(newScrollPos) && this.permalink.panel.classList.contains('is-active')) {
            this.permalink.panel.classList.add('is-hidden');
        }

        if ( ( newScrollPos < this.elemCoords.top ) && this.element.classList.contains('is-grounded') ) {
            this.element.classList.remove('is-grounded');
        }

        this.scrollPosition = newScrollPos;
        let trigger = requestAnimationFrame(this.handleScroll.bind(this));
    }

    stickyOn(scrollPos) {
        return ( scrollPos > this.elemCoords.top && scrollPos < this.elemCoords.bottom - window.innerHeight );
    }

    stickyOff(scrollPos) {
        return ( scrollPos >= this.elemCoords.bottom - window.innerHeight || scrollPos < this.elemCoords.top );
    }

    panelClosePos(scrollPos) {
        return ( scrollPos >= ( ( this.elemCoords.bottom - window.innerHeight ) + ( window.innerHeight / 4 ) ) );
    }


    /**
     * Scrolls Sidebar to card passed in, and sets that card to active.
     * */
    scrollSidebar(card) {

        let cardCoords = uGetCoords(card);
        let cardtype = card.dataset.mbLayerSort;
        let allCards =  card.dataset.mbLayerSort == 'places' ? this.locationCards : this.peopleCards;

        /* small screens */
        if (window.screen.width < 768) {
            // first card
            if (card.dataset.mbSort == 0) {
                this.sidebar.scrollTo({ left: 0, behavior: 'smooth' });
            }
            // others
            else {
                let xCoord = card.offsetWidth * card.dataset.mbSort;
                this.sidebar.scrollTo({ left: xCoord, behavior: 'smooth' });
            }
        } 
        /* med and larger screens */
        else {
            //  cards outside of first 2 in view 
            if ( card.dataset.mbSort >= 2 ) {
                window.scrollTo({ top: cardCoords.bottom - window.innerHeight + 20, behavior: 'smooth' }); // adding 20px for offset
            } 
            // one of the first 2 in view 
            else {
                this.sidebar.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
        this.setActiveCard(card);
    }

    setMapWidth(map = null) {
        if (this.config.bpMedium.matches) { 
            this.mapSticky.removeAttribute('style')
            
            if (this.mapContainer !== null && map != null) {
                this.mapContainer.removeAttribute('style')
                window.setTimeout( () => map.resize(), 500 );
            }
            return
        }

        let width = (window.innerWidth - this.sidebar.offsetWidth) + 'px';

        this.mapSticky.style.width = width;

        if (this.mapContainer !== null && map != null) {
            this.mapContainer.style.width = width;
            window.setTimeout( () => map.resize(), 500 );
        }
    }

}