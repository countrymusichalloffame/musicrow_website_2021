<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('acf_field_advanced_image') ) :

class acf_field_advanced_image extends acf_field {

	function __construct( $settings ) {

		$this->name = 'advanced_image';
		$this->label = __('Advanced Image', 'acf-advanced-image');

		$this->category = 'content';
		$this->settings = $settings;
		$this->defaults = array(
			'return_format'	=> 'array',
			'preview_size'	=> 'large',
			'library'		=> 'all',
			'min_width'		=> 0,
			'min_height'	=> 0,
			'min_size'		=> 0,
			'max_width'		=> 0,
			'max_height'	=> 0,
			'max_size'		=> 0,
			'mime_types'	=> ''
		);

		// filters
		add_filter('get_media_item_args',				array($this, 'get_media_item_args'));

    	parent::__construct();
	}
    
    
    /*
	*  input_admin_enqueue_scripts
	*
	*  description
	*
	*  @type	function
	*  @date	16/12/2015
	*  @since	5.3.2
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function input_admin_enqueue_scripts() {
		
		// localize
		acf_localize_text(array(
		   	'Select Image'	=> __('Select Image', 'acf'),
			'Edit Image'	=> __('Edit Image', 'acf'),
			'Update Image'	=> __('Update Image', 'acf'),
			'All images'	=> __('All images', 'acf'),
	   	));
	}

	function get_image_data( $value = [] ) {
		
		$image = array(
			'id' => '',
			'url' => '',
			'alt' => '',
			'focal-point' => '',
			'focal-point-zoom' => '',
			'additional-params' => '',
			'caption' => ''
		);
		
		if( is_array($value) ) {
			$image = array_merge($image, $value);
		}

		return $image;
	}
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function render_field( $field ) {

		// vars
		$uploader = acf_get_setting('uploader');

		// enqueue
		if( $uploader == 'wp' ) {
			acf_enqueue_uploader();
		}

		// vars
		$url = '';
		$alt = '';
		$div = array(
			'class'					=> 'acf-image-uploader',
			'data-preview_size'		=> $field['preview_size'],
			'data-library'			=> $field['library'],
			'data-mime_types'		=> $field['mime_types'],
			'data-uploader'			=> $uploader
		);

		$imgData = $this->get_image_data($field['value']);

		// has value?
		if( $imgData['id'] ) {
			// update vars
			$url = wp_get_attachment_image_src($imgData['id'], $field['preview_size']);
			$alt = get_post_meta($imgData['id'], '_wp_attachment_image_alt', true);
			
			// url exists
			$url = $url ? $url[0] : '';

			// url exists
			if( $url ) {
				$div['class'] .= ' has-value';
			}

		}

		// get size of preview value
		$size = acf_get_image_size($field['preview_size']);

        // echo "<pre>";var_dump($field);echo"</pre>";
?>
<div <?php acf_esc_attr_e( $div ); ?>>
	<div class="acf-hidden">
		<?php foreach( $imgData as $k => $v ): ?>
			<?php acf_hidden_input(array( 'class' => "input-$k", 'name' => $field['name'] . "[$k]", 'value' => $v )); ?>
		<?php endforeach; ?>
	</div>
	<div class="show-if-value image-wrap" <?php if( $size['width'] ): ?>style="<?php echo esc_attr('max-width: '.$size['width'].'px'); ?>"<?php endif; ?>>
		<div class="lb__adv-img-container">
			<div class="lb__adv-img-marker js-acf-adv-img-marker"></div>
			<img class="lb__adv-img js-acf-adv-img"
				src="<?php echo esc_url($url); ?>"
				alt="<?php echo esc_attr($alt); ?>"
				data-name="image"
				data-has-image="<?= !empty($url) ? 'true' : 'false'; ?>"
			/>
		</div>
		<div class="acf-actions -hover">
			<?php
			if( $uploader != 'basic' ):
			?><a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php _e('Edit', 'acf'); ?>"></a><?php
			endif;
			?><a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php _e('Remove', 'acf'); ?>"></a>
		</div>
		<div class="acf-field">
			<div class="acf-label">
				<label>Focal Point</label>
				<p class="description">Click anywhere on the image to add focal point</p>
			</div>
			<input class="js-acf-adv-img-fp-input"
				type="text" 
				name="<?= $field['name'] ?>[focal-point]"
				value="<?= $imgData['focal-point'] ?? ''; ?>"
			/>
			<button type="button" class="acf-button button button-primary js-acf-adv-img-clear-marker">
				Clear Focal Point
			</button>
		</div>
		<div class="acf-field">
			<div class="acf-label">
				<label>Focal Point Zoom</label>
			</div>
			<input class="js-acf-adv-img-input"
				type="text"
				name="<?= $field['name'] ?>[focal-point-zoom]"
				value="<?= $imgData['focal-point-zoom'] ?? ''; ?>"
				placeholder="Ex: 1-10"
			/>
		</div>
		<div class="acf-field">
			<div class="acf-label">
				<label>Additional Parameters</label>
				<p class="description">
					See <a href="<?php echo esc_url('https://docs.imgix.com/apis/url'); ?>">imgix API documentation</a> for a full list of accepted parameters.
				</p>
			</div>
			<input class="js-acf-adv-img-input"
				type="text"
				name="<?= $field['name'] ?>[additional-params]"
				value="<?= $imgData['additional-params'] ?? ''; ?>"
				placeholder="Ex: q=60"
			/>
		</div>
	</div>
	<div class="hide-if-value">
		<?php if( $uploader == 'basic' ): ?>

			<?php if( $field['value'] && !is_numeric($field['value']) ): ?>
				<div class="acf-error-message"><p><?php echo acf_esc_html($field['value']); ?></p></div>
			<?php endif; ?>

			<label class="acf-basic-uploader">
				<?php acf_file_input(array( 'name' => $field['name'], 'id' => $field['id'] )); ?>
			</label>

		<?php else: ?>

			<p><?php _e('No image selected','acf'); ?> <a data-name="add" class="acf-button button" href="#"><?php _e('Add Image','acf'); ?></a></p>

		<?php endif; ?>
	</div>
</div>
<?php

	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function render_field_settings( $field ) {
		
		// clear numeric settings
		$clear = array(
			'min_width',
			'min_height',
			'min_size',
			'max_width',
			'max_height',
			'max_size'
		);
		
		foreach( $clear as $k ) {
			
			if( empty($field[$k]) ) {
				
				$field[$k] = '';
				
			}
			
		}
		
		
		// return_format
		acf_render_field_setting( $field, array(
			'label'			=> __('Return Format','acf'),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'layout'		=> 'horizontal',
			'choices'		=> array(
				'array'			=> __("Image Array",'acf'),
				'url'			=> __("Image URL",'acf'),
				'id'			=> __("Image ID",'acf')
			)
		));
		
		
		// preview_size
		acf_render_field_setting( $field, array(
			'label'			=> __('Preview Size','acf'),
			'instructions'	=> '',
			'type'			=> 'select',
			'name'			=> 'preview_size',
			'choices'		=> acf_get_image_sizes()
		));
		
		
		// library
		acf_render_field_setting( $field, array(
			'label'			=> __('Library','acf'),
			'instructions'	=> __('Limit the media library choice','acf'),
			'type'			=> 'radio',
			'name'			=> 'library',
			'layout'		=> 'horizontal',
			'choices' 		=> array(
				'all'			=> __('All', 'acf'),
				'uploadedTo'	=> __('Uploaded to post', 'acf')
			)
		));
		
		
		// min
		acf_render_field_setting( $field, array(
			'label'			=> __('Minimum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'min_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'min_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'min_width'
		));	
		
		
		// max
		acf_render_field_setting( $field, array(
			'label'			=> __('Maximum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'max_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'max_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'max_width'
		));	
		
		
		// allowed type
		acf_render_field_setting( $field, array(
			'label'			=> __('Allowed file types','acf'),
			'instructions'	=> __('Comma separated list. Leave blank for all types','acf'),
			'type'			=> 'text',
			'name'			=> 'mime_types',
		));
		
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	
	function format_value( $value, $post_id, $field ) {
		// bail early if no value
		if( empty($value) ) return false;
		
		$imageData = $this->get_image_data( $value );
		
		
		// return
		return $imageData;
		
	}
	
	
	/*
	*  get_media_item_args
	*
	*  description
	*
	*  @type	function
	*  @date	27/01/13
	*  @since	3.6.0
	*
	*  @param	$vars (array)
	*  @return	$vars
	*/
	
	function get_media_item_args( $vars ) {
	
	    $vars['send'] = true;
	    return($vars);
	    
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	
    function update_value( $value, $post_id, $field ) {
        $src = wp_get_attachment_image_src($value['id'], 'full');//, $field['preview_size']);
        $src = $src ? $src[0] : '';
		$imgData = array(
			'id' => $value['id'],
			'url' => $src,
			'alt' => get_post_meta($value['id'] , '_wp_attachment_image_alt', true),
			'focal-point' => $value['focal-point'],
			'focal-point-zoom' => $value['focal-point-zoom'],
			'additional-params' => $value['additional-params'],
			'caption' => wp_get_attachment_caption( $value['id'])
		);
        // echo "<pre>";var_dump($value, $field, $imgData);echo"</pre>";die;
		return $imgData;
	}


	/*
	*  validate_value
	*
	*  This function will validate a basic file input
	*
	*  @type	function
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	// function validate_value( $valid, $value, $field, $input ){
		
	// 	return acf_get_field_type('file')->validate_value( $valid, $value, $field, $input );
		
	// }

}

new acf_field_advanced_image( $this->settings );

function input_admin_enqueue_scripts()
{	
	
	// register & include JS
	wp_register_script('acf-advanced-image', plugins_url() . "/acf-advanced-image/assets/js/advanced-image.js", array('acf-input'), '1.0.0');
	wp_enqueue_script('acf-advanced-image');
	
	
	// register & include CSS
	wp_register_style('acf-advanced-image', plugins_url() . "/acf-advanced-image/assets/css/advanced-image.css", array('acf-input'), '1.0.0');
	wp_enqueue_style('acf-advanced-image');
	
}

add_action('admin_enqueue_scripts', 'input_admin_enqueue_scripts');

endif;
