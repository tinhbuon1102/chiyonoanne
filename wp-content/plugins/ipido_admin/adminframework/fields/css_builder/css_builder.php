<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: CSS Builder
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_css_builder extends CSFramework_Options {
    /**
     * CSFramework_Option_css_builder constructor.
     * @param        $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field, $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        echo $this->element_before();

        $is_select2 = ( isset($this->field['select2']) && $this->field['select2'] === TRUE ) ? 'select2' : '';
        $is_chosen = ( isset($this->field['chosen']) && $this->field['chosen'] === TRUE ) ? 'chosen' : '';
        echo '<div class="csf-css-builder-container csf-multifield">';

        echo csf_add_element(array(
            'pseudo'  => FALSE,
            'id'      => $this->field['id'] . '_content',
            'type'    => 'content',
            'content' => 'Note, that if you enter a value without a unit, the default unit <em>px</em> will automatically appended. If an invalid value is entered, it is replaced by the default value <em>0px</em>. Accepted units are: <em>px</em>, <em>%</em> and <em>em</em></p><p>Activate the lock <span class="dashicons dashicons-lock acf-css-checkall" style="margin:0"></span> to link all values.',
        ));


        echo '<div class="csf-css-builder-margin">';
        echo '<div><span class="dashicons csf-css-info dashicons-info"></span></div>';
        echo '<div class="csf-css-margin-caption">' . __("Margin", 'csf-framework') . '<span class="dashicons dashicons-lock csf-css-checkall csf-margin-checkall" ></span></div>';
        $this->_css_fields('margin');

        echo '<div class="csf-css-builder-border">';
        echo '<div class="csf-css-border-caption">' . __("Border", 'csf-framework') . '<span class="dashicons dashicons-lock csf-css-checkall csf-border-checkall" ></span></div>';
        $this->_css_fields('border');
        echo '<div class="csf-css-builder-padding">';
        echo '<div class="csf-css-padding-caption">' . __("Padding", 'csf-framework') . '<span class="dashicons dashicons-lock csf-css-checkall csf-padding-checkall" ></span></div>';
        $this->_css_fields('padding');
        echo '<div class="csf-css-builder-layout-center">';
        echo '<p>Lorem ipsum dolor sit amet, </p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';


        echo '<div class="csf-css-builder-extra-options">';
        $id = $this->unique . '[' . $this->field['id'] . ']';
        echo csf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'background-color',
            'before'	=> '<label>'.__('Background Color','csf-framework').'</label>',
        ), $this->field_val('background-color'), $id);
        echo csf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'border-color',
            'before'	=> '<label>'.__('Border Color','csf-framework').'</label>',
        ), $this->field_val('border-color'), $id);
        echo csf_add_element(array(
            'pseudo'    => true,
            'type'      => 'color_picker',
            'id'        => 'color',
            'before'	=> '<label>'.__('Text Color','csf-framework').'</label>',
        ), $this->field_val('color'), $id);
        echo csf_add_element(array(
            'pseudo'    => true,
            'type'    => 'select',
            'id'      => 'border-style',
            'before'	=> '<label>'.__('Border Style','csf-framework').'</label>',
            'class'   => $is_select2 . ' ' . $is_chosen,
            'options' => array(
                ''       => __("None", 'csf-framework'),
                'solid'  => __("Solid", 'csf-framework'),
                'dashed' => __("Dashed", 'csf-framework'),
                'dotted' => __("Dotted", 'csf-framework'),
                'double' => __("Double", 'csf-framework'),
                'groove' => __("Groove", 'csf-framework'),
                'ridge'  => __("Ridge", 'csf-framework'),
                'inset'  => __("Inset", 'csf-framework'),
                'outset' => __("Outset", 'csf-framework'),

            ),
        ), $this->field_val('border-style'), $id);

        echo '<div class="csf-css-builder-border-radius">';
        echo '<div class="csf-css-border-radius-caption">' . __("Border Radius", 'csf-framework') . '<span class="dashicons dashicons-lock csf-css-checkall csf-border-radius-checkall" ></span></div>';

        echo csf_add_element($this->carr(array(
            // 'title'      => __('Top Left', 'csf-framework'),
            'wrap_class' => 'csf-border-radius csf-border-radius-top-left',
            'id'         => 'border-radius-top-left',
            'before'      => '<label>'.__('Top Left','csf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-top-left'), $id);

        echo csf_add_element($this->carr(array(
            // 'title'      => __('Top Right', 'csf-framework'),
            'wrap_class' => 'csf-border-radius csf-border-radius-top-right',
            'id'         => 'border-radius-top-right',
            'before'      => '<label>'.__('Top Right','csf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-top-right'), $id);
        echo csf_add_element($this->carr(array(
            // 'title'      => __('Bottom Left', 'csf-framework'),
            'wrap_class' => 'csf-border-radius csf-border-radius-bottom-left',
            'id'         => 'border-radius-bottom-left',
            'before'      => '<label>'.__('Bottom Left','csf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-bottom-left'), $id);
        echo csf_add_element($this->carr(array(
            // 'title'      => __('Bottom Right', 'csf-framework'),
            'wrap_class' => 'csf-border-radius csf-border-radius-bottom-right',
            'id'         => 'border-radius-bottom-right',
            'before'      => '<label>'.__('Bottom Left','csf-framework').'</label>',
            'attributes' => array(
                'style' => 'width: 100px',
            ),
        )), $this->field_val('border-radius-bottom-right'), $id);

        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo $this->element_after();
    }

    /**
     * @param $type
     */
    private function _css_fields($type) {
        $id = $this->unique . '[' . $this->field['id'] . ']';
        echo csf_add_element($this->carr(array(
            'wrap_class' => 'csf-' . $type . ' csf-' . $type . '-top',
            'id'         => $type . '-top',
        )), $this->field_val($type . '-top'), $id);
        echo csf_add_element($this->carr(array(
            'wrap_class' => 'csf-' . $type . ' csf-' . $type . '-right',
            'id'         => $type . '-right',
        )), $this->field_val($type . '-right'), $id);
        echo csf_add_element($this->carr(array(
            'wrap_class' => 'csf-' . $type . ' csf-' . $type . '-bottom',
            'id'         => $type . '-bottom',
        )), $this->field_val($type . '-bottom'), $id);
        echo csf_add_element($this->carr(array(
            'wrap_class' => 'csf-' . $type . ' csf-' . $type . '-left',
            'id'         => $type . '-left',
        )), $this->field_val($type . '-left'), $id);
    }

    /**
     * @param        $new_arr
     * @param string $type
     * @return array
     */
    private function carr($new_arr, $type = '') {
        return array_merge(array(
            'pseudo'     => true,
            'type'       => 'text',
            'attributes' => array(
                'style' => 'width: 40px',
            ),
        ), $new_arr);
    }

    /**
     * @param string $type
     * @return null
     */
    private function field_val($type = '') {
        return ( isset($this->value[$type]) ) ? $this->value[$type] : NULL;
    }

}

