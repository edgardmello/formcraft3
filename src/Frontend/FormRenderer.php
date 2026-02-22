<?php

namespace FormCraft\Frontend;

/**
 * FormRenderer
 *
 * Handles form rendering on the frontend.
 * Extracted from legacy.php in v3.9.13.
 */
class FormRenderer
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handleShortcode( $atts, $content = '' ) {
    global $fc_meta, $fc_forms_table, $fc_progress_table, $wpdb, $FormCraftFooterJS, $fc_translate;

    extract( shortcode_atts( array(
      'id' => '1',
      'align' => 'left',
      'type' => 'inline',
      'bind' => '',
      'placement' => '',
      'class' => '',
      'font_color' => '',
      'button_color' => '',
      'auto' => ''
      ), $atts ) );

    $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $id);
    $meta = $wpdb->get_var($query);
    if ($meta==NULL) {
      return esc_html__('This form does not exist', 'formcraft');
    }
    $meta = \FormCraft\Security\Sanitizer::decodeDbJson($meta, true);
    $load_datepicker = false;
    $load_slider = false;
    $load_fileupload = false;
    $load_address = false;
    foreach ($meta['fields'] as $key => $value) {
        $load_datepicker = $value['type']=='datepicker' || $value['type']=='' ? true : $load_datepicker;
        $load_slider = $value['type']=='slider' || $value['type']=='' ? true : $load_slider;
        $load_fileupload = $value['type']=='fileupload' || $value['type']=='' ? true : $load_fileupload;
        if ($value['type']=='address'  && !empty($value['elementDefaults']['google_key'])) {
        $load_address = $value['elementDefaults']['google_key'];
        } else {
        $load_address = $load_address ? $load_address : false;
        }
    }
    $dependencies = array('jquery', 'jquery-ui-core','jquery-ui-mouse');
    if ($load_datepicker===true) {
      $dependencies[] = 'jquery-ui-datepicker'; wp_enqueue_script('jquery-ui-datepicker');
    }
    if ($load_fileupload===true) {
      $dependencies[] = 'jquery-ui-widget'; wp_enqueue_script('jquery-ui-widget');
    }
    if ($load_slider===true) {
      $dependencies[] = 'jquery-ui-widget'; $dependencies[] = 'jquery-ui-slider';
      $dependencies[] = 'jquery-ui-mouse';
    }
    if ($load_fileupload===true) {
      wp_enqueue_script('fileupload', plugins_url( '../../assets/js/vendor/jquery.fileupload.js', __FILE__ ),array('jquery-ui-widget'));
      wp_localize_script( 'fileupload', 'FC_f',
        array(
          'ajaxurl' => admin_url( 'admin-ajax.php' )
          )
        );
    }
    if ($load_address) {
      wp_enqueue_script('typeahead', plugins_url( '../../assets/js/vendor/typeahead.min.js', __FILE__ ), array('jquery'), $fc_meta['version']);
      wp_enqueue_script('typeahead-address', plugins_url( '../../assets/js/vendor/typeahead-addresspicker.min.js', __FILE__ ), array('jquery'), $fc_meta['version']);
      wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key='.$load_address, array('jquery'));
    }
    wp_enqueue_script('fc-modal', plugins_url( '../../assets/js/src/fc_modal.js', __FILE__ ), array('jquery'), $fc_meta['version']);
    wp_enqueue_script('tooltip', plugins_url( '../../assets/js/vendor/tooltip.min.js', __FILE__ ), array('jquery', 'fc-modal'));
    wp_enqueue_script('awesomplete', plugins_url('../../lib/awesomplete.min.js', __FILE__ ));
    wp_enqueue_script('fc-form', plugins_url( '../../dist/form.min.js', __FILE__ ), $dependencies, $fc_meta['version']);

    foreach ($dependencies as $key => $value) {
      wp_enqueue_script($value);
    }

    /* Allow Add-Ons to Load Their Scripts */
    do_action('formcraft_form_scripts', $id);

    if (!empty($button_color) && $placement!='left' && $placement!='right') {
      $class = 'simple_button';
    }

    if (!ctype_digit($id)) {
      return '';
    }

    if (!empty($meta['config']['Messages'])) {
      unset($meta['config']['Messages']['success']);
      wp_localize_script( 'fc-form', 'FC',
        array(
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
          'fct' => $fc_translate,
          'datepickerLang' => plugins_url( '../../assets/js/datepicker-lang/', __FILE__ )
          )
        );
      global $footerVariables;
      $footerVariables = isset($footerVariables) && is_array($footerVariables) ? $footerVariables : array();
      $footerVariables[] = array('var'=>'FC_Validation_'.$id, 'data'=>json_encode($meta['config']['Messages']));
      // formcraft3_global_js_variable('FC_Validation_'.$id, json_encode($meta['config']['Messages']));
    }

    if (isset($_COOKIE['fc_sb_'.$id]) && isset($meta['config']['disable_multiple']) && $meta['config']['disable_multiple']==true) {
      if ( (!is_user_logged_in() || ( is_user_logged_in() && !isset($_GET['preview']) ) ) || !formcraft3_check_form_page() ) {
        if (isset($meta['config']['disable_multiple_message']) && $meta['config']['disable_multiple_message']!='' && $type!='popup') {
          return "<div class='form-disabled-message'>".$meta['config']['disable_multiple_message']."</div>";
        } else {
          return '';
        }
      }
    }
    if ( isset($meta['config']['form_disable']) && $meta['config']['form_disable']==true ) {
      if ( !empty($meta['config']['disable_after']) && !empty($meta['config']['disable_after_nos']) && !empty($meta['config']['form_disable_after_message']) ) {
        $meta['config']['form_disable_message'] = $meta['config']['form_disable_after_message'];
      }
      if ( (!is_user_logged_in() || ( is_user_logged_in() && !isset($_GET['preview']) ) ) || !formcraft3_check_form_page() )
      {
        if (isset($meta['config']['form_disable_message']) && $meta['config']['form_disable_message']!='' && $type!='popup')
        {
          return "<div class='form-disabled-message'>".$meta['config']['form_disable_message']."</div>";
        }
        else
        {
          return '';
        }
      }
    }

    if (isset($meta['config']['font_family']) && strpos($meta['config']['font_family'], 'Arial')===false && strpos($meta['config']['font_family'], 'sans-serif')===false && strpos($meta['config']['font_family'], 'Courier')===false && strpos($meta['config']['font_family'], 'inherit')===false) {
      $meta['config']['font_family'] = str_replace(' ', '+', $meta['config']['font_family']);
      $protocol = is_ssl() ? 'https' : 'http';
      $query_args = array(
        'family' => $meta['config']['font_family'].':400,600,700'
        );
      wp_enqueue_style('font-'.$meta['config']['font_family'],
        add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),
        array(), null);
    }

    $meta['config']['Custom_CSS'] = empty($meta['config']['Custom_CSS']) ? '' : $meta['config']['Custom_CSS'];
    $custom_css = empty($meta['config']['Custom_CSS']) ? "" : "<style type='text/css' scoped='scoped'>".$meta['config']['Custom_CSS']."</style>";

    $meta['config']['CustomJS'] = empty($meta['config']['CustomJS']) ? '' : $meta['config']['CustomJS'];
    $FormCraftFooterJS = isset($FormCraftFooterJS) && is_array($FormCraftFooterJS) ? $FormCraftFooterJS : array();
    $FormCraftFooterJS[] = $meta['config']['CustomJS'];


    $query = $wpdb->prepare("SELECT html FROM $fc_forms_table WHERE id = %d", $id);
    $html = $wpdb->get_var($query);
    if ( substr($html,0,10) == 'rawdeflate' ) {
      $html = gzinflate(base64_decode(rawurldecode(substr($html,11))),0);
    }
    $html = str_replace('fc_form_', 'fc-form-', $html);
    $html = str_replace('fc_form ', 'fc-form ', $html);
    $html = str_replace(' has-input', ' ', $html);
    $html = wp_unslash($html);

    $pattern = get_shortcode_regex();
    preg_match_all('/'. $pattern .'/s', $html, $matches);
    foreach ( $matches[0] as $x ) {
      $html = str_replace($x, "<div class='fc-third-party'>".do_shortcode($x)."</div>", $html);
    }

    if (empty($html)) {
      return '';
    }
    $uniq = uniqid();
    if (isset($meta['config']['save_progress']) && $meta['config']['save_progress']==true && isset($_COOKIE["fc_sp_$id"])) {
      $cookie = preg_replace("/\W|_/", "", $_COOKIE["fc_sp_$id"]);
      if ($cookie != '') {
        $query = $wpdb->prepare("SELECT content FROM $fc_progress_table WHERE uniq_key = %s", $cookie);
        $pre_data = $wpdb->get_var($query);
        if ($pre_data != null && $pre_data != '' && $pre_data != 'null') {
          $pre_data = \FormCraft\Security\Sanitizer::decodeDbJson($pre_data, true);
          foreach ($pre_data as $key => $value) {
            if ( !is_array($value) && $value == '' ) {
              unset($pre_data[$key]);
            } else if ( is_array($value) && count($value) == 1 && $value[0] == '' ) {
              unset($pre_data[$key]);
            }
          }
          if (count($pre_data)>0) {
            $saved_data = "<div class='pre-populate-data'>".json_encode($pre_data)."</div>";
          }
        }
      }
    }

    $pre_data = isset($pre_data) ? $pre_data : '';
    $saved_data = isset($saved_data) ? $saved_data : '';

    ob_start();
    do_action('formcraft_form_content', $id, $meta, $pre_data, $atts);
    $addon_content = ob_get_contents();
    ob_end_clean();
    $showPowered = true;
    if ( $fc_meta['preview_mode'] == true ) {
      $showPowered = false;
    }
    if ( is_multisite() ) {
      if ( $fc_meta['f3_multi_site_addon'] ) {
        $showPowered = false;
      } else if ( get_site_option('f3_verified') == 'yes' && get_site_option('f3_blog_id') == get_current_blog_id() ) {
        $showPowered = false;
      }
    } else {
      if ( get_site_option('f3_verified') == 'yes' ) {
        $showPowered = false;
      }
    }
    $powered_by = $showPowered ? '<a class="powered-by" target="_blank" href="http://formcraft-wp.com?source=pb"/>FormCraft - '.esc_html__('WordPress form builder', 'formcraft').'</a>' : '';
    $meta['config']['Logic'] = isset($meta['config']['Logic']) ? $meta['config']['Logic'] : array();
    $logicScript = "<script> window.formcraftLogic = window.formcraftLogic || {}; window.formcraftLogic[".$id."] = ".json_encode($meta['config']['Logic'])."; </script>";

    if ($type=='popup') {
      wp_enqueue_script('fc-modal', plugins_url( '../../assets/js/src/fc_modal.js', __FILE__ ), array(), $fc_meta['version']);
      if ( $placement=='left' || $placement=='right' )
      {
        $button = "<div class='formcraft-css body-append image_button_cover placement-$placement'><a data-toggle='fc_modal' data-target='#modal-$uniq' style='background-color: $button_color; color: $font_color' class='$class'>$content</a>";
      } else {
        $button = $content=='' ? '<div class="formcraft-css">' :  "<div class='formcraft-css'><a class='$class' data-toggle='fc_modal' data-target='#modal-$uniq' style='background-color: $button_color; color: $font_color'>$content</a>";
      }
      return "$button<div data-auto='$auto' class='fc-form-modal fc_modal fc_fade animate-$placement' id='modal-$uniq'>
      <div class='fc_modal-dialog fc_modal-dialog-".$id."'>
        <div data-bind='$bind' data-uniq='".$uniq."' class='uniq-".$uniq." formcraft-css form-live align-$align'>
          <button class='fc_close' type='button' class='close' data-dismiss='fc_modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
          ".$addon_content.$saved_data.$custom_css.$logicScript.$html."
        </div>".$powered_by."</div>
      </div>
      </div>";
    } else if ($type=='slide') {
      $button = "<div class='formcraft-css body-append image_button_cover placement-$placement'><a class='fc-sticky-button' data-toggle='fc-sticky' data-target='#sticky-$uniq' style='background-color: $button_color; color: $font_color'>$content</a>";
      return "
      $button
      <div data-auto='$auto' class='fc-sticky fc-sticky-$placement' id='sticky-$uniq'>
        <button class='fc-trigger-close'>×</button>
        <div data-bind='$bind' data-uniq='".$uniq."' class='uniq-".$uniq." formcraft-css form-live align-$align'>
          ".$addon_content.$saved_data.$custom_css.$logicScript.$html."
        </div><span class='powered-by-slide'>".$powered_by."</span></div>
      </div>";
    } else {
      formcraft3_new_view($id);
      $imageHTML = formcraft3_check_form_page()==true && isset($meta['config']['form_logo_url']) && $meta['config']['form_logo_url']!='' ? "<img src='".$meta['config']['form_logo_url']."' class='form-page-logo'/>" : "";
      return "<div data-uniq='".$uniq."' class='uniq-".$uniq." formcraft-css form-live align-$align'>$imageHTML".$addon_content.$saved_data.$custom_css.$logicScript.$html.$powered_by."</div>";
    }
  }
}
