<?php

namespace FormCraft\Frontend;

/**
 * FormSubmission
 *
 * Handles form submission and processing API.
 * Extracted from legacy.php in v3.9.13.
 */
class FormSubmission
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function handleFormSubmit() {

    global $fc_meta, $fc_forms_table, $fc_submissions_table, $fc_files_table, $wpdb, $fc_final_response;

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');    

    if (!isset($_POST['id']) || !ctype_digit($_POST['id'])) {
      echo json_encode(array('failed'=> esc_html__('Invalid Form ID','formcraft') ));
      die();
    }
    if (isset($_POST['website']) && $_POST['website']!='') {
      echo json_encode(array('failed'=> esc_html__('SPAM detected','formcraft') ));
      die();
    }
    if ($fc_meta['preview_mode'] == true) {
      echo json_encode(array('failed'=> esc_html__('Cannot submit forms in preview mode', 'formcraft')));
      die();
    }    
    $id = $_POST['id'];
    $meta = $wpdb->get_var( $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $id) );
    $meta = \FormCraft\Security\Sanitizer::decodeDbJson($meta, true);

    $fieldLabels = \FormCraft\Security\Sanitizer::decodeDbJson($_POST['fieldLabels'], true);

    /* Allow Editing of Meta */
    $meta = apply_filters('formcraft_filter_entry_meta', $meta);

    $fc_final_response = array();
    $fc_final_response['errors'] = array();

    $_POST = apply_filters('formcraft_filter_raw', $_POST, $meta);

    $integrations = array();
    $integrations['not_triggered'] = array();
    $_POST['trigger_integration'] = isset($_POST['triggerIntegration']) ? $_POST['triggerIntegration'] : $_POST['trigger_integration'];
    $_POST['trigger_integration'] = isset($_POST['trigger_integration']) ? $_POST['trigger_integration'] : '';
    $integrations['triggered'] = \FormCraft\Security\Sanitizer::decodeDbJson(urldecode($_POST['trigger_integration']), true);
    $integrations['triggered'] = !empty($integrations['triggered']) && count($integrations['triggered']) > 0 ? array_unique($integrations['triggered']) : $integrations['triggered'];
    if (isset($meta['config']['Logic'])) {
      foreach ($meta['config']['Logic'] as $key => $logicRow) {
        if (isset($logicRow[1]) && is_array($logicRow[1]) && count($logicRow[1])>0) {
          foreach ($logicRow[1] as $key2 => $value) {
            if (isset($value[0]) && isset($value[3]) && $value[0]=='trigger_integration' && !in_array($value[3], $integrations['triggered'])) {
              $integrations['not_triggered'][] = $value[3];
            }
          }
        }
      }
    }
    $messages = $meta['config']['Messages'];
    $hidden_fields = isset($_POST['hidden']) ? explode(',', preg_replace('/\s+/', '', $_POST['hidden'])) : array();
    foreach ($meta['fields'] as $key => $field) {

      if ( isset($_POST['type']) && ctype_digit($_POST['type']) && $field['page']!=$_POST['type'] ){continue;}

      $value = isset($_POST[$field['identifier']]) ? $_POST[$field['identifier']] : '';

      if ( !in_array($field['identifier'], $hidden_fields) ) {
        /* Check if Required Field */
        if ($field['type']=='matrix' && isset($field['elementDefaults']['required']) && $field['elementDefaults']['required']==true) {
          if ( !empty($field['elementDefaults']['matrixRowsOutput']) ) {
            $field['elementDefaults']['matrix_rows_output'] = $field['elementDefaults']['matrixRowsOutput'];
          }
          foreach ($field['elementDefaults']['matrix_rows_output'] as $matrix_key => $matrix_value) {
            if ( !isset($_POST[$field['identifier'].'_'.$matrix_key]) ) {
              $fc_final_response['errors'][$field['identifier']] = $messages['is_required'];
              break;
            }
          }
        }
        else if ( isset($field['elementDefaults']['required']) && is_array($value) && $field['elementDefaults']['required']==true && (count($value)==0 || $value[0]=='' ) ) {
          $fc_final_response['errors'][$field['identifier']] = $messages['is_required'];
        }
        else if ( isset($field['elementDefaults']['required']) && $field['elementDefaults']['required']==true && !is_array($value) && trim($value)=='') {
          $fc_final_response['errors'][$field['identifier']] = $messages['is_required'];
        }
        else if (isset($field['elementDefaults']['required']) && $field['elementDefaults']['required']==true && !isset($_POST[$field['identifier']])) {
          $fc_final_response['errors'][$field['identifier']] = $messages['is_required'];
        }

        /* Field Type Validation */
        switch ($field['type']) {
          case 'email':
          if (trim($value)!='' && is_email_fc(stripslashes($value)) == false) {
            $fc_final_response['errors'][$field['identifier']] = $messages['allow_email'];
          }
          break;

          case 'fileupload':
          if (isset($field['elementDefaults']['min_files']) && ctype_digit($field['elementDefaults']['min_files']) && $field['elementDefaults']['min_files']!=0) {
            if (!isset($_POST[$field['identifier']])) {
              $fc_final_response['errors'][$field['identifier']] = str_ireplace('[x]', $field['elementDefaults']['min_files'], $messages['min_files']);
            } else if ( count($_POST[$field['identifier']]) < $field['elementDefaults']['min_files'] ) {
              $fc_final_response['errors'][$field['identifier']] = str_ireplace('[x]', $field['elementDefaults']['min_files'], $messages['min_files']);
            }
          }
          break;

          default:
          break;
        }

        /* Explicit Validation */
        if ( isset($field['elementDefaults']) && isset($field['elementDefaults']['Validation']) ) {
          $spaces = isset($field['elementDefaults']['Validation']['spaces']) && $field['elementDefaults']['Validation']['spaces']==true ? true : false;
          $value_to_check = $spaces==true ? str_replace(' ', '', $value) : $value;
          $value = is_array($value) ? $value[0] : $value;
          $value = wp_unslash($value);
          foreach ($field['elementDefaults']['Validation'] as $type => $validation) {
            if (empty($value)){
              continue;
            }
            switch ($type) {
              case 'allowed':
              $value_to_check = is_array($value_to_check) ? $value_to_check[0] : $value_to_check;
              if ( $validation=='alphabets' && !ctype_alpha($value_to_check) )
              {
                $fc_final_response['errors'][$field['identifier']] = $messages['allow_alphabets'];
              }
              else if ( $validation=='numbers' && !ctype_digit($value_to_check) )
              {
                $fc_final_response['errors'][$field['identifier']] = $messages['allow_numbers'];
              }
              else if ( $validation=='alphanumeric' && !ctype_alnum($value_to_check) )
              {
                $fc_final_response['errors'][$field['identifier']] = $messages['allow_alphanumeric'];
              }
              else if ( $validation=='url' && !filter_var( $value, FILTER_VALIDATE_URL ) )
              {
                $fc_final_response['errors'][$field['identifier']] = $messages['allow_url'];
              }
              break;

              case 'minChar':
              if ( !ctype_digit($validation) ) break;
              if ( (mb_strlen($value)-substr_count( $value, "\n    " )) < $validation )
              {
                $fc_final_response['errors'][$field['identifier']] = str_ireplace('[x]', $validation, $messages['min_char']);
              }
              break;

              case 'maxChar':
              if ( !ctype_digit($validation) ) break;
              if ( (mb_strlen($value)-substr_count($value, "\n    " )) > $validation )
              {
                $fc_final_response['errors'][$field['identifier']] = str_ireplace('[x]', $validation, $messages['max_char']);
              }
              break;

              default:
              break;
            }
          }
        }
      }

    } /* End of Fields Loop */


    /* If validation failed, show errors */
    if ( !empty($fc_final_response['errors']) && count($fc_final_response['errors'])>0 ) {
      if ( !isset($fc_final_response['failed']) ) {
        $fc_final_response['failed'] = isset($meta['config']['messages']['form_errors']) ? $meta['config']['messages']['form_errors'] : $messages['failed'];
      }
      echo json_encode($fc_final_response);
      die();
    }
    if ( !isset($_POST['type']) || $_POST['type']!='all' ) {
      echo json_encode(array('validated'=>$_POST['type']));
      die();
    }
    /* ELSE All is Well with the Submission */

    /* Clean the User Input */
    foreach ($meta['fields'] as $key => $field) {
      if ( isset($_POST[$field['identifier']]) ) {
        if (is_array($_POST[$field['identifier']]))
        {
          foreach($_POST[$field['identifier']] as $key => $value) {
            $_POST[$field['identifier']][$key] = htmlentities(stripslashes($value), ENT_QUOTES, "UTF-8");
          }
        }
        else
        {
          $_POST[$field['identifier']] = stripslashes($_POST[$field['identifier']]);
          $_POST[$field['identifier']] = htmlentities($_POST[$field['identifier']], ENT_QUOTES, "UTF-8");
        }
      }
    }

    /* Parse and Organize Input */
    $content = array();
    $all_files = array();
    $autoresponder_email = array();
    foreach ($meta['fields'] as $key => $field) {
      if ( $field['type']=='password' ) { continue; }
      if ( $field['type']=='submit' ) { continue; }
      if (!empty($meta['config']['dont_submit_hidden'])) {
        if (in_array($field['identifier'], $hidden_fields)) {
          continue;
        }
      }
      $new_row = array();
      if ($field['type']=='fileupload') {
        if ( !isset($_POST[$field['identifier']]) ) { continue; }
        $files_name = array();
        $files_url = array();
        foreach($_POST[$field['identifier']] as $key => $value) {
          $query = $wpdb->prepare("SELECT id, name, file_url, file_path, uniq_key FROM $fc_files_table WHERE uniq_key = %s", $value);
          $file_row = $wpdb->get_row($query, ARRAY_A);
          if (!$file_row) {
            continue;
          }
          $files_name[] =  $file_row['name'];
          $files_url[] =  $file_row['file_url'];
          $all_files[] = $file_row;
        }
        $label = isset($field['elementDefaults']['main_label']) ? $field['elementDefaults']['main_label'] : '';
        $new_row = array('label'=>$label,'value'=>$files_name,'url'=>$files_url,'identifier'=>$field['identifier'],'type'=>$field['type'],'page'=>$field['page'],'page_name'=>$meta['config']['page_names'][$field['page']-1]);
      }
      else if ($field['type']=='matrix')
      {
        $value = array();
        $field['elementDefaults']['matrix_rows_output'] = isset($field['elementDefaults']['matrixRowsOutput']) ? $field['elementDefaults']['matrixRowsOutput'] : $field['elementDefaults']['matrix_rows_output'];
        foreach ($field['elementDefaults']['matrix_rows_output'] as $matrix_key => $matrix_value) {
          if (isset($_POST[$field['identifier'].'_'.$matrix_key]))
          {
            $value[] = array('question'=>$matrix_value['value'], 'value'=>$_POST[$field['identifier'].'_'.$matrix_key]);
          }
        }
        $label = isset($field['elementDefaults']['main_label']) ? $field['elementDefaults']['main_label'] : '';
        $new_row = array('label'=>$label,'value'=>$value,'identifier'=>$field['identifier'],'type'=>$field['type'],'page'=>$field['page'],'page_name'=>$meta['config']['page_names'][$field['page']-1]);
      }
      else
      {
        unset($value);
        if ( isset($_POST[$field['identifier']]) ) { $value = $_POST[$field['identifier']]; }

        $value = isset($value) ? $value : '';
        $label = isset($field['elementDefaults']['main_label']) ? $field['elementDefaults']['main_label'] : '';
        if ( $field['type']=='email' && isset($field['elementDefaults']['autoresponder']) && $field['elementDefaults']['autoresponder']==true)
        {
          $autoresponder_email[] = $value;
        }
        if ( is_array($value) && count($value)==1 )
        {
          $value = $value[0];
        }
        if ( is_array($value) )
        {
          foreach ($value as $k => $v) {
            $value[$k] = html_entity_decode($v, ENT_QUOTES, 'utf-8');
          }
        }
        $new_row = array('label'=>$label,'value'=>$value,'identifier'=>$field['identifier'],'type'=>$field['type'],'page'=>$field['page'],'page_name'=>$meta['config']['page_names'][$field['page']-1]);
      }

      if ($field['type']=='dropdown' || $field['type']=='checkbox')
      {
        $new_row['options'] = $field['elementDefaults']['optionsListShow'];
      }

      if ( isset($field['isPayment']) ) {
        $field['is_payment'] = $field['isPayment'];
      }
      if ( isset($field['is_payment']) && $field['is_payment']==true )
      {
        $form_payment = 1;
        $new_row['payment'] = $value;
        $new_row['currency'] = isset($field['elementDefaults']['currency']) ? $field['elementDefaults']['currency'] : '';
      }
      if ( isset($field['elementDefaults']['replyTo']) && $field['elementDefaults']['replyTo']==true )
      {
        $replyTo = $value;
      }
      $new_row['width'] = isset($field['elementDefaults']['field_width']) ? $field['elementDefaults']['field_width'] : '100%';
      $field['elementDefaults']['main_label'] = isset($field['elementDefaults']['main_label']) ? $field['elementDefaults']['main_label'] : '';
      $new_row['altLabel'] = isset($field['elementDefaults']['altLabel']) ? $field['elementDefaults']['altLabel'] : $field['elementDefaults']['main_label'];
      $content[] = $new_row;
      $form_nos_pages = $field['page'];
    }
    $form_payment = isset($form_payment) ? $form_payment : 0;

    /* Allow Editing Content */
    $content = apply_filters('formcraft_filter_entry_content', $content);


    $visitor = array();
    $form_name = $wpdb->get_var( $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $id) );
    $template = array();
    $template['Form ID'] = $id;
    if ( !empty($meta['config']['collect_ip']) ) {
        $visitor['IP'] = $template['IP'] = empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    $template['Form ID'] = $id;
    $template['Form Name'] = $form_name;
    $template['URL'] = $visitor['URL'] = isset($_POST['location']) ? $_POST['location'] : esc_html__('Unknown','formcraft');
    $template['Date'] = current_time(get_option('date_format'));
    $template['Time'] = current_time(get_option('time_format'));
    $temp = array();
    $temp2 = array();
    $thisWidth = 0;
    $signatureImages = array();

    foreach ($content as $key => $value) {

	    if (empty($meta['config']['dont_hide_empty']) && $value['value']=='') {
	      continue;
	    } else if ($value['value']=='') {
	    	$content[$key]['value'] = '-';
	    	$value['value'] = '-';
	    }

      if ($value['type']=='fileupload') {
        foreach ($value['value'] as $key2 => $file) {
          $temp[] = "<a href='".$value['url'][$key2]."'>".$value['value'][$key2]."</a>";
        }
        $value['value'] = implode("\n    ", $temp);
        unset($temp);
      } else if ($value['type']=='dropdown' || $value['type']=='checkbox') {
        $template[$value['label'].'.value'] = is_array($value['value']) ? implode(", ", $value['value']) : $value['value'];
        $fieldLabels[$value['identifier'].'.label'] = empty($fieldLabels[$value['identifier'].'.label']) ? [] : $fieldLabels[$value['identifier'].'.label'];
        $value['value'] = implode("\n    ", $fieldLabels[$value['identifier'].'.label']);
        $template[$value['label'].'.label'] = $value['value'];
        $template[$value['identifier'].'.label'] = $value['value'];
        $template[$value['identifier'].'.value'] = $template[$value['label'].'.value'];
        if ( $value['value']=='' && isset($template[$value['label'].'.value']) ) {
          $value['value'] = html_entity_decode($template[$value['label'].'.value'], ENT_QUOTES, 'utf-8');
        }
      } else if ($value['type']=='matrix') {
        $newValue = array();
        foreach ($value['value'] as $key2 => $value2) {
          $newValue[] = $value2['question'].': '.$value2['value'];
        }
        $value['value'] = implode("\n    ", $newValue);
      } else {
        if ( is_array($value['value']) && count($value['value'])==1 )
        {
          $value['value'] = $value['value'][0] ;
        }
        else if ( is_array( $value['value'] ) )
        {
          $value['value'] = implode("\n    ", $value['value']) ;
        }
        else
        {
          $value['value'] = $value['value'] ;
        }
      }
      if ( $value['value'] == '' && isset($template[$value['label'].'.value']) ) {
        $template[$value['label']] = $template[$value['label'].'.value'];
        $template[$value['identifier']] = $template[$value['label'].'.value'];
      } else {
        $template[$value['label']] = $value['value'];
        $template[$value['identifier']] = $value['value'];
      }


      $meta['page_count'] = isset($meta['page_count']) ? $meta['page_count'] : 1;
      if ( (empty($last_page) || $value['page_name']!=$last_page) && $meta['page_count']>1 ) {
        $last_page=$value['page_name'];
        if ( isset($meta['config']['notifications']['form_layout']) && $meta['config']['notifications']['form_layout']==true )
        {
          $temp2[] = "<div style='font-weight: bold;margin-top:15px;margin-bottom:10px;float:left;width:600px;font-size:110%'>".$value['page_name']."</div>";

        } else {
          $temp2[] = "<div style='font-weight: bold;margin-top:15px;margin-bottom:10px;width:600px;font-size:110%'>".$value['page_name']."</div>";
        }
      }
      $thisWidth = isset($value['width']) && strpos($value['width'], '%')!=0 ? $thisWidth + ((intval($value['width'])/100)*600) : 600;
      $tempW = isset($value['width']) && strpos($value['width'], '%')!=0 ? ((intval($value['width'])/100)*600).'px' : '600px';
      $value['value'] = str_replace("\n    \n    ", "<br><br>", $value['value']);

      if ( isset($meta['config']['notifications']['form_layout']) && $meta['config']['notifications']['form_layout']==true )
      {
        if ( $value['type']=='heading' )
        {
          $temp2[] = "<div style='font-size:120%;float:left;vertical-align:top;width:$tempW;margin-bottom:10px'><div style='font-weight: bold'>".$value['value']."</div></div>";
        } else if ( $value['type']=='signature' ) {
          $data = $value['value'];
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          // Set the permission constants if not already set.
          if ( ! defined( 'FS_CHMOD_DIR' ) ) {
              define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
          }
          if ( ! defined( 'FS_CHMOD_FILE' ) ) {
              define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
          }            
          $FSD = new WP_Filesystem_Direct(false);
          $FSD->put_contents(sys_get_temp_dir().'/'.$value['label'].'.png', $data);
          $signatureImages['path'] = array('name' => $value['label'].'png', 'path' => sys_get_temp_dir().'/'.$value['label'].'.png', 'id' => $value['identifier']);
          if ($meta['config']['notifications']['_method']=='smtp') {
            $temp2[] = "<div style='float:left;vertical-align:top;width:$tempW;margin-bottom:10px'><div style='font-weight: bold'>".$value['label']."</div><div><img src='cid:".$value['identifier']."'/></div></div>";
          } else {
            $temp2[] = "<div style='float:left;vertical-align:top;width:$tempW;margin-bottom:10px'><div style='font-weight: bold'>".$value['label']."</div><div><img src='".$value['value']."'/></div></div>";
          }
        }
        else
        {
          $temp2[] = "<div style='float:left;vertical-align:top;width:$tempW;margin-bottom:10px'><div style='font-weight: bold'>".$value['label']."</div><div>".$value['value']."</div></div>";
        }
      }
      else
      {
        if ( $value['type']=='heading' )
        {
          $temp2[] = "<tr><td colspan='2' style='font-size: 120%; font-weight: bold'>".$value['value']."</td></tr>";
        }
        else if ( $value['type']=='signature' )
        {
          $data = $value['value'];
          list($type, $data) = explode(';', $data);
          list(, $data)      = explode(',', $data);
          $data = base64_decode($data);
          if ( ! defined( 'FS_CHMOD_DIR' ) ) {
              define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
          }
          if ( ! defined( 'FS_CHMOD_FILE' ) ) {
              define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
          }              
          $FSD = new WP_Filesystem_Direct(false);
          $FSD->put_contents(sys_get_temp_dir().'/'.$value['label'].'.png', $data);
          $signatureImages['path'] = array('name' => $value['label'].'png', 'path' => sys_get_temp_dir().'/'.$value['label'].'.png', 'id' => $value['identifier']);
          if ($meta['config']['notifications']['_method']=='smtp') {
            $temp2[] = "<tr><td cellspacing='0' cellpadding='0' style='width: 200px; font-weight: bold; display: inline-block'>".$value['label']."</td> <td><img src='cid:".$value['identifier']."'/></td></tr>";
          } else {
            $temp2[] = "<tr><td cellspacing='0' cellpadding='0' style='width: 200px; font-weight: bold; display: inline-block'>".$value['label']."</td> <td><img src='".$value['value']."'/></td></tr>";
          }
        } else {
          $value['value'] = $value['type']=='checkbox' || $value['type']=='fileupload' ? str_ireplace("<br>", ", ", $value['value']) : $value['value'];
          $temp2[] = "<tr><td cellspacing='0' cellpadding='0' style='width: 200px; font-weight: bold; display: inline-block'>".$value['label']."</td>	<td>".$value['value']."</td></tr>";
        }
      }
      if ( isset($meta['config']['notifications']['form_layout']) && $meta['config']['notifications']['form_layout']==true && $thisWidth >= 600  )
      {
        $temp2[] = "</div><div style='width: 600px'>";
        $thisWidth = 0;
      }
    }

    if ( !isset($meta['config']['notifications']['form_layout']) || $meta['config']['notifications']['form_layout']==false )
    {
      array_unshift($temp2, '<table><tbody>');
      $temp2[] = '</tbody></table>';
    }

    $form_content = implode('', $temp2);
    $template['Form Content'] = "<div style='width: 600px'>".$form_content."<div style='display:block;clear:both'></div></div>";

    /* If validation failed, show errors */
    if (count($fc_final_response['errors'])>0) {
      if (!isset($fc_final_response['failed'])) {
        $fc_final_response['failed'] = isset($meta['config']['messages']['form_errors']) ? $meta['config']['messages']['form_errors'] : $messages['failed'];
      }
      echo json_encode($fc_final_response);
      die();
    }

    /* Check With the Add-Ons Before Submitting */
    do_action('formcraft_before_save', $template, $meta, $content, $integrations);    

    if ( !empty($fc_final_response['addContent']) && count($fc_final_response['addContent']) > 0 ) {
      $content[] = $fc_final_response['addContent'];
      unset($fc_final_response['addContent']);
    }

    if ( !empty($fc_final_response['attachments-notify']) && count($fc_final_response['attachments-notify']) > 0 ) {
      $notifyAttachments = $fc_final_response['attachments-notify'];
      unset($fc_final_response['attachments-notify']);
    }
    if ( !empty($fc_final_response['attachments-autoresponder']) && count($fc_final_response['attachments-autoresponder']) > 0 ) {
      $autoresponderAttachments = $fc_final_response['attachments-autoresponder'];
      unset($fc_final_response['attachments-autoresponder']);
    }

    /* If validation failed, show errors */
    if (count($fc_final_response['errors'])>0) {
      if (!isset($fc_final_response['failed'])) {
        $fc_final_response['failed'] = isset($meta['config']['messages']['form_errors']) ? $meta['config']['messages']['form_errors'] : $messages['failed'];
      }
      echo json_encode($fc_final_response);
      die();
    }

    $rows_affected = $wpdb->insert( $fc_submissions_table, array(
      'form' => $id,
      'form_name' => $form_name,
      'content' => esc_sql(json_encode($content)),
      'visitor' => esc_sql(json_encode($visitor)),
      'created' => strtotime('now')
      ) );
    $template['Entry ID'] = $wpdb->insert_id;

    /* Allow Editing Template */
    $template = apply_filters('formcraft_filter_email_template', $template);

    if ( isset($meta['config']['disable_store']) && $meta['config']['disable_store']) {
      $delete_before = intval($meta['config']['disable_store_days']);
      $delete_before = strtotime('-'.$delete_before.'days');
      $query = $wpdb->prepare("DELETE FROM $fc_submissions_table WHERE form = $id AND created <= %s", $delete_before);
      $wpdb->query($query);

      $query = $wpdb->prepare("SELECT file_path FROM $fc_files_table WHERE form = $id AND created < %s", $delete_before);
      $file_row = $wpdb->get_results($query, ARRAY_A);
      if ($file_row && count($file_row) > 0) {
        foreach ($file_row as $key => $value) {
          unlink($value['file_path']);
          $delete = $wpdb->delete( $fc_files_table, array('file_path'=>$value['file_path']) );
        }
      }
    }

    /* Written to Database, so it works */

    /* Mark Uploaded Files as Permanent */
    if ($all_files && count($all_files) > 0) {
      foreach ($all_files as $key => $value) {
        $sql = $wpdb->prepare("UPDATE `$fc_files_table` SET permanent=2 WHERE uniq_key='%s'", $value['uniq_key']);
        $wpdb->query($sql);
      }
    }

    /* Deleted Old Files Which Were Never Marked Permanent */
    $delete_before = date("Y-m-d H:i:s", strtotime("-1 day"));
    $query = $wpdb->prepare("SELECT file_path FROM $fc_files_table WHERE permanent=1 AND created < %s", $delete_before);
    $file_row = $wpdb->get_results($query, ARRAY_A);
    if ($file_row && count($file_row) > 0) {
      foreach ($file_row as $key => $value) {
        unlink($value['file_path']);
        $delete = $wpdb->delete( $fc_files_table, array('file_path'=>$value['file_path']) );
      }
    }

    if ($rows_affected) {
      if ( !strpos($_SERVER["HTTP_REFERER"], '?preview=true') ) {
        formcraft3_new_submission($id, $form_payment);
        unset($_COOKIE["fc_sp_$id"]);
      }
      if ( isset($meta['config']['messages']['success']) )
      {
        $fc_final_response['success'] = $meta['config']['messages']['success'];
      } else {
        $fc_final_response['success'] = $messages['success'];
        $fc_final_response['submission_id'] = $template['Entry ID'];
      }
    }
    else
    {
      $fc_final_response['failed'] = esc_html__('Failed to Write','formcraft');
      echo json_encode($fc_final_response); die();
    }

    if (isset($autoresponder_email) && is_array($autoresponder_email) && count($autoresponder_email)>0) {
      $email_subject = isset($meta['config']['autoresponder']['email_subject']) ? $meta['config']['autoresponder']['email_subject'] : esc_html__('New Form Submission','formcraft');
      $email_subject = formcraft3_template($template, $email_subject);
      $email_subject = formcraft3_template_content($content, $email_subject);  
      $email_subject = formcraft3_math($content, $email_subject);

      $email_body = isset($meta['config']['autoresponder']['email_body']) ? $meta['config']['autoresponder']['email_body'] : esc_html__('[Form Content]','formcraft');
      $email_body = formcraft3_template($template, $email_body);       
      $email_body = formcraft3_template_content($content, $email_body);
      $email_body = formcraft3_math($content, $email_body);
      $email_body = formcraft3_email_template($email_body);
      $email_body = !empty($meta['config']['notifications']['removeEmptyTags']) ? preg_replace('/\[[^\]]*\]/', '', $email_body) : $email_body;

      $from_name = isset($meta['config']['autoresponder']['email_sender_name']) ? $meta['config']['autoresponder']['email_sender_name'] : 'FormCraft';
      $from_name = formcraft3_template($template, $from_name);

      $from_email = isset($meta['config']['autoresponder']['email_sender_email']) ? $meta['config']['autoresponder']['email_sender_email'] : get_bloginfo('admin_email');
      $from_email = formcraft3_template($template, $from_email);
      $from_email = formcraft3_template_content($content, $from_email);

      if ( !is_email_fc($from_email) ){
        $from_email = get_bloginfo('admin_email');
      }

      $sent = 0;
      $failed = 0;
      if (version_compare(get_bloginfo('version'), '5.5') >= 0) {
          require_once(ABSPATH . 'wp-includes/PHPMailer/PHPMailer.php');
          require_once(ABSPATH . 'wp-includes/PHPMailer/SMTP.php');
          require_once(ABSPATH . 'wp-includes/PHPMailer/Exception.php');          
          $mail = new PHPMailer\PHPMailer\PHPMailer();
      } else {
          require_once(ABSPATH . 'wp-includes/class-phpmailer.php');
          $mail = new PHPMailer;
      }      
      if (!class_exists('Html2Text\Html2Text') && !function_exists('fix_newlines')) {
        require_once 'lib/html2text/html2text.php';
      }

      foreach ($autoresponder_email as $email) {
        if (!is_email_fc($email)){
          continue;
        }

        if (isset($meta['config']['notifications']['_method']) && $meta['config']['notifications']['_method']=='smtp') {
          $mail->isSMTP();
          $mail->Host = $meta['config']['notifications']['smtp_sender_host'];
          if (!empty($meta['config']['notifications']['smtp_sender_port'])) { $mail->SMTPAuth = true; }
          if (!empty($meta['config']['notifications']['smtp_sender_username'])) { $mail->Username = $meta['config']['notifications']['smtp_sender_username']; }
          if (!empty($meta['config']['notifications']['smtp_sender_password'])) { $mail->Password = $meta['config']['notifications']['smtp_sender_password']; }
          if (!empty($meta['config']['notifications']['smtp_sender_security'])) { $mail->SMTPSecure = $meta['config']['notifications']['smtp_sender_security']; }
          if (!empty($meta['config']['notifications']['smtp_sender_port'])) { $mail->Port = $meta['config']['notifications']['smtp_sender_port']; }

          $mail->From = $from_email;
          $mail->FromName = $from_name;
          $mail->addAddress($email);
          $mail->isHTML(true);

          $mail->Subject = $email_subject;
          $mail->Body    = $email_body;
          if (function_exists('mb_detect_encoding')) {
            $mail->AltBody = convert_html_to_text($email_body, true);  
          }
          $mail->CharSet = "UTF-8";

          if ( !empty($meta['config']['autoresponderFiles']) ) {
            $FSD = new WP_Filesystem_Direct(false);
            foreach ($meta['config']['autoresponderFiles'] as $key => $file) {
              if ( !filter_var($file['url'], FILTER_VALIDATE_URL) ) continue;
              $file['name'] = empty($file['name']) ? '' : $file['name'];
              $mail->addStringAttachment($FSD->get_contents($file['url']), $file['name']);
            }
          }

          if ( !empty($autoresponderAttachments) ) {
            foreach ($autoresponderAttachments as $key => $file) {
              $mail->addAttachment($file['path'], $file['name']);
            }
          }

          if ( !empty($signatureImages) ) {
            foreach ($signatureImages as $key => $file) {
              $mail->AddEmbeddedImage($file['path'], $file['id'], $file['name']);
            } 
          }                   

          if(!$mail->send()) {
            $failed++;
            $failed_msg = $mail->ErrorInfo;
          } else {
            $sent++;
          }
        } else {
          $subject = $email_subject;
          $message = $email_body;
          $headers = array();
          $attachments = array();
          $deleteAttachments = array();
          $headers[] = 'From: '."=?UTF-8?B?".base64_encode($from_name)."?=".' <'.$from_email.'>';
          $headers[] = 'Content-Type: text/html; charset=UTF-8';
          if (!empty($autoresponderAttachments)) {
            foreach ($autoresponderAttachments as $key => $file) {
              $attachments[] = $file['path'];
            }
          }
          if (!empty($meta['config']['autoresponderFiles'])) {
            foreach ($meta['config']['autoresponderFiles'] as $key => $file) {
              if ( !filter_var($file['url'], FILTER_VALIDATE_URL) ) continue;
              $file['name'] = empty($file['name']) ? '' : $file['name'];
              $FSD = new WP_Filesystem_Direct(false);
              $FSD->put_contents(sys_get_temp_dir().'/'.$file['name'], $FSD->get_contents($file['url']));
              $attachments[] = sys_get_temp_dir().'/'.$file['name'];
              $deleteAttachments[] = sys_get_temp_dir().'/'.$file['name'];
            }
          }
          $message = wordwrap($message, 70);
          $email_sent = wp_mail( $email, $subject, $message, $headers, $attachments );
          if(!$email_sent) {
            $failed++;
            $failed_msg = "Email setup error";
          } else {
            $sent++;
          }
          foreach ($deleteAttachments as $key => $value) {
            unlink($value);
          }
        }
      }
      if ( $failed>0 ) {
        $fc_final_response['debug']['failed'][] = esc_html__('Autoresponder Not Sent: ','formcraft').$failed_msg;
      } else {
        $fc_final_response['debug']['success'][] = esc_html__($sent.' autoresponder email(s) sent','formcraft');
      }
    }
    if ( isset($_POST['emails']) )
    {
      $_POST['emails'] = formcraft3_template_content($content, $_POST['emails']);    
      $meta['config']['notifications']['recipients'] = isset($meta['config']['notifications']['recipients']) ? $meta['config']['notifications']['recipients'].', '.$_POST['emails'] : $_POST['emails'];
    }

    if (isset($meta['config'])) {
      if (isset($meta['config']['notifications']['recipients'])) {

        $meta['config']['notifications']['recipients'] = formcraft3_template($template, $meta['config']['notifications']['recipients']);
        $emails = formcraft3_parse_emails($meta['config']['notifications']['recipients'], 10);
        $sent = 0;
        $failed = 0;
        if (is_array($emails) && count($emails) > 0) {
          $email_subject = isset($meta['config']['notifications']['email_subject']) ? $meta['config']['notifications']['email_subject'] : esc_html__('New Form Submission','formcraft');

          $email_subject = formcraft3_template($template, $email_subject);
          $email_subject = formcraft3_template_content($content, $email_subject);
          $email_subject = formcraft3_math($content, $email_subject);
          $email_subject = html_entity_decode($email_subject);

          $email_body = isset($meta['config']['notifications']['email_body']) ? $meta['config']['notifications']['email_body'] : esc_html__('[Form Content]','formcraft');

          $email_body = formcraft3_template($template, $email_body);

          $email_body = formcraft3_template_content($content, $email_body);
          $email_body = formcraft3_email_template($email_body);
          $email_body = formcraft3_math($content, $email_body);
          $email_body = !empty($meta['config']['notifications']['removeEmptyTags']) ? preg_replace('/\[[^\]]*\]/', '', $email_body) : $email_body;

          $from_name = isset($meta['config']['notifications']['general_sender_name']) ? $meta['config']['notifications']['general_sender_name'] : 'FormCraft';
          $from_name = formcraft3_template($template, $from_name);

          $from_email = isset($meta['config']['notifications']['general_sender_email']) ? $meta['config']['notifications']['general_sender_email'] : get_bloginfo('admin_email');
          $from_email = formcraft3_template($template, $from_email);
          $from_email = formcraft3_template_content($content, $from_email);

          if ( !is_email_fc($from_email) ){
            $from_email = get_bloginfo('admin_email');
          }

          foreach ($emails as $email => $name) {

            if (isset($meta['config']['notifications']['_method']) && $meta['config']['notifications']['_method']=='smtp') {

              if (version_compare(get_bloginfo('version'), '5.5') >= 0) {
                  require_once(ABSPATH . 'wp-includes/PHPMailer/PHPMailer.php');
                  require_once(ABSPATH . 'wp-includes/PHPMailer/SMTP.php');
                  require_once(ABSPATH . 'wp-includes/PHPMailer/Exception.php');
                  $mail = new PHPMailer\PHPMailer\PHPMailer();
              } else {
                  require_once(ABSPATH . 'wp-includes/class-phpmailer.php');
                  $mail = new PHPMailer;
              }

              if ( !class_exists('Html2Text\Html2Text') && !function_exists('fix_newlines') ) {
                require_once 'lib/html2text/html2text.php';
              }

              $mail->isSMTP();
              $mail->Host = $meta['config']['notifications']['smtp_sender_host'];
              if (!empty($meta['config']['notifications']['smtp_sender_port'])) { $mail->SMTPAuth = true; }
              if (!empty($meta['config']['notifications']['smtp_sender_username'])) { $mail->Username = $meta['config']['notifications']['smtp_sender_username']; }
              if (!empty($meta['config']['notifications']['smtp_sender_password'])) { $mail->Password = $meta['config']['notifications']['smtp_sender_password']; }
              if (!empty($meta['config']['notifications']['smtp_sender_security'])) { $mail->SMTPSecure = $meta['config']['notifications']['smtp_sender_security']; }
              if (!empty($meta['config']['notifications']['smtp_sender_port'])) { $mail->Port = $meta['config']['notifications']['smtp_sender_port']; }

              if ( isset($replyTo) )
              {
                $mail->addReplyTo($replyTo);
              }

              $mail->From = $from_email;
              $mail->FromName = $from_name;
              $mail->addAddress($email, $name);
                            
              if ( isset($all_files) && isset($meta['config']['notifications']['attach_images']) && $meta['config']['notifications']['attach_images']==true ) {
                foreach ($all_files as $key => $file) {
                  $mail->addAttachment($file['file_path']);
                }
              }
              if ( !empty($notifyAttachments) ) {
                foreach ($notifyAttachments as $key => $file) {
                  $mail->addAttachment($file['path'], $file['name']);
                }
              }
              foreach ($signatureImages as $key => $file) {
                $mail->AddEmbeddedImage($file['path'], $file['id'], $file['name']);
              }
              $mail->isHTML(true);

              $mail->Subject = $email_subject;
              $mail->Body    = $email_body;
              if (function_exists('mb_detect_encoding')) {
                $mail->AltBody = convert_html_to_text($email_body, true);  
              }              
              $mail->CharSet = "UTF-8";

              if(!$mail->send()) {
                $failed++;
                $failed_msg = $mail->ErrorInfo;
              } else {
                $sent++;
              }
            }
            else
            {
              $subject = $email_subject;
              $message = $email_body;
              $headers = array();
              $from_name = html_entity_decode($from_name, ENT_QUOTES, 'utf-8');
              $headers[] = 'From: '."=?UTF-8?B?".base64_encode($from_name)."?=".' <'.$from_email.'>';
              $headers[] = 'Content-Type: text/html; charset=UTF-8';
              if ( isset($replyTo) )
              {
                $headers[] = 'Reply-To: '.$replyTo. "\r\n    ";
              }
              $attachments = array();
              if ( isset($all_files) && isset($meta['config']['notifications']['attach_images']) && $meta['config']['notifications']['attach_images']==true )
              {
                foreach ($all_files as $key => $file) {
                  $attachments[] = $file['file_path'];
                }
              }
              foreach ($signatureImages as $key => $file) {
                $attachments[] = $file['path'];
              }              
              if ( !empty($notifyAttachments) ) {
                foreach ($notifyAttachments as $key => $file) {
                  $attachments[] = $file['path'];
                }
              }
              $message = wordwrap($message, 70);
              $email_sent = wp_mail( $email, $subject, $message, $headers, $attachments );
              if(!$email_sent) {
                $failed++;
                $failed_msg = "Email setup error";
              } else {
                $sent++;
              }
            }
          }
          if ($failed>0) {
            $fc_final_response['debug']['failed'][] = esc_html__('Email Not Sent: ', 'formcraft').$failed_msg;
          }
          if ($sent>0) {
            $fc_final_response['debug']['success'][] = esc_html__($sent.' notification email(s) sent', 'formcraft');
          }
        }
      }
    }

    if (!empty($fc_final_response['delete-pdf'])) {
      unlink($fc_final_response['delete-pdf']);
    }



    // Send Data to Custom URL
    if (isset($meta['config']['Post_data']) && $meta['config']['Post_data']==true && isset($meta['config']['webhook'])) {
      $post_data = array();
      $post_data['Entry ID'] = $template['Entry ID'];
      foreach ($content as $key => $value) {
        if ($value['type']=='fileupload') {
        $value['value'] = is_array($value['url']) ? implode(', ', $value['url']) : $value['url'];
        } if ($value['type']=='matrix') {
        	$newValue = array();
        	foreach ($value['value'] as $key1 => $value1) {
        		$newValue[] = $value1['question'].': '.$value1['value'];
        	}
        	$value['value'] = implode(', ', $newValue);
        } else {
        $value['value'] = is_array($value['value']) ? implode(', ', $value['value']) : $value['value'];
        }
        $post_data[html_entity_decode($value['altLabel'], ENT_QUOTES, 'utf-8')] = html_entity_decode($value['value'], ENT_QUOTES, 'utf-8');
      }


      if (isset($meta['config']['webhook_method']) && $meta['config']['webhook_method']=='POST') {
        wp_remote_post($meta['config']['webhook'], array('body'=>$post_data));
      } else if ( isset($meta['config']['webhook_method']) && $meta['config']['webhook_method']=='POSTJSON' ) {
        $headers = array('Content-Type' => 'application/json; charset=utf-8');      
        wp_remote_post($meta['config']['webhook'], array('body'=>json_encode($post_data), 'headers'=>$headers));
      } else {
        $url = strpos($meta['config']['webhook'], '?') === FALSE ? $meta['config']['webhook'].'?'.http_build_query($post_data) : $meta['config']['webhook'].'&'.http_build_query($post_data);
        $url = formcraft3_template($template, $url);
        wp_remote_get($url);
      }
    }


    if (!empty($_POST['redirect'])) {
      $fc_final_response['redirect'] = formcraft3_template($template, $_POST['redirect'], true);
    } else if ( !empty($meta['config']['redirect_main']) ) {
      $fc_final_response['redirect'] = formcraft3_template($template, $meta['config']['redirect_main'], true);
    }

    if ( !empty($fc_final_response['success']) ) {
      $fc_final_response['success'] = formcraft3_template($template, $fc_final_response['success'], true);
    }

    /* Emails Sent, All Done */
    do_action('formcraft_after_save', $template, $meta, $content, $integrations);
    echo json_encode($fc_final_response); die();
  }
}
