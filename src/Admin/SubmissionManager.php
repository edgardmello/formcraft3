<?php

namespace FormCraft\Admin;

/**
 * SubmissionManager
 *
 * Handles admin submission retrieval, viewing, modification, and deletion.
 * Extracted from legacy.php in v3.9.13.
 */
class SubmissionManager
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handleDeleteEntries() {
    global $fc_meta, $fc_submissions_table, $wpdb;
    if ( !current_user_can($fc_meta['user_can']) ) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce($nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    if ($_GET['entries']) {
      foreach ($_GET['entries'] as $value) {
        if ( !ctype_digit($value) ) {
          die();
        }
        $done = $wpdb->delete( $fc_submissions_table, array('id' => $value) );
        $deleted = $done==true ? $deleted+1 : $deleted;
      }
    } else if ($_GET['form']) {
      if ( !ctype_digit($_GET['form']) ) {
        die();
      }
      $done = $wpdb->delete( $fc_submissions_table, array('form' => $_GET['form']) );
      $deleted = $done==true ? $deleted+1 : $deleted;
    }
    if ($deleted > 0) {
      echo json_encode(array('success'=>esc_html__($deleted.' submission(s) deleted','formcraft') ));
      die();
    } else {
      echo json_encode(array('failed'=>esc_html__('Failed deleting submissions','formcraft') ));
      die();
    }
  }

  public function handleGetEntries() {
    global $fc_meta, $fc_submissions_table, $wpdb;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? $_GET['page']-1 : 0;
    $whichForm = isset($_GET['whichForm']) && ctype_digit($_GET['whichForm']) ? $_GET['whichForm'] : 0;
    $per_page = isset($_GET['perPage']) && ctype_digit($_GET['perPage']) ? $_GET['perPage'] : 10;
    $from = $page*$per_page;
    $to = $per_page;

    $sortWhat = !isset($_GET['sortWhat']) && $_GET['sortWhat']!='created' ? 'created' : $_GET['sortWhat'];
    $sortOrder = !isset($_GET['sortOrder']) && $_GET['sortOrder']!='ASC' && !$_GET['sortOrder']!='DESC' ? 'DESC' : $_GET['sortOrder'];
    $order_query = "ORDER by $sortWhat $sortOrder";

    if ($whichForm==0) {
      $where_clause = '';
    } else {
      $where_clause = $wpdb->prepare("WHERE form = %d ", $whichForm);
    }

    if (isset($_GET['query']) && $_GET['query']!=='') {
      $where_clause = $whichForm==0 ? '' : "AND form = $whichForm ";
      $query = $wpdb->prepare( "SELECT id, form, form_name, created FROM $fc_submissions_table WHERE (content LIKE %s or form_name LIKE %s or id LIKE %s) ".$where_clause."$order_query LIMIT %d, %d;", "%$_GET[query]%", "%$_GET[query]%", "%$_GET[query]%", $from, $to);
      $submissions = $wpdb->get_results( $query, ARRAY_A );
      $query = $wpdb->prepare( "SELECT COUNT(*) FROM $fc_submissions_table WHERE (content LIKE %s or form_name LIKE %s or id LIKE %s) ".$where_clause, "%$_GET[query]%", "%$_GET[query]%", "%$_GET[query]%");
      $total = $wpdb->get_var($query);
    } else {
      $query = $wpdb->prepare("SELECT id, form, form_name, created FROM $fc_submissions_table $where_clause $order_query LIMIT %d, %d", $from, $to);
      $submissions = $wpdb->get_results( $query, ARRAY_A );
      $total = $wpdb->get_var("SELECT COUNT(*) FROM $fc_submissions_table ".$where_clause);
    }

    if ( is_array($submissions) && count($submissions) > 0 ) {
      echo json_encode(array('pages'=>ceil($total/$per_page),'entries'=>$submissions,'total'=>$total));
      die();
    } else {
      echo json_encode(array('pages'=>'0','total'=>'0'));
      die();
    }
  }

  public function handleGetEntryContent() {
    global $fc_meta, $fc_submissions_table, $wpdb;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    if ( !isset($_GET['entryID']) || !ctype_digit($_GET['entryID']) ) {
      die();
    }
    $entryID = intval($_GET['entryID']);
    $query = $wpdb->prepare("SELECT id, form, form_name, content, visitor, created FROM $fc_submissions_table WHERE id = %d", $entryID);
    $submission = $wpdb->get_row( $query, ARRAY_A );
    $submission['created_date'] = get_date_from_gmt(date('Y-m-d H:i:s', $submission['created']), get_option('date_format'));
    $submission['created_time'] = get_date_from_gmt(date('Y-m-d H:i:s', $submission['created']), get_option('time_format'));
    $submission['content'] = json_decode(stripslashes($submission['content']),1);
    $submission['visitor'] = json_decode(stripslashes($submission['visitor']),1);
    foreach ($submission['content'] as $key => $value) {
      $submission['content'][$key]['value'] = formcraft3_stripslashes_deep($submission['content'][$key]['value']);
      if ( !is_array($submission['content'][$key]['value']) ) {
        $submission['content'][$key]['value'] =  html_entity_decode($submission['content'][$key]['value'], ENT_QUOTES, 'utf-8');
      }
    }
    echo json_encode($submission);
    die();
  }

  public function handleUpdateEntryContent() {
    global $fc_meta, $fc_submissions_table, $wpdb;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    if (!isset($_REQUEST['entryID']) || !ctype_digit($_REQUEST['entryID'])) {
      die();
    }
    $entryID = $_REQUEST['entryID'];
    $content = array();
    foreach ($_REQUEST['entryData'] as $key => $value) {
      if (substr($key, 0,5)!='field') {
        continue;
      }
      $content[$key] = $value;
    }
    $query = $wpdb->prepare("SELECT content FROM $fc_submissions_table WHERE id = %d", $entryID);
    $existing = $wpdb->get_var( $query );
    $existing = \FormCraft\Security\Sanitizer::decodeDbJson($existing, true);
    foreach ($existing as $key => $value) {
      if (isset($content[$value['identifier']])) {
        $content[$value['identifier']] = explode(PHP_EOL, $content[$value['identifier']]);
        $existing[$key]['value'] = $content[$value['identifier']];
      }
    }
    $saved = $wpdb->update($fc_submissions_table, array(
      'content' => esc_sql(json_encode($existing)),
      ), array('id' => $entryID));
    if ($saved) {
      echo json_encode(array('success'=>'true'));
      die();
    }
    echo json_encode(array('failed'=>'true'));
    die();
  }
}
