(function ($) {
    /**
    *  initialize_field
    *
    *  This function will initialize the $field.
    *
    *  @date	30/11/17
    *  @since	5.6.5
    *
    *  @param	n/a
    *  @return	n/a
    */

    function initialize_field($field) {
        let field = $field.get(0)
        let img = field.querySelector('.js-acf-adv-img')

        // init focal point scripts if image exists on page load
        if (img.dataset.hasImage) {
            new MediaPickerFocalPoint(field, {})
        }
    }

    if (typeof acf.add_action !== 'undefined') {
        /*
        *  ready & append (ACF5)
        *
        *  These two events are called when a field element is ready for initizliation.
        *  - ready: on page load similar to $(document).ready()
        *  - append: on new DOM elements appended via repeater field or other AJAX calls
        *
        *  @param	n/a
        *  @return	n/a
        */

        acf.add_action('ready_field/type=advanced_image', initialize_field);
        acf.add_action('append_field/type=advanced_image', initialize_field);

        var Field = acf.Field.extend({

            type: 'advanced-image',

            $control: function () {
                return this.$('.acf-image-uploader');
            },

            $input: function () {
                return this.$('input[type="hidden"].input-id');
            },

            events: {
                'click a[data-name="add"]': 'onClickAdd',
                'click a[data-name="edit"]': 'onClickEdit',
                'click a[data-name="remove"]': 'onClickRemove',
                'change input[type="file"]': 'onChange'
            },

            initialize: function () {

                // add attribute to form
                if (this.get('uploader') === 'basic') {
                    this.$el.closest('form').attr('enctype', 'multipart/form-data');
                }
            },

            validateAttachment: function (attachment) {

                // defaults
                attachment = attachment || {};

                // WP attachment
                if (attachment.id !== undefined) {
                    attachment = attachment.attributes;
                }

                // args
                attachment = acf.parseArgs(attachment, {
                    url: '',
                    alt: '',
                    title: '',
                    caption: '',
                    description: '',
                    width: 0,
                    height: 0
                });

                // preview size
                var url = acf.isget(attachment, 'sizes', this.get('preview_size'), 'url');
                if (url !== null) {
                    attachment.url = url;
                }

                // return
                return attachment;
            },

            render: function (attachment) {
                // vars
                attachment = this.validateAttachment(attachment);

                // update image
                this.$('img').attr({
                    src: attachment.url,
                    alt: attachment.alt,
                    title: attachment.title
                });

                // vars
                var val = attachment.id || '';

                // update val
                this.val(val);

                // update class
                if (val) {
                    this.$control().addClass('has-value');
                } else {
                    this.$control().removeClass('has-value');
                }

                focalPointInit(this.$el.get(0))
            },

            // create a new repeater row and render value
            append: function (attachment, parent) {

                // create function to find next available field within parent
                var getNext = function (field, parent) {

                    // find existing file fields within parent
                    var fields = acf.getFields({
                        key: field.get('key'),
                        parent: parent.$el
                    });

                    // find the first field with no value
                    for (var i = 0; i < fields.length; i++) {
                        if (!fields[i].val()) {
                            return fields[i];
                        }
                    }

                    // return
                    return false;
                }

                // find existing file fields within parent
                var field = getNext(this, parent);

                // add new row if no available field
                if (!field) {
                    parent.$('.acf-button:last').trigger('click');
                    field = getNext(this, parent);
                }

                // render
                if (field) {
                    field.render(attachment);
                }
            },

            selectAttachment: function () {

                // vars
                var parent = this.parent();
                var multiple = (parent && parent.get('type') === 'repeater');

                // new frame
                var frame = acf.newMediaPopup({
                    mode: 'select',
                    type: 'image',
                    title: acf.__('Select Image'),
                    field: this.get('key'),
                    multiple: multiple,
                    library: this.get('library'),
                    allowedTypes: this.get('mime_types'),
                    select: $.proxy(function (attachment, i) {
                        if (i > 0) {
                            this.append(attachment, parent);
                        } else {
                            this.render(attachment);
                        }
                    }, this)
                });
            },

            editAttachment: function () {

                // vars
                var val = this.val();

                // bail early if no val
                if (!val) return;

                // popup
                var frame = acf.newMediaPopup({
                    mode: 'edit',
                    title: acf.__('Edit Image'),
                    button: acf.__('Update Image'),
                    attachment: val,
                    field: this.get('key'),
                    select: $.proxy(function (attachment, i) {
                        this.render(attachment);
                    }, this)
                });
            },

            removeAttachment: function () {
                this.render(false);
            },

            onClickAdd: function (e, $el) {
                this.selectAttachment();
            },

            onClickEdit: function (e, $el) {
                this.editAttachment();
            },

            onClickRemove: function (e, $el) {
                console.log('on click remove')
                this.removeAttachment();
                clearInputs();
            },

            onChange: function (e, $el) {
                var $hiddenInput = this.$input();

                acf.getFileInputData($el, function (data) {
                    $hiddenInput.val($.param(data));
                });
            }
        });
        acf.registerFieldType(Field);

    }

    const ADV_IMG_DEFAULTS = {
        'focalPointInputSelector': '.js-acf-adv-img-fp-input',
        'focalPointMarkerSelector': '.js-acf-adv-img-marker',
        'imageSelector': '.js-acf-adv-img',
        'clearFocalPointButtonSelector': '.js-acf-adv-img-clear-marker'
    }
    
    class MediaPickerFocalPoint {
        constructor(elem, options) {
            this.elem = elem
            this.options = { ...ADV_IMG_DEFAULTS, ...options }
            this.image = this.elem.querySelector(this.options.imageSelector)
            this.focalPointInput = this.elem.querySelector(this.options.focalPointInputSelector)
            this.focalPointMarker = this.elem.querySelector(this.options.focalPointMarkerSelector)
            this.clearFocalPointButton = this.elem.querySelector(this.options.clearFocalPointButtonSelector)
            this.bindEvents()
            this.init()
        }
    
        init() {
            if (this.focalPointInput.value !== '') {
                this.renderFocalPoint(this.getFocalPointValuesFromString())
            } else {
                this.disableClearFocalPointButton()
            }
    
            ;[].forEach.call(this.elem.querySelectorAll('input, textarea, select'), field => {
                if (field.name.indexOf('[x]') > -1) {
                    field.name = field.name.replace('[x]', `[${this.options.id}]`)
                }
            })
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
            return this.getCoords(elem)
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
    

})(jQuery);
