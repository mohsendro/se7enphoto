<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: persian date
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package http://babakhani.github.io/PersianWebToolkit/doc/datepicker/
 */
 /*******       LIMITATIONs   **********************
 * only date working, time may have problem as 'h:mm:ss a'
 *
 ***************************************************/
if ( ! class_exists( 'CSF_Field_pdate' ) ) {
  class CSF_Field_pdate extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );

      // add_action('csf/enqueue', [$this, 'csf_add_my_custom_css'] );

    }

    public function render() {

      $default_settings = array(
        'format' => 'dddd, DD MMMM YYYY',
      );

      $settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
      $settings = wp_parse_args( $settings, $default_settings );

      echo $this->field_before();


      echo '<input class="pd" type="text" name="'. esc_attr( $this->field_name() ) .'" data-date= "'.esc_attr($this->value).'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>';


      echo '<div class="csf-date-settings" data-settings="'. esc_attr( json_encode( $settings ) ) .'"></div>';


      // echo('<pre style="direction:ltr;text-align:left">');var_dump(($settings));echo('</pre>');


      echo $this->field_after();

    }

    public function enqueue() {

      // if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
      //   // wp_enqueue_script( 'jquery-ui-datepicker' );
      // }

      $plugins_url = plugin_dir_url(__FILE__);
      // Style
      if(!wp_style_is('sorth-codestar-pd')){
        wp_enqueue_style( 'sorth-codestar-pd', $plugins_url.'css/persian.datepicker.min.css' );
      }

      // Script
      if ( ! wp_script_is( 'sorth-codestar-pd' ) ) {
        wp_enqueue_script( 'sorth-codestar-pd', $plugins_url.'js/persian.date.min.js', array('jquery') );
      }

      if ( ! wp_script_is( 'sorth-codestar-pd-picker' ) ) {
        wp_enqueue_script( 'sorth-codestar-pd-picker', $plugins_url.'js/persian.datepicker.min.js', array('jquery') );
      }

      if ( ! wp_script_is( 'sorth-codestar-pd-run' ) ) {
        wp_enqueue_script( 'sorth-codestar-pd-run', $plugins_url.'js/sorth.js', array('jquery'), '', true );
      }


    }

  }
}
