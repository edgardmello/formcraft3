<?php

namespace FormCraft\Addon;

/**
 * AddonRegistry
 *
 * Handles addon registration and API data retrieval.
 * Extracted from legacy.php in v3.9.13.
 */
class AddonRegistry
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerAddon($content, $plugin_id, $title, $controller, $logo=false, $templates=false,$trigger=false) {
    global $fc_addons, $fc_templates, $fc_triggers;
    $plugin_id = $plugin_id==0 ? false : $plugin_id;
    $controller = $controller==false ? '' : $controller;
    $logo = $logo==false || $logo=='' ? plugins_url('../../assets/images/add-on-logo.png', __FILE__ ) : $logo;
    $fc_addons[] = array('content_fn'=>$content,'plugin_id'=>$plugin_id,'title'=>$title,'controller'=>$controller,'logo'=>$logo);
    $fc_templates[$title] = $templates;
    if ( $trigger == true ) {
      $fc_triggers[] = $title;
    }
  }

  public function getAddonData($addon, $id) {
    global $wpdb, $fc_forms_table;
    if ( !isset($id) || !ctype_digit($id) ) {
      return false;
    }
    $query = $wpdb->prepare("SELECT addons FROM $fc_forms_table WHERE id = %d", $id);
    $qry = $wpdb->get_var( $query );
    $data = \FormCraft\Security\Sanitizer::decodeDbJson($qry, true);
    if ( isset($data[$addon]) )
    {
      return $data[$addon];
    }
    else
    {
      return false;
    }
  }
}
