<?php

namespace FormCraft\Admin;

/**
 * FormManager
 *
 * Handles admin form creation, deletion, retrieval, and saving.
 * Extracted from legacy.php in v3.9.13.
 */
class FormManager
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handleNewForm() {
    global $wpdb, $fc_meta, $fc_forms_table;

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
    // Set the permission constants if not already set.
    if ( ! defined( 'FS_CHMOD_DIR' ) ) {
        define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
    }
    if ( ! defined( 'FS_CHMOD_FILE' ) ) {
        define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
    }

    if ( !current_user_can($fc_meta['user_can']) ) {
      die();
    }
    do_action('formcraft_new_form');
    if (empty($_POST['name'])) {
      echo json_encode(array('failed' => esc_html__('Form name cannot be empty.', 'formcraft')));
      die();
    }
    $form_name = stripslashes($_POST['name']);
    switch ($_POST['type']) {
      case 'blank':
      $formData = array();
      $formData['html'] = NULL;
      $formData['builder'] = NULL;
      $formData['addons'] = NULL;
      $formData['meta_builder'] = NULL;
      break;

      case 'template':
      if (empty($_POST['templatePath'])) {
        echo json_encode(array('failed' => esc_html__('Please upload a template file.', 'formcraft')));
        die();
      }
      $bom = pack('H*','EFBBBF');
      $FSD = new WP_Filesystem_Direct(false);
      $importForm = json_decode(preg_replace("/^$bom/", '', $FSD->get_contents(WP_PLUGIN_DIR.$_POST['templatePath'])), 1);
      if (!$importForm) {
        echo json_encode(array('failed' => esc_html__('Invalid form template.', 'formcraft')));
        die();
      }
      $formData = array();
      $formData['html'] = $importForm['html'];
      $formData['builder'] = $importForm['builder'];
      $formData['addons'] = $importForm['addons'];
      $formData['meta_builder'] = $importForm['meta_builder'];
      $formData['old_url'] = $importForm['old_url'];
      break;

      case 'duplicate':
      if (empty($_POST['duplicateFormID']) || !ctype_digit($_POST['duplicateFormID'])) {
        echo json_encode(array('failed' => esc_html__('Select a form to duplicate.', 'formcraft')));
        die();
      }
      $query = $wpdb->prepare("SELECT id, html, builder, addons, meta_builder FROM $fc_forms_table WHERE id = %d", $_POST['duplicateFormID']);
      $existing_form = $wpdb->get_row($query, ARRAY_A);

      $formData = array();
      $formData['html'] = $existing_form['html'];
      $formData['builder'] = $existing_form['builder'];
      $formData['addons'] = json_encode(json_decode(wp_unslash($existing_form['addons'])));
      $formData['meta_builder'] = $existing_form['meta_builder'];
      break;

      case 'import':
      if (empty($_FILES['file'])) {
        echo json_encode(array('failed' => esc_html__('Please upload a template file.', 'formcraft')));
        die();
      }
      $bom = pack('H*','EFBBBF');
      $FSD = new WP_Filesystem_Direct(false);
      $importForm = json_decode(preg_replace("/^$bom/", '', $FSD->get_contents($_FILES['file']['tmp_name'])), 1);
      if (!$importForm) {
        echo json_encode(array('failed' => esc_html__('Invalid form template.', 'formcraft')));
        die();
      }
      if ($importForm['plugin']=='FormCraft Basic') {
        $importForm['html'] = base64_decode($importForm['html']);
        $importForm['builder'] = base64_decode($importForm['builder']);
        $importForm['meta_builder'] = base64_decode($importForm['meta_builder']);
        $importForm['addons'] = NULL;
      }
      $formData = array();
      $formData['html'] = $importForm['html'];
      $formData['builder'] = $importForm['builder'];
      $formData['addons'] = $importForm['addons'];
      $formData['meta_builder'] = $importForm['meta_builder'];
      break;
    }
    $rows_affected = $wpdb->insert($fc_forms_table, array(
      'name' => esc_sql($form_name),
      'created' => strtotime('now'),
      'modified' => strtotime('now'),
      'html' => esc_sql($formData['html']),
      'builder' => esc_sql($formData['builder']),
      'addons' => esc_sql($formData['addons']),
      'old_url' => esc_sql($formData['old_url']),
      'meta_builder' => esc_sql($formData['meta_builder'])
    ));
    if ($rows_affected==false || !is_int($wpdb->insert_id)) {
      echo json_encode(array('failed'=>esc_html__('Could not write to database','formcraft')));
      die();
    }
    do_action('formcraft_after_form_add', array('id'=>$wpdb->insert_id, 'type'=>$_POST['type'], 'name'=>$form_name));
    $response = array('success'=> esc_html__('Form created. Redirecting.', 'formcraft'), 'redirect'=> '&id='.$wpdb->insert_id);
    echo json_encode($response); die();
  }

  public function handleDeleteForm() {
    global $fc_meta, $fc_forms_table, $wpdb;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    $form = $_GET['form'];
    if (!ctype_digit($form)) {
      die();
    }

    $deleted = $wpdb->delete( $fc_forms_table, array('id'=>$form) );

    if ($deleted > 0) {
      do_action('formcraft_after_form_delete', $form);
      echo json_encode(array('success'=> '#'.$form.esc_html__('deleted', 'formcraft'), 'form_id'=> $form));
      die();
    } else {
      echo json_encode(array('failed'=>esc_html__('Failed deleting form','formcraft') ));
      die();
    }
  }

  public function handleGetForms() {
    global $fc_meta, $fc_forms_table, $wpdb;

    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }

    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }

    $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? $_GET['page'] - 1 : 0;
    $form = isset($_GET['form']) && ctype_digit($_GET['form']) ? $_GET['form'] : 0;
    $per_page = isset($_GET['max']) && ctype_digit($_GET['max']) ? $_GET['max'] : 11;
    $from = $page * $per_page;
    $to = $per_page;

    // Whitelist sort values to prevent SQL injection
    $allowed_sort_what = array('name', 'id', 'modified');
    $sortWhat = (isset($_GET['sortWhat']) && in_array($_GET['sortWhat'], $allowed_sort_what)) ? $_GET['sortWhat'] : 'id';

    $allowed_sort_order = array('ASC', 'DESC');
    $sortOrder = (isset($_GET['sortOrder']) && in_array($_GET['sortOrder'], $allowed_sort_order)) ? $_GET['sortOrder'] : 'DESC';

    $searchQuery = !isset($_GET['query']) || trim($_GET['query']) === '' ? false : esc_sql($_GET['query']);

    $order_query = "ORDER by $sortWhat $sortOrder";

    if ( $searchQuery ) {
      $total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $fc_forms_table WHERE (name LIKE %s or id LIKE %s);", '%' .$searchQuery . '%', '%' . $searchQuery . '%') );
      $query = $wpdb->prepare( "SELECT id, name, modified FROM $fc_forms_table WHERE (name LIKE %s or id LIKE %s) $order_query LIMIT %d, %d", "%$searchQuery%", "%$searchQuery%", $from, $to);
      $forms = $wpdb->get_results( $query, ARRAY_A );
    } else {
      $total = $wpdb->get_var( "SELECT COUNT(*) FROM $fc_forms_table" );
      $query = $wpdb->prepare("SELECT id, name, modified FROM $fc_forms_table $order_query LIMIT %d, %d", $from, $to);
      $forms = $wpdb->get_results( $query, ARRAY_A );
    }

    if ( is_array($forms) && count($forms) > 0 ) {
      foreach ($forms as $key => $value) {
        $forms[$key]['name'] = $forms[$key]['name']=='' ? '(No Name)' : wp_unslash($forms[$key]['name']);
      }
      echo json_encode(array('pages'=>ceil($total/$per_page),'forms'=>$forms,'total'=>$total));
      die();
    } else {
      echo json_encode(array('pages'=>'0','total'=>'0'));
      die();
    }
  }

  public function handleLoadFormData() {
    global $wpdb, $fc_forms_table, $fc_meta;
    if ( !current_user_can($fc_meta['user_can']) ) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce($nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    $form_id = $_GET['id'];
    if (!ctype_digit($form_id)) {
      echo json_encode(array('failed'=>esc_html__('Invalid Form ID')));
      die();
    }
    if ($_GET['type']=='builder') {
      $query = $wpdb->prepare("SELECT * FROM $fc_forms_table WHERE id = %d", $form_id);
      $formData = $wpdb->get_row($query, ARRAY_A);

      $formData['builder'] = $formData['builder']==null ? '' : $formData['builder'];
      $formData['meta_builder'] = $formData['meta_builder']==null ? false : $formData['meta_builder'];
      $formData['addons'] = $formData['addons'] == null ? false : $formData['addons'];
      $formData['old_url'] = $formData['old_url']==null ? false : $formData['old_url'];
      if ($formData['meta_builder'] != false) {
        $formData['meta_builder'] = \FormCraft\Security\Sanitizer::decodeDbJson($formData['meta_builder'], true);
        $formData['meta_builder'] = $formData['meta_builder']['config'];
        $formData['meta_builder'] = json_encode($formData['meta_builder']);
      }
      if ($formData['addons'] != false) {
        $formData['addons'] = wp_unslash($formData['addons']);
      }

      $formData['new_url'] = site_url();

      echo json_encode($formData);
    }
    die();
  }

  public function handleFormSave() {
    global $wpdb, $fc_meta, $fc_forms_table;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce($nonce, 'formcraft3_wpnonce')) {
      echo json_encode(array('failed'=>esc_html__('Failed saving owing to expired token. Please refresh and try again.')));
      die();
    }
    $form_id = $_POST['id'];
    if (!ctype_digit($form_id)) {
      echo json_encode(array('failed'=>esc_html__('Invalid Form ID')));
      die();
    }
    $meta_builder = substr($_POST['meta_builder'], 0, 10) === 'rawdeflate' ? json_decode(gzinflate(base64_decode(rawurldecode(substr($_POST['meta_builder'], 11))),0), 1) : \FormCraft\Security\Sanitizer::decodeDbJson($_POST['meta_builder'], true);
    $name = $meta_builder['config']['form_name'];
    $builder = $_POST['builder'];
    $addons = esc_sql(stripslashes($_POST['addons']));

    $meta_builder = esc_sql(json_encode($meta_builder));

    $html = esc_sql(stripslashes($_POST['html']));
    if ( $builder != esc_sql($builder) ) {
      echo json_encode(array('failed'=>esc_html__('Lost in Translation')));
      die();
    }
    if ( $wpdb->update($fc_forms_table, array(
      'meta_builder' => $meta_builder,
      'addons' => $addons,
      'builder' => $builder,
      'html' => $html,
      'modified' => strtotime('now'),
      'name' => $name
      ), array('ID'=>$form_id)) === FALSE) {
      echo json_encode(array('failed' => esc_html__('Could not write to database')));
      die();
    } else {
      echo json_encode(array('success' => esc_html__('Form Saved')));
      die();
    }
    die();
  }
}
