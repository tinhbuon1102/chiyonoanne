<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image Size
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_image_size extends CSFramework_Options {
    /**
     * CSFramework_Option_image_size constructor.
     * @param        $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field, $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        echo $this->element_before();

        $default_width = ( isset($this->field['default']) && isset($this->field['default']['width']) ) ? $this->field['default']['width'] : '';
        $default_height = ( isset($this->field['default']) && isset($this->field['default']['height']) ) ? $this->field['default']['height'] : '';
        $default_crop = ( isset($this->field['default']) && isset($this->field['default']['crop']) ) ? $this->field['default']['crop'] : '';

        $width = ( isset($this->value['width']) ) ? $this->value['width'] : $default_width;
        $height = ( isset($this->value['height']) ) ? $this->value['height'] : $default_height;
        $crop = ( isset($this->value['crop']) ) ? $this->value['crop'] : $default_crop;

        echo csf_add_element(array(
            'id'         => $this->field['id'] . '_width',
            'pseudo'     => FALSE,
            'type'       => 'text',
            'name'       => $this->element_name('[width]'),
            'value'      => $width,
            'attributes' => array(
                'placeholder' => __('Width','csf-framework'),
                'style'       => 'width:50px;',
                'size'        => 3,
            ),
        ));

        echo ' x ';

        echo csf_add_element(array(
            'id'         => $this->field['id'] . '_height',
            'pseudo'     => FALSE,
            'type'       => 'text',
            'name'       => $this->element_name('[height]'),
            'value'      => $height,
            'attributes' => array(
                'placeholder' => __('Height','csf-framework'),
                'style'       => 'width:50px;',
                'size'        => 3,
            ),
        ));

        echo csf_add_element(array(
            'id'     => $this->field['id'] . '_crop',
            'pseudo' => FALSE,
            'type'   => 'checkbox',
            'name'   => $this->element_name('[crop]'),
            'value'  => $crop,
            'label'  => __('Hard Crop ?','csf-framework'),
        ));

        echo $this->element_after();
    }
}
