<?php

  /*
  Plugin Name: FormCraft
  Plugin URI: http://formcraft-wp.com
  Description: Premium WordPress form and survey builder. Make amazing forms, incredibly fast.
  Author: nCrafts
  Author URI: http://ncrafts.net
  Version: 3.9.12
  Domain Path: /languages
  Text Domain: formcraft
  */

  global $fc_meta, $fc_forms_table, $fc_progress_table, $fc_submissions_table, $fc_views_table, $fc_files_table, $wpdb, $fc_addons, $fc_translate;
  $fc_addons = array();
  $fc_templates = array();
  $fc_triggers = array();
  $fc_templates['General'] = plugin_dir_path( __FILE__ ).'templates/';
  $fc_meta['version'] = '3.9.12';
  $fc_meta['f3_multi_site_addon'] = is_multisite() ? false : true;
  $fc_meta['user_can'] = strpos(get_site_url(), 'formcraft-wp.com/demo') > -1 || strpos(get_site_url(), 'formcraft-wp.com/plugin-demo') > -1 ? 'read' : 'activate_plugins';
  $fc_meta['preview_mode'] = strpos(get_site_url(), 'formcraft-wp.com/demo') > -1 || strpos(get_site_url(), 'formcraft-wp.com/plugin-demo') > -1 ? true : false;
  $fc_forms_table = $wpdb->prefix . "formcraft_3_forms";
  $fc_submissions_table = $wpdb->prefix . "formcraft_3_submissions";
  $fc_views_table = $wpdb->prefix . "formcraft_3_views";
  $fc_progress_table = $wpdb->prefix . "formcraft_3_progress";
  $fc_files_table = $wpdb->prefix . "formcraft_3_files";
  $fc_meta['basic_html'] = array('a' => array(), 'string' => array(), 'p' => array(), 'br' => array());

  function is_email_fc($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  function formcraft3_translate_init() {
    global $fc_translate, $fc_meta;
    load_plugin_textdomain('formcraft', false, basename( dirname( __FILE__ ) ) . '/languages');
    $fc_translate = array();
    $fc_translate['1w'] = esc_html__('1w', 'formcraft');
    $fc_translate['1m'] = esc_html__('1m', 'formcraft');
    $fc_translate['1y'] = esc_html__('1y', 'formcraft');
    $fc_translate['Form Name'] = esc_html__('Form Name', 'formcraft');
    $fc_translate['Set Width Option'] = wp_kses(__('Set the widths of two fields to <strong>50%</strong> each to fit them in one row.<br>You can have any number of fields in the same row, as long as the sum of widths is <strong>100%</strong>', 'formcraft'), $fc_meta['basic_html']);
    $fc_translate['Alt Label'] = esc_html__('The field label / key to use when sending data via a webhook', 'formcraft');
    $fc_translate['Dropdown One'] = esc_html__('You can set the value of the options different from the text, using this pattern', 'formcraft');
    $fc_translate['Dropdown Two'] = wp_kses(__('Here, <strong>100</strong> would be the value, and <strong>Apple</strong> would be the text.', 'formcraft'), $fc_meta['basic_html']);
    $fc_translate['keepdata'] = esc_html__('Keep Data When Deleting Plugin', 'formcraft');
    $fc_translate['exportall'] = esc_html__('Export All Data', 'formcraft');
    $fc_translate['needAPIKey'] = esc_html__('You need to enter the Google API Key when editing the field to make the autocomplete address field work', 'formcraft');
    $fc_translate['Form:'] = esc_html__('Form:', 'formcraft');
    $fc_translate['Embed Type:'] = esc_html__('Embed Type:', 'formcraft');
    $fc_translate['Add a FormCraft form'] = esc_html__('Add a FormCraft form', 'formcraft');
    $fc_translate['Inline'] = esc_html__('Inline', 'formcraft');
    $fc_translate['Popup'] = esc_html__('Popup', 'formcraft');
    $fc_translate['Slide In'] = esc_html__('Slide In', 'formcraft');
    $fc_translate['Button Text:'] = esc_html__('Button Text:', 'formcraft');
    $fc_translate['Alignment:'] = esc_html__('Alignment:', 'formcraft');
    $fc_translate['Left'] = esc_html__('Left', 'formcraft');
    $fc_translate['Center'] = esc_html__('Center', 'formcraft');
    $fc_translate['Right'] = esc_html__('Right', 'formcraft');
    $fc_translate['Placement:'] = esc_html__('Placement:', 'formcraft');
    $fc_translate['Bottom Right'] = esc_html__('Bottom Right', 'formcraft');
    $fc_translate['Bind:'] = esc_html__('Bind:', 'formcraft');
    $fc_translate['bind form popup action to a CSS selector'] = esc_html__('bind form popup action to a CSS selector', 'formcraft');
    $fc_translate['Class:'] = esc_html__('Class:', 'formcraft');
    $fc_translate['add a custom class to the popup button'] = esc_html__('add a custom class to the popup button', 'formcraft');
    $fc_translate['Font Color:'] = esc_html__('Font Color:', 'formcraft');
    $fc_translate['font color of the button'] = esc_html__('font color of the button', 'formcraft');
    $fc_translate['Button Color:'] = esc_html__('Button Color:', 'formcraft');
    $fc_translate['color of the button'] = esc_html__('color of the button', 'formcraft');
    $fc_translate['Auto Popup:'] = esc_html__('Auto Popup:', 'formcraft');
    $fc_translate['auto popup the form on page load after x seconds'] = esc_html__('auto popup the form on page load after x seconds', 'formcraft');
    $fc_translate['loseChanges'] = esc_html__('You will lose any un-saved changes.', 'formcraft');
    $fc_translate['Free AddOns'] = esc_html__('Free AddOns', 'formcraft');
    $fc_translate['Purchased AddOns'] = esc_html__('Purchased AddOns', 'formcraft');
    $fc_translate['Paid AddOns'] = esc_html__('Paid AddOns', 'formcraft');
    $fc_translate['read more'] = esc_html__('read more', 'formcraft');
    $fc_translate['Nothing Left To Install'] = esc_html__('Nothing Left To Install', 'formcraft');
    $fc_translate['Please check your internet connection'] = esc_html__('Please check your internet connection', 'formcraft');
    $fc_translate['back'] = esc_html__('back', 'formcraft');
    $fc_translate['Help Topics'] = esc_html__('Help Topics', 'formcraft');
    $fc_translate['Sorry, nothing here'] = esc_html__('Sorry, nothing here', 'formcraft');
    $fc_translate['Contact Support'] = esc_html__('Contact Support', 'formcraft');
    $fc_translate['Unknown Error.'] = esc_html__('Unknown Error.', 'formcraft');
    $fc_translate['Failed Saving.'] = esc_html__('Failed Saving.', 'formcraft');
    $fc_translate['Failed Saving. Please try disabing your firewall, or security plugin.'] = esc_html__('Failed Saving. Please try disabing your firewall, or security plugin.', 'formcraft');
    $fc_translate['Debug Info'] = esc_html__('Debug Info', 'formcraft');
    $fc_translate['Invalid'] = esc_html__('Invalid', 'formcraft');
    $fc_translate['Min [x] characters required'] = esc_html__('Min [x] characters required', 'formcraft');
    $fc_translate['Max [x] characters allowed'] = esc_html__('Max [x] characters allowed', 'formcraft');
    $fc_translate['Max [x] characters allowed'] = esc_html__('Max [x] characters allowed', 'formcraft');
    $fc_translate['Max [x] file(s) allowed'] = esc_html__('test', 'formcraft');
    $fc_translate['Files bigger than [x] MB not allowed'] = esc_html__('Files bigger than [x] MB not allowed', 'formcraft');
    $fc_translate['Invalid Email'] = esc_html__('Invalid Email', 'formcraft');
    $fc_translate['Invalid URL'] = esc_html__('Invalid URL', 'formcraft');
    $fc_translate['Invalid Expression'] = esc_html__('Invalid Expression', 'formcraft');
    $fc_translate['Only alphabets'] = esc_html__('Only alphabets', 'formcraft');
    $fc_translate['Only numbers'] = esc_html__('Only numbers', 'formcraft');
    $fc_translate['Should be alphanumeric'] = esc_html__('Should be alphanumeric', 'formcraft');
    $fc_translate['Please correct the errors and try again'] = esc_html__('Please correct the errors and try again', 'formcraft');
    $fc_translate['Message received'] = esc_html__('Message received', 'formcraft');
    $fc_translate['Email Content Autoresponder'] = wp_kses(__('<p>Hello [Name],</p><p><br></p><p>We have received your submission. Here are the details you have submitted to us:</p><p>[Form Content]</p><p><br></p><p>Regards,</p><p>Nishant</p>', 'formcraft'), $fc_meta['basic_html']);
    $fc_translate['Thank you for your submission'] = esc_html__('Thank you for your submission', 'formcraft');
    $fc_translate['<p>Hello,</p><p><br></p><p>You have received a new form submission for the form [Form Name]. Here are the details:</p><p>[Form Content]</p><p><br></p><p>Page: [URL]<br>Unique ID: #[Entry ID]<br>Date: [Date]<br>Time: [Time]</p>'] = wp_kses(__('<p>Hello,</p><p><br></p><p>You have received a new form submission for the form [Form Name]. Here are the details:</p><p>[Form Content]</p><p><br></p><p>Page: [URL]<br>Unique ID: #[Entry ID]<br>Date: [Date]<br>Time: [Time]</p>', 'formcraft'), $fc_meta['basic_html']);
    $fc_translate['New Form Submission'] = esc_html__('New Form Submission', 'formcraft');
    $fc_translate['Heading'] = esc_html__('Heading', 'formcraft');
    $fc_translate['Some Title'] = esc_html__('Some Title', 'formcraft');
    $fc_translate['Name'] = esc_html__('Name', 'formcraft');
    $fc_translate['your full name'] = esc_html__('your full name', 'formcraft');
    $fc_translate['Password'] = esc_html__('Password', 'formcraft');
    $fc_translate['check your caps'] = esc_html__('check your caps', 'formcraft');
    $fc_translate['Email'] = esc_html__('Email', 'formcraft');
    $fc_translate['a valid email'] = esc_html__('a valid email', 'formcraft');
    $fc_translate['Comments'] = esc_html__('Comments', 'formcraft');
    $fc_translate['more details'] = esc_html__('more details', 'formcraft');
    $fc_translate['Favorite Fruits'] = esc_html__('Favorite Fruits', 'formcraft');
    $fc_translate['pick one!'] = esc_html__('pick one!', 'formcraft');
    $fc_translate['Language'] = esc_html__('Language', 'formcraft');
    $fc_translate['Date'] = esc_html__('Date', 'formcraft');
    $fc_translate['of appointment'] = esc_html__('of appointment', 'formcraft');
    $fc_translate['Add some text or <strong>HTML</strong> here'] = wp_kses(__('Add some text or <strong>HTML</strong> here', 'formcraft'), $fc_meta['basic_html']);
    $fc_translate['Text Field'] = esc_html__('Text Field', 'formcraft');
    $fc_translate['Submit Form'] = esc_html__('Submit Form', 'formcraft');
    $fc_translate['File'] = esc_html__('File', 'formcraft');
    $fc_translate['upload'] = esc_html__('upload', 'formcraft');
    $fc_translate['Slider'] = esc_html__('Slider', 'formcraft');
    $fc_translate['take your pick'] = esc_html__('take your pick', 'formcraft');
    $fc_translate['Time'] = esc_html__('Time', 'formcraft');
    $fc_translate['of appointment'] = esc_html__('of appointment', 'formcraft');
    $fc_translate['Address'] = esc_html__('Address', 'formcraft');
    $fc_translate['your home / office'] = esc_html__('your home / office', 'formcraft');
    $fc_translate['Rate'] = esc_html__('Rate', 'formcraft');
    $fc_translate['our support'] = esc_html__('our support', 'formcraft');
    $fc_translate['Liked the food?'] = esc_html__('Liked the food?', 'formcraft');
    $fc_translate['let us know'] = esc_html__('let us know', 'formcraft');
    $fc_translate['Survey'] = esc_html__('Survey', 'formcraft');
    $fc_translate['How Was the Food?'] = esc_html__('How Was the Food?', 'formcraft');
    $fc_translate['How Was the Service?'] = esc_html__('How Was the Service?', 'formcraft');
    $fc_translate['Poor'] = esc_html__('Poor', 'formcraft');
    $fc_translate['Average'] = esc_html__('Average', 'formcraft');
    $fc_translate['Good'] = esc_html__('Good', 'formcraft');
    $fc_translate['Bad'] = esc_html__('Bad', 'formcraft');
    $fc_translate['Could be better'] = esc_html__('Could be better', 'formcraft');
    $fc_translate['So so'] = esc_html__('So so', 'formcraft');
    $fc_translate['Excellent'] = esc_html__('Excellent', 'formcraft');
    $fc_translate['Blank'] = esc_html__('Blank', 'formcraft');
    $fc_translate['Template'] = esc_html__('Template', 'formcraft');
    $fc_translate['Duplicate'] = esc_html__('Duplicate', 'formcraft');
    $fc_translate['Import'] = esc_html__('Import', 'formcraft');
    $fc_translate['(blank form)'] = esc_html__('(blank form)', 'formcraft');
    $fc_translate['80% Zoom'] = esc_html__('80% Zoom', 'formcraft');
    $fc_translate['Select Form Template To View'] = esc_html__('Select Form Template To View', 'formcraft');
    $fc_translate['Select Form'] = esc_html__('Select Form', 'formcraft');
    $fc_translate['Create Form'] = esc_html__('Create Form', 'formcraft');
    $fc_translate['No Forms Found'] = esc_html__('No Forms Found', 'formcraft');
    $fc_translate['Forms'] = esc_html__('Forms', 'formcraft');
    $fc_translate['New Form'] = esc_html__('New Form', 'formcraft');
    $fc_translate['ID'] = esc_html__('ID', 'formcraft');
    $fc_translate['Name'] = esc_html__('Name', 'formcraft');
    $fc_translate['Last Edit'] = esc_html__('Last Edit', 'formcraft');
    $fc_translate["Sure? This action can't be reversed."] = esc_html__("Sure? This action can't be reversed.", 'formcraft');
    $fc_translate['Form Analytics'] = esc_html__('Form Analytics', 'formcraft');
    $fc_translate['reset analytics data'] = esc_html__('reset analytics data', 'formcraft');
    $fc_translate['Custom'] = esc_html__('Custom', 'formcraft');
    $fc_translate['All Forms'] = esc_html__('All Forms', 'formcraft');
    $fc_translate['form views'] = esc_html__('form views', 'formcraft');
    $fc_translate['submissions'] = esc_html__('submissions', 'formcraft');
    $fc_translate['conversion'] = esc_html__('conversion', 'formcraft');
    $fc_translate['charges'] = esc_html__('charges', 'formcraft');
    $fc_translate['conversion'] = esc_html__('conversion', 'formcraft');
    $fc_translate['No Entries Found'] = esc_html__('No Entries Found', 'formcraft');
    $fc_translate['Select Form to Export'] = esc_html__('Select Form to Export', 'formcraft');
    $fc_translate['Separator:'] = esc_html__('Separator:', 'formcraft');
    $fc_translate['Comma (CSV format)'] = esc_html__('Comma (CSV format)', 'formcraft');
    $fc_translate['Semicolon'] = esc_html__('Semicolon', 'formcraft');
    $fc_translate['Export'] = esc_html__('Export', 'formcraft');
    $fc_translate['Entries'] = esc_html__('Entries', 'formcraft');
    $fc_translate['(All Forms)'] = esc_html__('(All Forms)', 'formcraft');
    $fc_translate['Created'] = esc_html__('Created', 'formcraft');
    $fc_translate['Hide Empty Fields'] = esc_html__('Hide Empty Fields', 'formcraft');
    $fc_translate['Save Changes'] = esc_html__('Save Changes', 'formcraft');
    $fc_translate['Edit Entry'] = esc_html__('Edit Entry', 'formcraft');
    $fc_translate['Print'] = esc_html__('Print', 'formcraft');
    $fc_translate['Referer'] = esc_html__('Referer', 'formcraft');
    $fc_translate['Insights'] = esc_html__('Insights', 'formcraft');
    $fc_translate['Get Insights'] = esc_html__('Get Insights', 'formcraft');
    $fc_translate['Select Form'] = esc_html__('Select Form', 'formcraft');
    $fc_translate['Period'] = esc_html__('Period', 'formcraft');
    $fc_translate['All'] = esc_html__('All', 'formcraft');
    $fc_translate['Custom'] = esc_html__('Custom', 'formcraft');
    $fc_translate['From'] = esc_html__('From', 'formcraft');
    $fc_translate['To'] = esc_html__('To', 'formcraft');
    $fc_translate['Max Entries'] = esc_html__('Max Entries', 'formcraft');
    $fc_translate['Entries Analyzed'] = esc_html__('Entries Analyzed', 'formcraft');
    $fc_translate['No Insights Available'] = esc_html__('No Insights Available', 'formcraft');
    $fc_translate['learn more'] = esc_html__('learn more', 'formcraft');
    $fc_translate['License Key verified'] = esc_html__('License Key verified', 'formcraft');
    $fc_translate['Verified'] = esc_html__('Verified', 'formcraft');
    $fc_translate['Update Key Info'] = esc_html__('Update Key Info', 'formcraft');
    $fc_translate['Verify Key'] = esc_html__('Verify Key', 'formcraft');
    $fc_translate['Purchased On'] = esc_html__('Purchased On', 'formcraft');
    $fc_translate['Last Check'] = esc_html__('Last Check', 'formcraft');
    $fc_translate['Expires On'] = esc_html__('Expires On', 'formcraft');
    $fc_translate['More Info'] = esc_html__('More Info', 'formcraft');
    $fc_translate['days too late'] = esc_html__('days too late', 'formcraft');
    $fc_translate['days left'] = esc_html__('days left', 'formcraft');
    $fc_translate['Renew License Key'] = esc_html__('Renew License Key', 'formcraft');
    $fc_translate['renewing the license key gives you access to auto plugin updates and free customer support'] = esc_html__('renewing the license key gives you access to auto plugin updates and free customer support', 'formcraft');
    $fc_translate['No Files Found'] = esc_html__('No Files Found', 'formcraft');
    $fc_translate['File Uploads'] = esc_html__('File Uploads', 'formcraft');
    $fc_translate['Trash'] = esc_html__('Trash', 'formcraft');
    $fc_translate['Type'] = esc_html__('Type', 'formcraft');
    $fc_translate['Size'] = esc_html__('Size', 'formcraft');
    $fc_translate['Created'] = esc_html__('Created', 'formcraft');
    $fc_translate['Disable Analytics'] = esc_html__('Disable Analytics', 'formcraft');
    $fc_translate['Search'] = esc_html__('Search', 'formcraft');
    $fc_translate['Loading'] = esc_html__('Loading', 'formcraft');
    $fc_translate['Entry View'] = esc_html__('Entry View', 'formcraft');
    $fc_translate['Insights'] = esc_html__('Insights', 'formcraft');
    $fc_translate['Your License Key'] = esc_html__('Your License Key', 'formcraft');
    $fc_translate['Your Email'] = esc_html__('Your Email', 'formcraft');
  }
  add_action('init', 'formcraft3_translate_init');  

  /*
  Create the necessary tables on plugin activation
  */
  function formcraft3_activate() {
    global $fc_meta, $fc_forms_table, $fc_submissions_table, $fc_views_table, $fc_files_table, $fc_progress_table, $wpdb;
    update_site_option('f3_keep_data', true);
    if (!is_multisite()) {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE $fc_progress_table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `uniq_key` tinytext NOT NULL, form INT NOT NULL,
      `content` MEDIUMTEXT NULL,
      `created` INT(11) NULL DEFAULT NULL,
      `modified` INT(11) NULL DEFAULT NULL,
      `to_delete` INT(11) NULL DEFAULT NULL,
      UNIQUE KEY id (id)
      ) $charset_collate;";
      dbDelta( $sql );

      $sql = "CREATE TABLE $fc_forms_table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `counter` INT NOT NULL,
      `name` tinytext NOT NULL,
      `created` INT(11) NULL DEFAULT NULL,
      `modified` INT(11) NULL DEFAULT NULL,
      `html` MEDIUMTEXT NULL,
      `builder` MEDIUMTEXT NULL,
      `addons` MEDIUMTEXT NULL,
      `meta_builder` MEDIUMTEXT NULL,
      `old_url` tinytext NULL,
      `imported` INT NULL,
      UNIQUE KEY id (id)
      ) $charset_collate;";
      dbDelta( $sql );

      $sql = "CREATE TABLE $fc_submissions_table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `form` INT NOT NULL,
      `form_name` tinytext NOT NULL,
      `created` INT(11) NULL DEFAULT NULL,
      `content` MEDIUMTEXT NULL,
      `visitor` MEDIUMTEXT NULL,
      UNIQUE KEY id (id)
      ) $charset_collate;";
      dbDelta( $sql );

      $sql = "CREATE TABLE $fc_views_table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `form` INT NOT NULL,
      `views` INT NOT NULL,
      `submissions` INT NOT NULL,
      `payment` FLOAT NOT NULL DEFAULT '0',
      `_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      UNIQUE KEY id (id)
      ) $charset_collate;";
      dbDelta( $sql );

      $sql = "CREATE TABLE $fc_files_table (
      `id` mediumint(9) NOT NULL AUTO_INCREMENT,
      `uniq_key` tinytext NOT NULL,
      `name` VARCHAR(255) NOT NULL,
      `form` INT NOT NULL,
      `submission` INT NULL,
      `permanent` tinyint(1) NOT NULL,
      `mime` VARCHAR(255) NOT NULL,
      `size` INT NOT NULL,
      `file_url` VARCHAR(1000) NOT NULL,
      `file_path` VARCHAR(1000) NOT NULL,
      `created` INT(11) NULL DEFAULT NULL,
      UNIQUE KEY id (id)
      ) $charset_collate;";
      dbDelta( $sql );
      formcraft3_check_for_imports();
    }
  }
  register_activation_hook( __FILE__, 'formcraft3_activate' );

  function formcraft3_delete() {
    global $fc_meta, $fc_forms_table, $fc_submissions_table, $fc_views_table, $fc_files_table, $fc_progress_table, $wpdb;
    if (empty(get_site_option('f3_keep_data'))) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
      require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      $sql = "DROP TABLE IF EXISTS $fc_progress_table";
      $wpdb->query($sql);

      $sql = "DROP TABLE IF EXISTS $fc_forms_table";
      $wpdb->query($sql);

      $sql = "DROP TABLE IF EXISTS $fc_submissions_table";
      $wpdb->query($sql);

      $sql = "DROP TABLE IF EXISTS $fc_views_table";
      $wpdb->query($sql);

      $sql = "DROP TABLE IF EXISTS $fc_files_table";
      $wpdb->query($sql);

      $upload = wp_upload_dir( );
    // Set the permission constants if not already set.
        if ( ! defined( 'FS_CHMOD_DIR' ) ) {
            define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
        }
        if ( ! defined( 'FS_CHMOD_FILE' ) ) {
            define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
        }       
      $FSD = new WP_Filesystem_Direct(false);
      $FSD->rmdir($upload['basedir'].'/formcraft3', true);

      delete_site_option('f3_verified');
      delete_site_option('f3_email');
      delete_site_option('f3_key');
      delete_site_option('f3_disable_analytics');
      delete_site_option('f3_purchased');
      delete_site_option('f3_registered');
      delete_site_option('f3_expires');
      delete_site_option('f3_blog_id');
    }
  }
  register_uninstall_hook( __FILE__, 'formcraft3_delete' );

  add_action('wp_ajax_formcraft3_verify_license', 'formcraft3_verify_license');
  function formcraft3_verify_license() {
    global $wp_version, $fc_meta, $fc_forms_table, $fc_progress_table, $fc_submissions_table, $fc_views_table, $fc_files_table, $wpdb, $fc_addons;
    $licenseKey = trim(strtolower($_GET['key']));
    $licenseEmail = trim(strtolower($_GET['email']));
    if ( empty($licenseKey) ) {
      echo json_encode(array('failed'=>esc_html__('License Key cannot be empty', 'formcraft')));
      die();
    }
    if ( empty($licenseEmail) ) {
      echo json_encode(array('failed'=>esc_html__('Email cannot be empty', 'formcraft')));
      die();
    }
    if ( is_email_fc($licenseEmail) === false ) {
      echo json_encode(array('failed'=>esc_html__('Invalid email', 'formcraft')));
      die();
    }
    $args = array(
      'timeout'     => 15,
      'redirection' => 5,
      'sslverify'   => false
    );
    $siteURL = is_multisite() && $fc_meta['f3_multi_site_addon'] === true ? network_site_url() : site_url();
    $url = "http://formcraft-wp.com?type=register_license&key=".rawurlencode($licenseKey)."&site=".rawurlencode($siteURL)."&email=".rawurlencode($licenseEmail).'&v=2';
    $response_api = \FormCraft\Security\Sanitizer::remoteGet($url, ['sslverify' => false, 'timeout' => 10]);
    $response = is_wp_error($response_api) ? null : wp_remote_retrieve_body($response_api);

    if ( $response==NULL || empty($response) ) {
      echo json_encode(array('failed'=>esc_html__('Could not connect','formcraft')));
      die();
    }
    $response = json_decode($response, 1);

    if ( isset($response['success']) ) {
      update_site_option( 'f3_verified', 'yes' );

      update_site_option( 'f3_email', $licenseEmail );
      update_site_option( 'f3_key', $licenseKey );

      update_site_option( 'f3_purchased', $response['purchased'] );
      update_site_option( 'f3_registered', $response['registered'] );
      update_site_option( 'f3_expires', $response['expires'] );
      update_site_option( 'f3_blog_id', get_current_blog_id() );

      if (is_multisite()) {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        if($wpdb->get_var("SHOW TABLES LIKE '$fc_progress_table'") != $fc_progress_table) {
          $sql = "CREATE TABLE $fc_progress_table (id mediumint(9) NOT NULL AUTO_INCREMENT, uniq_key tinytext NOT NULL, form INT NOT NULL, content MEDIUMTEXT NULL, created INT(11) NULL DEFAULT NULL, modified INT(11) NULL DEFAULT NULL, to_delete INT(11) NULL DEFAULT NULL, UNIQUE KEY id (id)) $charset_collate;";
          dbDelta( $sql );
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$fc_forms_table'") != $fc_forms_table) {
          $sql = "CREATE TABLE $fc_forms_table (id mediumint(9) NOT NULL AUTO_INCREMENT, counter INT NOT NULL,name tinytext NOT NULL,created INT(11) NULL DEFAULT NULL,modified INT(11) NULL DEFAULT NULL,html MEDIUMTEXT NULL,builder MEDIUMTEXT NULL,addons MEDIUMTEXT NULL,meta_builder MEDIUMTEXT NULL, old_url tinytext NULL, imported INT NULL,UNIQUE KEY id (id)) $charset_collate;";
          dbDelta( $sql );
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$fc_submissions_table'") != $fc_submissions_table) {
          $sql = "CREATE TABLE $fc_submissions_table (id mediumint(9) NOT NULL AUTO_INCREMENT,form INT NOT NULL,form_name tinytext NOT NULL,created INT(11) NULL DEFAULT NULL,content MEDIUMTEXT NULL,visitor MEDIUMTEXT NULL,UNIQUE KEY id (id)) $charset_collate;";
          dbDelta( $sql );
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$fc_views_table'") != $fc_views_table) {
          $sql = "CREATE TABLE $fc_views_table (id mediumint(9) NOT NULL AUTO_INCREMENT,form INT NOT NULL,views INT NOT NULL,submissions INT NOT NULL, payment FLOAT NOT NULL DEFAULT '0',_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,UNIQUE KEY id (id)) $charset_collate;";
          dbDelta( $sql );
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$fc_files_table'") != $fc_files_table) {
          $sql = "CREATE TABLE $fc_files_table (id mediumint(9) NOT NULL AUTO_INCREMENT, uniq_key tinytext NOT NULL, name VARCHAR(255) NOT NULL,form INT NOT NULL,submission INT NULL, permanent tinyint(1) NOT NULL, mime VARCHAR(255) NOT NULL, size INT NOT NULL, file_url VARCHAR(1000) NOT NULL,file_path VARCHAR(1000) NOT NULL,created INT( 11 ) NULL DEFAULT NULL,UNIQUE KEY id (id)) $charset_collate;";
          dbDelta( $sql );
        }

        formcraft3_check_for_imports();
      }

      $response['purchased'] = date(get_option('date_format'), $response['purchased']);
      $response['expires_days'] = ( $response['expires'] - strtotime('now') ) / ( 60 * 60 * 24 );
      $response['expires'] = date(get_option('date_format'), $response['expires']);
      $response['registered'] = date(get_option('date_format'), $response['registered']);

      echo json_encode($response);
      die();
    } else {
      echo json_encode(array('failed'=>$response['failed']));
      die();
    }
  }

  class FormCraft_Plugin_Updater {

    private $slug;
    private $pluginData;
    private $username;
    private $repo;
    private $pluginFile;
    private $githubAPIResult;
    private $accessToken;
    private $pluginActivated;

    function __construct( $pluginFile, $gitHubUsername, $gitHubProjectName, $accessToken = '' ) {

      add_filter( "pre_set_site_transient_update_plugins", array( $this, "setTransitent" ) );
      add_filter( "plugins_api", array( $this, "setPluginInfo" ), 10, 3 );
      add_filter( "upgrader_pre_install", array( $this, "preInstall" ), 10, 3 );
      add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );

      $this->pluginFile = $pluginFile;
      $this->username = $gitHubUsername;
      $this->repo = $gitHubProjectName;
      $this->accessToken = $accessToken;
    }

    private function initPluginData() {
      $this->slug = plugin_basename( $this->pluginFile );
      $this->pluginData = get_plugin_data( $this->pluginFile );
    }

    private function getRepoReleaseInfo() {
      $result = wp_remote_get('http://formcraft-wp.com?type=release_info&repo='.$this->repo);
      $result = wp_remote_retrieve_body($result);
      $this->githubAPIResult = json_decode($result);
    }

    public function setTransitent( $transient ) {

      $this->initPluginData();
      $this->getRepoReleaseInfo();

      
      if ( get_site_option( 'f3_expires' )==NULL ) {
        return $transient;
      }

      $expires_time = get_site_option( 'f3_expires' );
      
      if ( ($expires_time-strtotime('now'))/(60 * 60 * 24)<0 ) {
        return $transient;
      }

      
      if ( empty( $transient->checked ) || empty( $this->githubAPIResult->tag_name ) ) {
        return $transient;
      }


      $doUpdate = version_compare( $this->githubAPIResult->tag_name, $this->pluginData['Version'] );
      if ( $doUpdate == 1 ) {
        $tempSlug = explode('/', $this->slug);
        $package = $this->githubAPIResult->zipball_url;
        $obj = new stdClass();
        $obj->slug = sanitize_title_with_dashes($tempSlug[0]);
        $obj->new_version = $this->githubAPIResult->tag_name;
        $obj->url = $this->pluginData["PluginURI"];
        $obj->package = $package;
        $transient->response[$this->slug] = $obj;
      }
      return $transient;
    }

    public function setPluginInfo( $false, $action, $response ) {
      $this->initPluginData();
      $this->getRepoReleaseInfo();
      $compareSlug = explode('/', $this->slug);
      $compareSlug = sanitize_title_with_dashes($compareSlug[0]);
      if ( empty( $response->slug ) || ( $response->slug != $compareSlug && $response->slug != $this->slug ) ) {
        return $false;
      }
      require_once( plugin_dir_path( __FILE__ ) . "lib/parsedown.php" );

      $response->last_updated = $this->githubAPIResult->published_at;
      $response->slug = $this->slug;
      $response->plugin_name  = $this->pluginData["Name"];
      $response->name  = $this->pluginData["Name"];
      $response->version = $this->githubAPIResult->tag_name;
      $response->author = $this->pluginData["AuthorName"];
      $response->homepage = $this->pluginData["PluginURI"];

      // This is our release download zip file
      $downloadLink = $this->githubAPIResult->zipball_url;
      $response->download_link = $downloadLink;
      $response->sections = array(
        'changelog' => class_exists( "Parsedown" )
        ? Parsedown::instance()->parse( $this->githubAPIResult->body )
        : $this->githubAPIResult->body
      );

      // Gets the required version of WP if available
      $matches = null;
      preg_match( "/requires:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
      if ( ! empty( $matches ) ) {
        if ( is_array( $matches ) ) {
          if ( count( $matches ) > 1 ) {
            $response->requires = $matches[1];
          }
        }
      }

      // Gets the tested version of WP if available
      $matches = null;
      preg_match( "/tested:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
      if ( ! empty( $matches ) ) {
        if ( is_array( $matches ) ) {
          if ( count( $matches ) > 1 ) {
            $response->tested = $matches[1];
          }
        }
      }
      return $response;
    }

    public function preInstall( $true, $args )
    {
      $this->initPluginData();
      $this->pluginActivated = is_plugin_active( $this->slug );
    }

    public function postInstall( $true, $hook_extra, $result ) {
      $this->initPluginData();
      global $wp_filesystem;

      if ( isset($_GET['plugin']) && $_GET['plugin']!=$this->slug ) {
      	return $true;
      }
      if ( isset($_GET['plugin']) ) {
        $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
        $result['destination'] = substr($result['destination'], 0, -1);
        $wp_filesystem->move( $pluginFolder, $pluginFolder.'-temp', true );
        $wp_filesystem->move( $result['destination'], $pluginFolder, true );
        $wp_filesystem->delete( $pluginFolder.'-temp', true );
        $result['destination'] = $pluginFolder;
        if ( $this->pluginActivated )
        {
          $activate = activate_plugin( $this->slug );
        }
        return $result;
      }
      else
      {
        $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
        $wp_filesystem->move( $result['destination'], $pluginFolder );
        $result['destination'] = $pluginFolder;
        if ( $this->pluginActivated )
        {
          $activate = activate_plugin( $this->slug );
        }
        return $result;
      }
    }
  }
  if ( is_admin() ) {
    new FormCraft_Plugin_Updater( __FILE__, 'ncrafts', "formcraft3" );
  }

  function formcraft3_check_for_imports() {
    global $wpdb, $fc_forms_table;
    $table_name = $wpdb->prefix . "formcraft_builder";
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      return false;
    }
    $all_data = $wpdb->get_results( "SELECT * FROM $table_name" , ARRAY_A);
    foreach ($all_data as $key => $value) {
      $value['id'] = intval($value['id']);
      $query = $wpdb->prepare("SELECT COUNT(*) FROM $fc_forms_table WHERE imported=%d", $value['id']);
      if ($wpdb->get_var( $query )!=0){ 
        continue;
      }
      $form_name = $value['name'];
      $builder = $value['build'].'[BREAK]'.$value['options'].'[BREAK]'.$value['con'].'[BREAK]'.$value['recipients'];
      $rows_affected = $wpdb->insert( $fc_forms_table, array(
        'name' => $form_name,
        'created' => strtotime('now'),
        'modified' => strtotime('now'),
        'builder' => $builder,
        'imported' => $value['id']
        ) );
    }
  }

  /*
  Add-On Framework
  */
  function register_formcraft_addon($content, $plugin_id, $title, $controller, $logo=false, $templates=false,$trigger=false) {
    global $fc_addons, $fc_templates, $fc_triggers;
    $plugin_id = $plugin_id==0 ? false : $plugin_id;
    $controller = $controller==false ? '' : $controller;
    $logo = $logo==false || $logo=='' ? plugins_url('assets/images/add-on-logo.png', __FILE__ ) : $logo;
    $fc_addons[] = array('content_fn'=>$content,'plugin_id'=>$plugin_id,'title'=>$title,'controller'=>$controller,'logo'=>$logo);
    $fc_templates[$title] = $templates;
    if ( $trigger == true ) {
      $fc_triggers[] = $title;
    }
  }
  function formcraft_get_addon_data($addon, $id) {
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

  /* Add-On Install - Little Ugly */
  add_action('wp_ajax_formcraft3_install_plugin', 'formcraft3_install_plugin');
  function formcraft3_install_plugin() {
    global $fc_addons, $fc_meta;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }    
    if ($fc_meta['preview_mode']==true) {
      echo json_encode(array('failed'=>esc_html__('Cannot install plugins in demo mode', 'formcraft')));
      die();
    }
    do_action('formcraft_addon_init');
    $plugin = intval($_POST['plugin']);
    $result = wp_remote_get('http://formcraft-wp.com?type=download_plugin&id='.$plugin.'&key='.get_site_option('f3_key'));
    if (is_wp_error($result)) {
      $error_string = $result->get_error_message();
      echo json_encode(array('failed'=>$error_string));
      die();
    }
    $result = json_decode($result['body'], 1);
    if (isset($result['failed'])) {
      echo json_encode(array('failed'=>$result['failed']));
      die();
    }
    else if (!isset($result['success'])) {
      echo json_encode(array('failed'=>esc_html__('Unknwon error','formcraft')));
      die();
    }
    $plugin_file = $result['file'];

    if ( file_exists(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $result['github-repo']) )
    {
      echo json_encode(array('failed'=>esc_html__('Plugin / Add-On is already installed. Please go to the Wordpress Plugins page and activate it.','formcraft')));
      die();
    }
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    class Plugin_Installer_Skin_B extends Plugin_Installer_Skin
    {
      public $done_header = true;
      public $done_footer = true;
      public function feedback($string)
      {
        if ( strpos($string, 'Destination folder already exists.') !== false ) {
          echo json_encode(array('failed'=>esc_html__('Plugin already installed. Maybe not activated?','formcraft')));
          die();
        }
      }
    }
    $plugin_obj = new Plugin_Upgrader( $skin = new Plugin_Installer_Skin_B( compact( 'type', 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
    $installed = $plugin_obj->install($plugin_file);
    $info = $plugin_obj->plugin_info();
    $old_location = rtrim(plugin_dir_path($info), '/');
    rename(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $old_location, WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $result['github-repo']);
    $info = str_replace($old_location, $result['github-repo'], $info);
    if ($installed==true)
    {
      $activated = activate_plugin($info);
      if ($activated==NULL)
      {
        echo json_encode(array('success'=>'true','plugin'=>$plugin));
        die();
      }
      else
      {
        echo json_encode(array('failed'=>esc_html__('Could not activate plugin','formcraft')));
        die();
      }
    }
    else
    {
      echo json_encode(array('failed'=>esc_html__('Could not install plugin','formcraft')));
      die();
    }
    die();
  }

  add_action('wp_ajax_formcraft3_get_stats', 'formcraft3_get_stats');
  function formcraft3_get_stats() {
    global $fc_meta, $fc_views_table, $wpdb;
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    $from = $_GET['from'];
    $to = $_GET['to'];
    $form = intval($_GET['form']);
    $from = date('Y-m-d 00:00:00', strtotime($from));
    $to = date('Y-m-d 00:00:00', strtotime('+1day', strtotime($to)));
    if ($form==0) {
      $all_data = $wpdb->get_results( $wpdb->prepare("SELECT id, form, views, submissions, payment, _date FROM $fc_views_table WHERE _date >= %s AND _date <= %s", $from, $to) , ARRAY_A);
    } else {
      $all_data = $wpdb->get_results( $wpdb->prepare("SELECT id, form, views, submissions, payment, _date FROM $fc_views_table WHERE form = %d AND _date >= %s AND _date <= %s", $form, $from, $to) , ARRAY_A);
    }
    $temp = array();
    foreach ($all_data as $key => $value) {
      if ( isset($temp[$value['_date']]) ) {
        $temp[$value['_date']]['views'] = $temp[$value['_date']]['views'] + $value['views'];
        $temp[$value['_date']]['submissions'] = $temp[$value['_date']]['submissions'] + $value['submissions'];
        $temp[$value['_date']]['payment'] = $temp[$value['_date']]['payment'] + $value['payment'];
      }
      else
      {
        $temp[$value['_date']] = $value;
      }
    }
    $all_data = $temp;

    $difference = (strtotime($to)-strtotime($from))/(60*60*24);
    $i = 0;
    $outputV = array();
    $outputS = array();
    $outputP = array();
    $labels = array();

    if ($difference > 180) {
      $denominator = $difference > 366 ? 'M Y' : 'M';
      while ($i < $difference) {
        $this_month = date($denominator, strtotime('+'.$i.'day', strtotime($from)));
        $this_date = date('Y-m-d 00:00:00', strtotime('+'.$i.'day', strtotime($from)));
        if (isset($all_data[$this_date])) {
          if (in_array(date($denominator, strtotime($this_date)), $labels)) {
            $outputV[array_search(date($denominator, strtotime($this_date)), $labels)] += intval($all_data[$this_date]['views']);
            $outputS[array_search(date($denominator, strtotime($this_date)), $labels)] += intval($all_data[$this_date]['submissions']);
            $outputP[array_search(date($denominator, strtotime($this_date)), $labels)] += intval($all_data[$this_date]['payment']);
          } else {
            $labels[] = date($denominator, strtotime($this_date));
            $outputV[] = intval($all_data[$this_date]['views']);
            $outputS[] = intval($all_data[$this_date]['submissions']);
            $outputP[] = intval($all_data[$this_date]['payment']);            
          }
        } else {
          if (in_array(date($denominator, strtotime($this_date)), $labels)) {

          } else {
            $labels[] = date($denominator, strtotime($this_date));
            $outputV[] = 0;
            $outputS[] = 0;
            $outputP[] = 0;
          }
        }
        $i++;
      }
    } else {
      while ($i < $difference) {
        $this_date = date('Y-m-d 00:00:00', strtotime('+'.$i.'day', strtotime($from)));
        if (isset($all_data[$this_date])) {
          $labels[] = date('d M', strtotime($this_date));
          $outputV[] = intval($all_data[$this_date]['views']);
          $outputS[] = intval($all_data[$this_date]['submissions']);
          $outputP[] = intval($all_data[$this_date]['payment']);
        } else {
          $labels[] = date('d M', strtotime($this_date));
          $outputV[] = 0;
          $outputS[] = 0;
          $outputP[] = 0;
        }
        $i++;
      }
    }
    $max_points = 20;
    $nos = ceil(count($outputV)/$max_points);

    if ($nos > 2000) {
      $labels = formcraft3_compress_stats($labels,$max_points, 'string');
      $outputV = formcraft3_compress_stats($outputV,$max_points, 'int');
      $outputS = formcraft3_compress_stats($outputS,$max_points, 'int');
      $outputP = formcraft3_compress_stats($outputP,$max_points, 'int');
    }
    echo json_encode(array('success'=>'true','labels'=>$labels,'views'=>$outputV,'submissions'=>$outputS,'payments'=>$outputP));
    die();
  }

  function formcraft3_compress_stats($input, $max_points, $type) {
    $x = 0;
    $nos = ceil(count($input)/$max_points);
    $newOutput = array();
    do {
      $temp1 = array();
      $temp1 = array_slice($input, $x*$nos, $nos);
      $temp2 = 0;
      foreach ($temp1 as $key => $value) {
        if ($type=='int')
        {
          $temp2 =  $value + $temp2;
        }
        else
        {
          $temp2 =  $temp1[0].' - '.$temp1[ count($temp1) -1 ];
        }
      }
      $newOutput[] = $temp2;
      $x++;
    } while ($x<$max_points);
    return $newOutput;
  }

  /* Check if the User is Visiting a Form Page */
  add_action('template_redirect', 'formcraft3_redirect_to_form_page', 1);
  function formcraft3_redirect_to_form_page() {
    global $fc_meta, $fc_forms_table, $wpdb;
    if(formcraft3_check_form_page()) {
      $form_id = formcraft3_check_form_page();
      if (formcraft3_check_form_page_access($form_id)) {
        $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $form_id);
        $query = $wpdb->get_var( $query );
        $query = \FormCraft\Security\Sanitizer::decodeDbJson($query, true);
        wp_enqueue_style('formcraft-form-page', plugins_url( 'dist/form-page.css', __FILE__ ),array(), $fc_meta['version']);
        add_action('wp_head','formcraft3_wp_head');
        echo '<!DOCTYPE html>
        <html '; ?><?php language_attributes(); ?><?php echo '>
        <head>';
          ?>
          <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
          <?php
          remove_theme_support('title-tag');
          $title = formcraft3_page_title();
          echo "<title>$title</title>";
          wp_head();
          echo '</head>';
          echo '<body class="">';
          echo '<div class="site-header"></div>';
          if ( is_user_logged_in() && isset($_GET['preview']) ) {
            echo "<div id='fc-form-preview'>".esc_html__('Form Preview', 'formcraft')."</div>";
          }
          echo "<div class='fc-form-tip-cover'>";
          if ( isset($query['emailRecipients']) && $query['emailRecipients']=='' && isset($_GET['preview']) ) {
            echo "<div class='fc-form-tip'>".esc_html__('To send form notifications to emails, add an email address to', 'formcraft')."<br><strong>".esc_html__('Options → Email → Email Notifications → Send Email(s) To', 'formcraft')."</strong></div>";
          }
          echo "</div>";
          echo '<div class="dedicated-page"><div class="sticky_header"></div>';
          echo '<div id="notification-panel"></div>';
          echo do_shortcode("[fc id='$form_id' align='center'][/fc]");
          echo "</div>";
          wp_footer();
          echo '</body>';
          die();
        }
      }
    }
    function formcraft3_page_title() {
      global $fc_meta, $fc_forms_table, $wpdb;
      $url = explode('/',str_ireplace('?preview=true', '', $_SERVER["REQUEST_URI"]));
      $form_id = intval($url[(count($url)-1)]);
      $query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $form_id);
      $query = $wpdb->get_var($query);
      return $query.' - '.get_bloginfo('name');
    }

    function formcraft3_wp_head()
    {
      global $fc_meta, $fc_forms_table, $wpdb;
      $url = explode('/',str_ireplace('?preview=true', '', $_SERVER["REQUEST_URI"]));
      $form_id = intval($url[ (count($url)-1) ]);
      $query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $form_id);
      $query = $wpdb->get_var($query);
      echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    }

    function formcraft3_check_form_page()
    {
      global $fc_meta, $fc_forms_table, $wpdb;
      $temp_1 = str_replace('&', '/', $_SERVER["REQUEST_URI"]);
      $existing = explode('/', get_site_url());
      $url = explode('/',str_ireplace('?preview=true', '', $temp_1));
      $url = array_filter($url);
      foreach ($url as $key => $value) {
        if(in_array($value,$existing))
        {
          unset($url[$key]);
        }
      }
      $url = array_values($url);
      if ( !isset($url[0]) || !isset($url[1]) ) {
        return false;
      }
      $last_one = explode("?", $url[1]);
      if ( $url[0]=='form-view' && ctype_digit($last_one[0]) )
      {
        return intval($last_one[0]);
      }
      else
      {
        return false;
      }
    }
    /* Check if current requester is allowed form page access */
    function formcraft3_check_form_page_access($form_id)
    {
      global $fc_meta, $fc_forms_table, $wpdb;
      $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $form_id);
      $query = $wpdb->get_var($query);
      $query = json_decode(stripslashes($query), 1);
      if (isset($query['config']) && isset($query['config']['disable_form_link']) && $query['config']['disable_form_link']==true) {
        if (is_user_logged_in()) {
          if (isset($_GET['preview']) && $_GET['preview']==true) {
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return true;
      }
    }

    /* Enqueue Styles on Front End Pages, Header */
    add_action( 'wp_enqueue_scripts', 'formcraft3_form_styles' );
    function formcraft3_form_styles()
    {
      global $fc_meta, $wpdb;
      $form_id = formcraft3_check_form_page();
      if($form_id) {
        if(formcraft3_check_form_page_access($form_id)) {
          status_header( 200 );
        }
      }
      wp_enqueue_style('formcraft-common', plugins_url('dist/formcraft-common.css', __FILE__), array(), $fc_meta['version']);      
      wp_enqueue_style('formcraft-form', plugins_url( 'dist/form.css', __FILE__ ),array(), $fc_meta['version']);
    }
    add_action( 'admin_enqueue_scripts', 'formcraft3_admin_scripts' );
    function formcraft3_admin_scripts() {
      global $fc_meta, $wpdb, $fc_forms_table, $wp_version, $fc_translate;
      $screen = get_current_screen();
      if (version_compare($wp_version, '5.0.0') >= 0) {
        wp_enqueue_style('fc-icon', plugins_url( 'assets/formcraft-icon.css', __FILE__ ), array(), $fc_meta['version']);
      }
      if (function_exists('register_block_type') && function_exists('has_blocks') && $screen->base === 'post') {
        wp_enqueue_style('fc-block', plugins_url( 'assets/formcraft-block.css', __FILE__ ), array(), $fc_meta['version']);      
        wp_enqueue_script('fc-block', plugins_url( 'dist/formcraft-admin.js', __FILE__ ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), $fc_meta['version']);
        $forms = $wpdb->get_results( "SELECT id, name, modified FROM $fc_forms_table LIMIT 0, 999", ARRAY_A );
        wp_localize_script( 'fc-block', 'FormCraftGlobal', 
          array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'forms' => $forms,
            'fct' => $fc_translate
          )
        );
      }
    }    

    /* Custom Add Form Button for the WP Editor */
    add_action( 'media_buttons', 'formcraft3_custom_button');
    function formcraft3_custom_button( ) {
      global $fc_meta, $wpdb;
      if ( !current_user_can('edit_posts') || !current_user_can('edit_pages') ) { return; }
      $button = '<a id="fc_afb" class="button" title="'.esc_attr__('Insert FormCraft Form','formcraft').'" data-target="#fc_add_form_modal" data-toggle="fc_modal"><img width="12" src="'.plugins_url( 'assets/images/plus.png', __FILE__ ).'"/>' .esc_html__( 'Add Form', 'formcraft' ). '</a>';
      add_action('admin_footer','formcraft3_add_modal');
      wp_enqueue_script('fc-modal', plugins_url( 'assets/js/src/fc_modal.js', __FILE__ ), array(), $fc_meta['version']);
      wp_enqueue_script('fc-add-form-button', plugins_url( 'assets/js/src/add-form-button.js', __FILE__ ));
      wp_enqueue_style('fc-add-form-button', plugins_url( 'dist/add-form-button.css', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_style('formcraft-common', plugins_url('dist/formcraft-common.css', __FILE__), array(), $fc_meta['version']);
      echo $button;
    }
    function formcraft3_add_modal() {
      global $fc_meta, $fc_forms_table, $wpdb;
      $forms = $wpdb->get_results( "SELECT id, name FROM $fc_forms_table", ARRAY_A );
      echo '<div class="fc_modal formcraft-css fc_fade" id="fc_add_form_modal"><form class="fc_modal-dialog"><div class="fc_modal-content">';
      echo '<div class="fc_modal-header">FormCraft<button class="fc_close" type="button" class="close" data-dismiss="fc_modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
      echo '<div class="fc_modal-body">';
      if ( count($forms)!=0 )
      {
        echo "<div class='fc-modal-head'>".esc_html__('Select Form','formcraft')."</div>";
        foreach ($forms as $key => $value) {
          if ( $value['name']=='' ) { continue; }
          echo "<label class='select-form'><input ".($key==0?"checked ":"")."type='radio' value='".esc_attr__($value['id'])."' name='fc_form_id'/>".esc_html__($value['name'])."</label>";
        }

        echo "<br><div class='fc-modal-head'>".esc_html__('Select Embed Type','formcraft')."</div>";
        echo "<label class='select-alignment'><input checked type='radio' value='inline' name='fc_form_type'/>".esc_html__('Inline Form','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='popup' name='fc_form_type'/>".esc_html__('Popup Form','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='slide' name='fc_form_type'/>".esc_html__('Slide In Form','formcraft')."</label>";

        echo "<br><div id='fc_form_type_inline'><div class='fc-modal-head'>".esc_html__('Select Alignment','formcraft')."</div>";
        echo "<label class='select-alignment'><input checked type='radio' value='left' name='fc_form_align'/>".esc_html__('Left','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='center' name='fc_form_align'/>".esc_html__('Center','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='right' name='fc_form_align'/>".esc_html__('Right','formcraft')."</label><br></div>";

        echo "<div id='fc_form_type_popup'><div class='fc-modal-head'>".esc_html__('Select Button Placement','formcraft')."</div>";
        echo "<label class='select-alignment'><input checked type='radio' value='left' name='fc_form_btn_align'/>".esc_html__('Left','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='inline' name='fc_form_btn_align'/>".esc_html__('Inline','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='right' name='fc_form_btn_align'/>".esc_html__('Right','formcraft')."</label><br></div>";

        echo "<div id='fc_form_type_slide'><div class='fc-modal-head'>".esc_html__('Select Button Placement','formcraft')."</div>";
        echo "<label class='select-alignment'><input checked type='radio' value='left' name='fc_form_btn_align'/>".esc_html__('Left','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='right' name='fc_form_btn_align'/>".esc_html__('Right','formcraft')."</label>";
        echo "<label class='select-alignment'><input type='radio' value='bottom-right' name='fc_form_btn_align'/>".esc_html__('Bottom Right','formcraft')."</label><br></div>";

        echo "<input id='fc_button_text' type='text' placeholder='".esc_html__('Button Text / Image URL','formcraft')."'/>";
      }
      else
      {
        echo "<center>".esc_html__("You have no forms","formcraft3")."</center>";
      }
      echo '</div>';
      if ( count($forms)!=0 )
      {
        echo '<div class="fc_modal-footer"><button type="submit" class="button" id="fc_add_form_to_editor">'.esc_html__('Add Form','formcraft').'</button></div>';
      }
      echo '</div></form></div>';
    }


    add_action('wp_ajax_formcraft3_trigger_view', 'formcraft3_trigger_view');
    add_action('wp_ajax_nopriv_formcraft3_trigger_view', 'formcraft3_trigger_view');
    function formcraft3_trigger_view() {
      if ( !isset($_GET['id']) || !ctype_digit($_GET['id']) ) {
        return false;
      }
      formcraft3_new_view($_GET['id']);
    }

    /* Register a Form View */
    function formcraft3_new_view($form_id) {
      global $fc_meta, $fc_views_table, $wpdb;
      if (get_site_option('f3_disable_analytics')) {
        return false;
      }
      if (!strpos($_SERVER["REQUEST_URI"], '?preview=true') && ctype_digit($form_id)) {
        $time = date('Y-m-d 00:00:00', time() + formcraft3_offset());
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $fc_views_table WHERE _date = %s AND form = %d", $time, $form_id);
        if ($wpdb->get_var($query)) {
          $query = $wpdb->prepare("SELECT views FROM $fc_views_table WHERE _date = %s AND form = %d", $time, $form_id);
          $existing = $wpdb->get_var($query);
          $wpdb->update($fc_views_table, array( 'views' => $existing + 1 ), array('form'=>$form_id, '_date'=>$time));
        } else {
          $rows_affected = $wpdb->insert( $fc_views_table, array(
            'form' => $form_id,
            'views' => 1,
            '_date' => $time
          ));
        }
      }
    }

    /* Register a Form Submission */
    function formcraft3_new_submission($form_id, $payment) {
      global $fc_meta, $fc_forms_table, $fc_views_table, $wpdb;
      if ( !strpos($_SERVER["REQUEST_URI"], '?preview=true') && ctype_digit($form_id)) {
        setcookie("fc_sb_".$form_id, true, time() + (10 * 365 * 24 * 60 * 60), '/');
      }      
      if (get_site_option('f3_disable_analytics')) {
        return false;
      }      
      if ( !strpos($_SERVER["REQUEST_URI"], '?preview=true') && ctype_digit($form_id)) {
        $time = date('Y-m-d 00:00:00', time() + formcraft3_offset());
        $query = $wpdb->prepare("SELECT counter FROM $fc_forms_table WHERE id = %d", $form_id);
        $existing = $wpdb->get_var($query);
        $wpdb->update($fc_forms_table, array( 'counter' => $existing + 1 ), array('id'=>$form_id));
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $fc_views_table WHERE _date = %s AND form = %d", $time, $form_id);
        if ($wpdb->get_var($query)) {
          $query = $wpdb->prepare("SELECT payment, submissions FROM $fc_views_table WHERE _date = %s AND form = %d", $time, $form_id);
          $query = $wpdb->get_row($query, ARRAY_A);
          $rows_affected = $wpdb->update($fc_views_table, array(
            'submissions' => intval($query['submissions']) + 1,
            'payment' => intval($query['payment']) + $payment
          ), array('form'=>$form_id, '_date'=>$time));
        } else {
          $rows_affected = $wpdb->insert( $fc_views_table, array(
            'form' => $form_id,
            'submissions' => 1,
            'payment' => $payment,
            '_date' => $time
            ) );
        }
        /* Check if we need to disable form */
        $existing++;
        $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $form_id);
        $meta = $wpdb->get_var($query);
        $meta = json_decode(stripslashes($meta),1);
        if ( isset($meta['config']['disable_after']) && isset($meta['config']['disable_after_nos']) && $meta['config']['disable_after']==true && ctype_digit($meta['config']['disable_after_nos']) ) {
          if ($meta['config']['disable_after_nos']==$existing) {
            $meta['config']['form_disable']=true;
            $meta = esc_sql(json_encode($meta));
            $wpdb->update($fc_forms_table, array( 'meta_builder' => $meta ), array('id'=>$form_id));
          }
        }
      }
    }


    /* Create a Custom Title for the Form Page */
    function formcraft3_modify_title($title, $sep) {
      global $fc_meta, $fc_forms_table, $wpdb;
      $url = explode('/',str_ireplace('?preview=true', '', $_SERVER["REQUEST_URI"]));
      $form_id = $url[ (count($url)-1) ];
      $query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $form_id);
      $query = $wpdb->get_var($query);
      return $sep." ".$query;
    }

    /* Enqueue Scripts / Styles if the user is visiting the Form Page */
    add_action('init','formcraft3_check');
    function formcraft3_check()
    {
      global $fc_meta, $fc_forms_table, $fc_submissions_table, $fc_views_table, $wpdb, $fc_files_table;
      do_action('formcraft_addon_init');

      if ( isset($_GET['page']) && $_GET['page']=='formcraft-dashboard' && isset($_GET['id']) )
      {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
      }

      if (is_user_logged_in() && isset($_GET['formcraft3_download_file']) )
      {
        $value = esc_sql($_GET['formcraft3_download_file']);
        $query = $wpdb->prepare("SELECT mime, name, file_path FROM $fc_files_table WHERE uniq_key = %s", $value);
        $file = $wpdb->get_row($query, ARRAY_A);
        header('Content-Type: '.$file['mime']);
        header('Content-Transfer-Encoding: Binary');
        header('Cache-Control: must-revalidate');
        header('Content-disposition: attachment; filename="'.$file['name'].'"');
        readfile($file['file_path']);
        die();
      }

      if ( is_user_logged_in() && current_user_can($fc_meta['user_can']) && isset($_GET['formcraft3_export_all']) ) {

        echo "DONE";
        
        $forms = $wpdb->get_results( "SELECT id, name, builder, counter FROM $fc_forms_table", ARRAY_A );

        $result = [];

        if ( empty($forms) ) {
          echo "No data to export";
          die();
        }

        foreach ($forms as $form) {

          $result[$form['id']] = $form;

          $query = $wpdb->prepare("SELECT id, content, created, visitor FROM $fc_submissions_table WHERE form = %d LIMIT %d, %d", $form['id'], 0, 100000);
          $entries = $wpdb->get_results( $query, ARRAY_A );
          $result[$form['id']]['submissions'] = empty($entries) ? null : $entries;

          $query = $wpdb->prepare("SELECT id, uniq_key, name, created, file_url FROM $fc_files_table WHERE form = %d LIMIT %d, %d", $form['id'], 0, 100000);
          $entries = $wpdb->get_results( $query, ARRAY_A );    
          $result[$form['id']]['files'] = empty($entries) ? null : $entries;

          $query = $wpdb->prepare("SELECT id, views, submissions, payment, _date FROM $fc_views_table WHERE form = %d LIMIT %d, %d", $form['id'], 0, 500000);
          $entries = $wpdb->get_results( $query, ARRAY_A );    
          $result[$form['id']]['views'] = empty($entries) ? null : $entries;        

        }

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="formcraft_export_all.txt"');
        header("Pragma: no-cache");
        header("Expires: 0");
        print json_encode($result);
        die();
      }      

      if (is_user_logged_in() && current_user_can($fc_meta['user_can']) && isset($_GET['formcraft3_export_form']) && ctype_digit($_GET['formcraft3_export_form']) ) {
        if ( !current_user_can($fc_meta['user_can']) ) { die(); }
        $form_id = $_GET['formcraft3_export_form'];
        $query = $wpdb->prepare("SELECT id, html, addons, builder, meta_builder, name FROM $fc_forms_table WHERE id = %d", $form_id);
        $data = $wpdb->get_row( $query, ARRAY_A );
        $result = array();
        $result['plugin'] = 'FormCraft';
        $result['created'] = strtotime('now');
        $result['html'] = $data['html'];
        $result['addons'] = stripslashes($data['addons']);
        $result['builder'] = stripslashes($data['builder']);
        $result['meta_builder'] = stripslashes($data['meta_builder']);
        $result['old_url'] = site_url();
        $result = json_encode($result);

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="'.$data['name'].'.txt"');
        header("Pragma: no-cache");
        header("Expires: 0");

        print $result;
        die();
      }

      if (is_user_logged_in() && current_user_can($fc_meta['user_can']) && isset($_GET['formcraft_export_entries'])) {
        if (!current_user_can($fc_meta['user_can'])) {
          die();
        }
        $exportFormID = $_GET['formcraft_export_entries'];
        if ( $exportFormID === 0 || !ctype_digit($exportFormID) ) {
          echo 'Invalid form ID';
          die();
        }
        $output = array();
        $output[0][] = 'Entry ID';
        $i = 1;

        $exportFrom = isset($_GET['from']) && ctype_digit($_GET['from']) ? $_GET['from'] : 0;
        $exportTo = isset($_GET['to']) && ctype_digit($_GET['to']) ? $_GET['to'] : 20000;
        $exportTo = $exportTo - $exportFrom;

        $query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $exportFormID);
        $form_name = $wpdb->get_var($query);
        $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id = %d", $exportFormID);        
        $meta = $wpdb->get_var($query);
        if ($meta == NULL) {
          echo "Form does not exist";
          die();
        }
        $meta = \FormCraft\Security\Sanitizer::decodeDbJson($meta, true);
        $meta = $meta['fields'];
        $query = $wpdb->prepare("SELECT id, content, created FROM $fc_submissions_table WHERE form = %d LIMIT %d, %d", $exportFormID, $exportFrom, $exportTo);
        $entries = $wpdb->get_results( $query, ARRAY_A );

        if (count($entries)==0) {
          echo "No entries to export";
          die();
        }

        foreach ($meta as $key2 => $value2) {
          if ($value2['type']=='submit') {
            continue;
          }
          $output[0][] = isset($value2['elementDefaults']['main_label']) ? $value2['elementDefaults']['main_label'] : '...';
        }
        $output[0][] = 'Created';
        foreach ($entries as $key => $entry) {
          $content = \FormCraft\Security\Sanitizer::decodeDbJson($entry['content'], true);
          $new_content = array();
          foreach ($content as $key2 => $value2) {
            $new_content[$value2['identifier']] = $value2['type']=='fileupload' ? $value2['url'] : $value2['value'];
          }
          $output[$i][] = $entry['id'];
          foreach ($meta as $key2 => $value2) {
            if ($value2['type']=='submit') {
              continue;
            }
            $output[$i][] = isset($new_content[$value2['identifier']]) ? $new_content[$value2['identifier']] : '';
          }
          $created_date = get_date_from_gmt(date('Y-m-d H:i:s', $entry['created']), get_option('date_format'));
          $created_time = get_date_from_gmt(date('Y-m-d H:i:s', $entry['created']), get_option('time_format'));          
          $output[$i][] = $created_date . ' ' . $created_time;
          $i++;
        }

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=".urlencode($form_name).".csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        if ( isset($_GET['sep']) ) {
          echo "sep=".esc_html__($_GET['sep'])."\n";
        }
        formcraft3_prepare_csv($output, esc_html__($_GET['separator']));
        die();
      }
    }

    function formcraft3_global_js_variable($variable, $content = '') {
      global $footerVariables;
      if (is_array($footerVariables) && count($footerVariables)!=0) {
        foreach ($footerVariables as $key => $value) {
          if ( !empty($value['var']) && !empty($value['data']) && empty($_GET['type']) ) {
            $js_data = 'var '.esc_html__($value['var']).' = '. $value['data'] .';';
            echo "<script type='text/javascript'>\n";
            echo "/* <![CDATA[ */\n";
            echo $js_data;
            echo "\n/* ]]> */\n";
            echo "</script>\n";
          }
        }
      }
    }
    add_action('wp_footer', 'formcraft3_global_js_variable', 20);

    function formcraft3_global_js_footer() {
      global $FormCraftFooterJS;
      $FormCraftFooterJS = isset($FormCraftFooterJS) && is_array($FormCraftFooterJS) ? implode("\r\n", $FormCraftFooterJS) : '';
      ?>
    <script>
    jQuery(document).ready(function() {
      <?php
      echo $FormCraftFooterJS."\r\n";
      ?>
    });
    </script>
    <?php
  }
  add_action('wp_footer', 'formcraft3_global_js_footer', 999999);

  function formcraft3_shortcode( $atts, $content = '' ) {
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
      wp_enqueue_script('fileupload', plugins_url( 'assets/js/vendor/jquery.fileupload.js', __FILE__ ),array('jquery-ui-widget'));
      wp_localize_script( 'fileupload', 'FC_f',
        array(
          'ajaxurl' => admin_url( 'admin-ajax.php' )
          )
        );
    }
    if ($load_address) {
      wp_enqueue_script('typeahead', plugins_url( 'assets/js/vendor/typeahead.min.js', __FILE__ ), array('jquery'), $fc_meta['version']);
      wp_enqueue_script('typeahead-address', plugins_url( 'assets/js/vendor/typeahead-addresspicker.min.js', __FILE__ ), array('jquery'), $fc_meta['version']);
      wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key='.$load_address, array('jquery'));
    }
    wp_enqueue_script('fc-modal', plugins_url( 'assets/js/src/fc_modal.js', __FILE__ ), array('jquery'), $fc_meta['version']);
    wp_enqueue_script('tooltip', plugins_url( 'assets/js/vendor/tooltip.min.js', __FILE__ ), array('jquery', 'fc-modal'));
    wp_enqueue_script('awesomplete', plugins_url('lib/awesomplete.min.js', __FILE__ ));
    wp_enqueue_script('fc-form', plugins_url( 'dist/form.min.js', __FILE__ ), $dependencies, $fc_meta['version']);

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
          'datepickerLang' => plugins_url( 'assets/js/datepicker-lang/', __FILE__ )
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
      wp_enqueue_script('fc-modal', plugins_url( 'assets/js/src/fc_modal.js', __FILE__ ), array(), $fc_meta['version']);
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
  add_shortcode( 'fc', 'formcraft3_shortcode' );

  function add_formcraft_form($shortcode) {
    echo do_shortcode($shortcode);
  }

  /*
  Create New Form Function
  */
  add_action( 'wp_ajax_formcraft3_new_form', 'formcraft3_new_form' );
  function formcraft3_new_form() {
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

  /*
  Load Form Data in the Form Editor Mode
  */
  add_action( 'wp_ajax_formcraft3_load_form_data', 'formcraft3_load_form_data' );
  function formcraft3_load_form_data() {
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

  /* Delete Submissions */
  add_action( 'wp_ajax_formcraft3_delete_entries', 'formcraft3_delete_entries' );
  function formcraft3_delete_entries() {
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

  add_action( 'wp_ajax_formcraft3_get_insights', 'formcraft3_get_insights' );
  function formcraft3_get_insights() {
    global $fc_meta, $fc_forms_table, $fc_submissions_table, $wpdb;
    $formID = $_GET['form'];
    $nonce = $_REQUEST['formcraft3_wpnonce'];    
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    if (!ctype_digit($formID)) {
      die();
    }
    $query = $wpdb->prepare("SELECT meta_builder FROM $fc_forms_table WHERE id=%d", $formID);
    $currentForm = $wpdb->get_var( $query );
    $currentForm = \FormCraft\Security\Sanitizer::decodeDbJson($currentForm, true);
    $chart = array();
    foreach ($currentForm as $pageNos => $formPage) {
      foreach ($formPage as $key => $value) {
        if ( empty($value['type']) ) {
          continue;
        }
        if ( $value['type'] == 'checkbox' || $value['type'] == 'dropdown' || $value['type'] == 'datepicker' || $value['type'] == 'slider' || $value['type'] == 'star' || $value['type'] == 'thumb'  || $value['type'] == 'timepicker' ) {
          $chart[$value['elementDefaults']['identifier']]['label'] = $value['elementDefaults']['main_label'];
          $chart[$value['elementDefaults']['identifier']]['totalAnalyzed'] = 0;
          $chart[$value['elementDefaults']['identifier']]['labels'][] = '(empty)';
          $chart[$value['elementDefaults']['identifier']]['labelsAlt'][] = '(empty)';
          $chart[$value['elementDefaults']['identifier']]['data'][] = 0;
          if (isset($value['elementDefaults']['optionsListShow'])) {
            foreach ($value['elementDefaults']['optionsListShow'] as $k => $v) {
              if (trim($v['value'])=='') { continue; }
              $chart[$value['elementDefaults']['identifier']]['labels'][] = $v['value'];
              $chart[$value['elementDefaults']['identifier']]['labelsAlt'][] = $v['show'] == $v['value'] ? $v['value'] : $v['show']." / ".$v['value'];
              $chart[$value['elementDefaults']['identifier']]['data'][] = 0;
            }
          }
        }
      }
    }
    $maxEntries = intval($_GET['maxEntries']) < 1 || intval($_GET['maxEntries']) > 10000 ? 100 : intval($_GET['maxEntries']);
    $dateFrom = isset($_GET['period-from']) ? strtotime($_GET['period-from']) : 0;
    $dateTo = isset($_GET['period-to']) ? strtotime($_GET['period-to']) : strtotime('+1day');
    $current = 0;
    $size = min($maxEntries, 50);
    $i = 1;
    while ($current < $maxEntries) {
      $query = $wpdb->prepare("SELECT content FROM $fc_submissions_table WHERE form=%d AND created > %s AND created <= %s LIMIT %d OFFSET %d", $formID, $dateFrom, $dateTo, $size, $current);
      $entries = $wpdb->get_results( $query, ARRAY_A );
      $current = $current + $size;
      foreach ($entries as $entryKey => $value) {
        $entry = \FormCraft\Security\Sanitizer::decodeDbJson($value['content'], true);
        foreach ($entry as $fieldKey => $field) {
          if ( isset($chart[$field['identifier']]) ) {
            $chart[$field['identifier']]['totalAnalyzed']++;
            if ( !is_array($field['value']) && trim($field['value']) == '' ) {
              $field['value'] = array('(empty)');
            }
            if ( !is_array($field['value']) ) {
              $field['value'] = array($field['value']);
            }
            foreach ($field['value'] as $label) {
              if ( in_array($label, $chart[$field['identifier']]['labels']) ) {
                $chart[$field['identifier']]['data'][array_search($label, $chart[$field['identifier']]['labels'])]++;
              } else {
                $chart[$field['identifier']]['labels'][] = $label;
                $chart[$field['identifier']]['labelsAlt'][] = $label;
                $chart[$field['identifier']]['data'][] = 1;
              }
            }
          }
        }
      }
    }
    foreach ($chart as $chartKey => $thisChart) {
      if ($thisChart['data'][0] == 0) {
        array_shift($chart[$chartKey]['labels']);
        array_shift($chart[$chartKey]['data']);
        array_shift($chart[$chartKey]['labelsAlt']);
      }
      if ($thisChart['totalAnalyzed'] === 0) {
        unset($chart[$chartKey]);
      }
    }
    echo json_encode(array('success' => true, 'charts' => $chart));
    // echo '{"success":true,"charts":{"field18":{"label":"Rate Your Experience With Us","totalAnalyzed":185,"labels":["(empty)","1","2","3","4","5"],"labelsAlt":["(empty)","Bad \/ 1","Could be better \/ 2","So so \/ 3","Good \/ 4","Excellent! \/ 5"],"data":[12,3,20,27,62,47]}}}';
    die();
  }

  add_action( 'wp_ajax_formcraft3_toggle_analytics', 'formcraft3_toggle_analytics' );
  function formcraft3_toggle_analytics() {
    global $fc_meta, $wpdb;
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    if ($_GET['disable']=='true') {
      update_site_option('f3_disable_analytics', true);
    } else {
      update_site_option('f3_disable_analytics', false);
    }
    die();
  }

  add_action( 'wp_ajax_formcraft3_toggle_keep_data', 'formcraft3_toggle_keep_data' );
  function formcraft3_toggle_keep_data() {
    global $fc_meta, $wpdb;
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }
    if ($_GET['keep'] == 'true') {
      update_site_option('f3_keep_data', true);
      echo json_encode(array('keepData' => true));
    } else {
      update_site_option('f3_keep_data', false);
      echo json_encode(array('keepData' => false));
    }
    die();
  }

  add_action( 'wp_ajax_formcraft3_reset_analytics', 'formcraft3_reset_analytics' );
  function formcraft3_reset_analytics() {
    global $fc_meta, $fc_views_table, $wpdb;
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    if ( $fc_meta['preview_mode'] == true ) {
      echo json_encode(array('failed'=>esc_html__('Cannot reset data in demo mode', 'formcraft'))); die();
    }
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $done = $wpdb->query("TRUNCATE TABLE $fc_views_table");
    echo json_encode(array('success'=>esc_html__('Data reset','formcraft')));
    die();
  }

  /* Delete Form */
  add_action( 'wp_ajax_formcraft3_delete_form', 'formcraft3_delete_form' );
  function formcraft3_delete_form() {
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

  add_action( 'wp_ajax_formcraft3_get_forms', 'formcraft3_get_forms' );
  function formcraft3_get_forms() {
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

  add_action( 'wp_ajax_formcraft3_get_files', 'formcraft3_get_files' );
  function formcraft3_get_files() {
    global $fc_meta, $fc_files_table, $wpdb;
    $nonce = $_REQUEST['formcraft3_wpnonce'];    
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }
    $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? $_GET['page']-1 : 0;
    $whichForm = isset($_GET['whichForm']) && ctype_digit($_GET['whichForm']) ? $_GET['whichForm'] : 0;
    $per_page = isset($_GET['max']) && ctype_digit($_GET['max']) ? $_GET['max'] : 12;
    $from = $page * $per_page;
    $to = $per_page;
    $formQuery = '';

    if ( isset($_GET['query']) && $_GET['query'] != '' ) {
      $formQuery = $whichForm != 0 ? $wpdb->prepare("AND form = %d", $whichForm) : '';
      $total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $fc_files_table WHERE (name LIKE %s or mime LIKE %s) $formQuery", "%$_GET[query]%", "%$_GET[query]%") );
      $query = $wpdb->prepare( "SELECT id,name,mime,size,file_url,created,uniq_key FROM $fc_files_table WHERE (name LIKE %s or mime LIKE %s) $formQuery ORDER BY id DESC LIMIT %d, %d", "%$_GET[query]%", "%$_GET[query]%", $from, $to);
      $files = $wpdb->get_results( $query, ARRAY_A );
    } else {
      $formQuery = $whichForm != 0 ? $wpdb->prepare("WHERE form = %d", $whichForm) : '';
      $total = $wpdb->get_var( "SELECT COUNT(*) FROM $fc_files_table $formQuery" );
      $files = $wpdb->get_results( "SELECT id,name,mime,size,file_url,created,uniq_key FROM $fc_files_table $formQuery ORDER BY id DESC LIMIT $from, $to", ARRAY_A );
    }

    if ( is_array($files) && count($files) > 0 ) {
      echo json_encode(array('pages'=>ceil($total/$per_page),'files'=>$files,'total'=>$total));
      die();
    } else {
      echo json_encode(array('pages'=>ceil($total/$per_page),'total'=>'0'));
      die();
    }
  }

  /* Get List of Submissions */
  add_action( 'wp_ajax_formcraft3_get_entries', 'formcraft3_get_entries' );
  function formcraft3_get_entries() {
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

  /* Get Submission Content */
  add_action( 'wp_ajax_formcraft3_get_entry_content', 'formcraft3_get_entry_content' );
  function formcraft3_get_entry_content() {
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

  /* Update Submission Content */
  add_action( 'wp_ajax_formcraft3_update_entry_content', 'formcraft3_update_entry_content' );
  function formcraft3_update_entry_content() {
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

  add_action('wp_ajax_formcraft3_get_template', 'formcraft3_get_template');
  function formcraft3_get_template() {
    $file_path = WP_PLUGIN_DIR.$_GET['path'];
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce( $nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    if (!is_readable($file_path)) {
      echo json_encode(array('html'=> "<div>".esc_html__('Could not read template file', 'formcraft')."</div>"));
      die();
    }
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php'); 
    $FSD = new WP_Filesystem_Direct(false);    
    $content = $FSD->get_contents($file_path);
    $content = json_decode($content);
    $meta = json_decode($content->meta_builder, 1);
    if (!isset($content->html)) {
      echo json_encode(array('html'=> "<div>".esc_html__('Could not read template file', 'formcraft')."</div>"));
    } else {
      $html = wp_unslash($content->html);
      if ( substr($html,0,10) == 'rawdeflate' ) {
        $html = gzinflate(base64_decode(rawurldecode(substr($html,11))),0);
      }      
      $html = str_replace($content->old_url, site_url(), $html);
      if ( !empty($meta['config']['Custom_CSS']) ) {
        $html .= "<style type='text/css' scoped='scoped'>".$meta['config']['Custom_CSS']."</style>";
      }
      echo json_encode(array('html'=>$html, 'config'=>json_decode($content->meta_builder)));
    }
    die();
  }

  /*
  Save Form Progress
  */
  add_action('wp_ajax_formcraft3_save_form_progress', 'formcraft3_save_form_progress');
  add_action('wp_ajax_nopriv_formcraft3_save_form_progress', 'formcraft3_save_form_progress');
  function formcraft3_save_form_progress() {
    global $wpdb, $fc_progress_table;
    if ( !isset($_POST['id']) || !ctype_digit($_POST['id']) ) {
      die();
    }
    $deleteOld = $wpdb->query("DELETE FROM $fc_progress_table WHERE to_delete < ".strtotime('now'));
    $id = $_POST['id'];
    $max_fields = 200;
    $i = 1;
    foreach ($_POST as $key => $value) {
      if ($i > 200) {
        break;
      }
      $i++;
      if (substr($key, 0,5) != 'field') {
          continue;
      }
      $content[$key] = stripslashes_deep($value);
    }
    if ( isset($_COOKIE["fc_sp_$id"]) ) {
      $cookie = preg_replace("/\W|_/", "", $_COOKIE["fc_sp_$id"]);
      if ($wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $fc_progress_table WHERE uniq_key = %s", $cookie) )!=0) {
        $wpdb->update($fc_progress_table, array(
          'content' => esc_sql(json_encode($content)),
          'modified' => strtotime('now'),
          'to_delete' => strtotime('+60day', strtotime('now'))
          ), array('uniq_key'=>$cookie));
      }
    } else {
      $uniq = uniqid('fc');
      setcookie("fc_sp_$id", $uniq, time() + (365 * 24 * 60 * 60), '/');
      $rows_affected = $wpdb->insert( $fc_progress_table, array(
        'form' => $id,
        'uniq_key' => $uniq,
        'content' => esc_sql(json_encode($content)),
        'created' => strtotime('now'),
        'modified' => strtotime('now'),
        'to_delete' => strtotime('+60day', strtotime('now'))
        ) );
    }
    die();
  }

  add_action('wp_ajax_formcraft3_test_email', 'formcraft3_test_email');
  function formcraft3_test_email() {

    global $fc_meta;
    $postData = json_decode(file_get_contents('php://input'), 1);

    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce($nonce, 'formcraft3_wpnonce')) {
      exit;
    }    

    if ( empty($postData['emails']) ) {
      echo json_encode(array('failed' => esc_html__('No email specified', 'formcraft')));
      die();
    }
    $emails = $postData['emails'];
    $config = rawurldecode($postData['config']);
    $config = json_decode(gzinflate(base64_decode(substr($config,11)),0), 1);
    if ( strpos($emails, ',') != -1 ) {
      $emails = explode(',', $postData['emails']);
    } else {
      $emails = array($emails);
    }
    $sent = 0;
    $failed = 0;
    $from_name = isset($config['general_sender_name']) ? $config['general_sender_name'] : 'FormCraft';
    $from_email = isset($config['general_sender_email']) ? $config['general_sender_email'] : get_bloginfo('admin_email');
    $from_email = is_email_fc($from_email) == false ? get_bloginfo('admin_email') : $from_email;

    if (version_compare(get_bloginfo('version'), '5.5') >= 0) {
        require_once(ABSPATH . 'wp-includes/PHPMailer/PHPMailer.php');
        require_once(ABSPATH . 'wp-includes/PHPMailer/SMTP.php');
        require_once(ABSPATH . 'wp-includes/PHPMailer/Exception.php');
        $mail = new PHPMailer\PHPMailer\PHPMailer();
    } else {
        require_once(ABSPATH . 'wp-includes/class-phpmailer.php');
        $mail = new PHPMailer;
    }
    foreach ($emails as $key => $email) {
      if ( is_email_fc($email) == false ) {
        echo json_encode(array('failed' => esc_html__('Invalid e-mail', 'formcraft')));
        die();
      }

      if ( isset($config['_method']) && $config['_method']=='smtp' )
      {
        $mail->isSMTP();
        $mail->SMTPDebug = 1;
        $mail->Debugoutput = 'html';
        $mail->Host = $config['smtp_sender_host'];
        if (!empty($config['smtp_sender_port'])) { $mail->SMTPAuth = true; }
        if (!empty($config['smtp_sender_username'])) { $mail->Username = $config['smtp_sender_username']; }
        if (!empty($config['smtp_sender_password'])) { $mail->Password = $config['smtp_sender_password']; }
        if (!empty($config['smtp_sender_security'])) { $mail->SMTPSecure = $config['smtp_sender_security']; }
        if (!empty($config['smtp_sender_port'])) { $mail->Port = $config['smtp_sender_port']; }

        $mail->From = $from_email;
        $mail->FromName = "=?UTF-8?B?".base64_encode($from_name)."?=";
        $mail->addAddress($email);
        $mail->isHTML(true);

        $mail->Subject = esc_html__("Test Email from FormCraft", 'formcraft');
        $mail->Body = wp_kses(__("Hey,<br><br>This is a test email sent from FormCraft, for WordPress. If you have received this email, it means your settings are working correctly.", 'formcraft'), $fc_meta['basic_html']);
        $mail->AltBody = esc_html__("Hey,\nThis is a test email sent from FormCraft, for WordPress. If you have received this email, it means your settings are working correctly.", 'formcraft');
        ob_start();
        if (!$mail->send()) {      
          $output = ob_get_clean();
          $failed_msg = $mail->ErrorInfo;
          echo json_encode(array('failed' => $failed_msg, 'debug' => $output));
          die();
        } else {
          $output = ob_get_clean();
          $sent++;
        }
      }
      else
      {
        $subject = esc_html__("Test Email from FormCraft", 'formcraft');
        $message = wp_kses(__("Hey,<br><br>This is a test email sent from FormCraft, for WordPress. If you have received this email, it means your settings are working correctly.", 'formcraft'), $fc_meta['basic_html']);
        $headers = array();
        $headers[] = 'From: '."=?UTF-8?B?".base64_encode($from_name)."?=".' <'.$from_email.'>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $email_sent = wp_mail( $email, $subject, $message, $headers );
        if(!$email_sent) {
          echo json_encode(array('failed' => esc_html__('Email setup error', 'formcraft')));
          die();
        } else {
          $sent++;
        }
      }

    }
    if ($sent==0) {
      echo json_encode(array('failed' => esc_html__('0 email sent', 'formcraft')));
    } else {
      echo json_encode(array('success' => $sent.esc_html__(' email(s) sent', 'formcraft')));
    }
    die();
  }

  /*
  Submit The Form
  */
  add_action('wp_ajax_formcraft3_form_submit', 'formcraft3_form_submit');
  add_action('wp_ajax_nopriv_formcraft3_form_submit', 'formcraft3_form_submit');
  function formcraft3_form_submit() {

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
              if ( (mb_strlen($value)-substr_count( $value, "\n" )) < $validation )
              {
                $fc_final_response['errors'][$field['identifier']] = str_ireplace('[x]', $validation, $messages['min_char']);
              }
              break;

              case 'maxChar':
              if ( !ctype_digit($validation) ) break;
              if ( (mb_strlen($value)-substr_count($value, "\n" )) > $validation )
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
        $value['value'] = implode("\n", $temp);
        unset($temp);
      } else if ($value['type']=='dropdown' || $value['type']=='checkbox') {
        $template[$value['label'].'.value'] = is_array($value['value']) ? implode(", ", $value['value']) : $value['value'];
        $fieldLabels[$value['identifier'].'.label'] = empty($fieldLabels[$value['identifier'].'.label']) ? [] : $fieldLabels[$value['identifier'].'.label'];
        $value['value'] = implode("\n", $fieldLabels[$value['identifier'].'.label']);
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
        $value['value'] = implode("\n", $newValue);
      } else {
        if ( is_array($value['value']) && count($value['value'])==1 )
        {
          $value['value'] = $value['value'][0] ;
        }
        else if ( is_array( $value['value'] ) )
        {
          $value['value'] = implode("\n", $value['value']) ;
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
      $value['value'] = str_replace("\n\n", "<br><br>", $value['value']);

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
                $headers[] = 'Reply-To: '.$replyTo. "\r\n";
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

  /*
  Save Form Data from the Form Editor Mode
  */
  add_action( 'wp_ajax_formcraft3_form_save', 'formcraft3_form_save' );
  function formcraft3_form_save() {
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

  add_action( 'wp_ajax_formcraft3_get', 'formcraft3_get' );
  add_action( 'wp_ajax_nopriv_formcraft3_get', 'formcraft3_get' );
  function formcraft3_get() {
    $args = array();
    $args['timeout'] = 10;
    if ( isset($_GET['URL']) ) {
      $url = parse_url(urldecode($_GET['URL']));
      if ( $url['host'] !== "formcraft-wp.com" ) {
        echo json_encode(array('failed' => 'Invalid URL')); die();
      }      
      $response = wp_remote_get($_GET['URL'], $args);
      if ( is_wp_error( $response ) ) {
        echo json_encode(array('failed' => $response->get_error_message()));
      } else {
        echo wp_remote_retrieve_body($response);
      }
      die();
    }
  }

  add_action( 'wp_ajax_formcraft3_file_delete', 'formcraft3_file_delete' );
  add_action( 'wp_ajax_nopriv_formcraft3_file_delete', 'formcraft3_file_delete' );
  function formcraft3_file_delete() {
    global $wpdb, $fc_meta, $fc_files_table;
    if (!isset($_POST['id'])) {
      die();
    }  
    $uniq_key = esc_sql($_POST['id']);
    $query = $wpdb->prepare("SELECT file_path FROM $fc_files_table WHERE uniq_key = %s", $uniq_key);
    $file_row = $wpdb->get_row($query, ARRAY_A);
    if (!$file_row) {
      echo json_encode(array('failed'=> esc_html__('Invalid Key?','formcraft'), 'debug' => esc_html__('Invalid Key?','formcraft') ));
      die();
    }
    unlink($file_row['file_path']);
    $delete = $wpdb->delete( $fc_files_table, array('uniq_key'=>$uniq_key) );
    if ($delete) {
      echo json_encode(array('success'=> true ));
      die();
    }
    die();
  }

  add_action( 'wp_ajax_formcraft3_delete_files', 'formcraft3_delete_files' );
  function formcraft3_delete_files() {
    global $wpdb, $fc_meta, $fc_files_table;
    if (!isset($_GET['files'])) {
      die();
    }
    $nonce = $_REQUEST['formcraft3_wpnonce'];
    if (!wp_verify_nonce($nonce, 'formcraft3_wpnonce')) {
      exit;
    }    
    $total = 0;
    foreach ($_GET['files'] as $key => $value) {
      if (!ctype_digit($value)) {
        continue;
      }
      $file_row = $wpdb->get_var($wpdb->prepare("SELECT file_path FROM $fc_files_table WHERE id = %d", $value));
      if ($file_row) {
        unlink($file_row);
        $delete = $wpdb->delete( $fc_files_table, array('id'=>$value) );
        $total = $delete==true ? $total + 1 : $total;
      }
    }
    echo json_encode(array('success' => $total.esc_html__(' file(s) deleted','formcraft')));
    die();
  }

  add_action( 'wp_ajax_formcraft3_file_upload', 'formcraft3_file_upload' );
  add_action( 'wp_ajax_nopriv_formcraft3_file_upload', 'formcraft3_file_upload' );
  function formcraft3_file_upload() {
    global $wpdb, $fc_meta, $fc_files_table;  

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
  
    if (isset($_FILES['files'])) {
      foreach ($_FILES as $key => $file) {
        if (intval($file['size']) == 0) {
          echo json_encode(array(
            'failed'=> esc_html__('Failed','formcraft'),
            'debug' => esc_html__('Invalid File Size', 'formcraft')
          ));
          die();
        }

        $is_valid = wp_check_filetype($file['name']);

        if ($is_valid['type'] === false) {
          echo json_encode(array('failed'=>'true','debug' => esc_html__('Invalid File Format','formcraft') ));
          die();          
        }

        $filename = sanitize_file_name($file['name']);
        $filename = explode('.', $filename);
        $extension = strtolower($filename[count($filename)-1]);
        $extension = preg_replace("/[^A-Za-z0-9]/", '', $extension);
        unset($filename[count($filename)-1]);
        $filename = implode('', $filename).'.'.$extension;

        $allowed = array('png','doc','docx','xls','xlsx','csv','txt','rtf','zip','mp3', 'm4v', 'wma','wmv','mpg','flv','avi','jpg','jpeg','png','gif','ods','rar','ppt','pptx','tif','wav','mov','psd','eps','sit','sitx','cdr','ai','mp4','m4a','bmp','pps','aif', 'pdf', 'svg', 'odt','psa','stp','step','igs','x_t','dwg','obj','stl','bin','ols','sketch','msg','eml','cr2', 'raw', 'dxf','dog','sldprt','slddrw','sldasm','step','ages','its','obj','heic','heif');

        if (!in_array($extension, $allowed)) {
          echo json_encode(array('failed'=>'true','debug' => esc_html__('Invalid File Format','formcraft') ));
          die();
        }

        if ( !isset($_GET['id']) || !ctype_digit($_GET['id']) ) {
          echo json_encode(array('failed' => esc_html__('Invalid Form ID','formcraft') ));
          die();
        }

        /* Safe to Upload */
        $filename_new = str_shuffle(md5(time())).'-'.$filename;

        // Set the permission constants if not already set.
        if ( ! defined( 'FS_CHMOD_DIR' ) ) {
            define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
        }
        if ( ! defined( 'FS_CHMOD_FILE' ) ) {
            define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
        }         
        $FSD = new WP_Filesystem_Direct(false);

        $file_done = formcraft3_upload_bits($filename_new, null, $FSD->get_contents($file["tmp_name"]), null, $_GET['id']);
        if ( isset($file_done['name']) ) {
          $uniq_key = str_shuffle(md5(time()));
          $file_name_new = substr($file_done['name'], strpos($file_done['name'], '-')+1, strlen($file_done['name']));
          $rows_affected = $wpdb->insert( $fc_files_table, array(
            'uniq_key' => $uniq_key,
            'name' => $file_name_new,
            'form' => $_GET['id'],
            'permanent' => 1,
            'mime' => $file['type'],
            'size' => intval($file['size']),
            'file_url' => $file_done['url'],
            'file_path' => $file_done['file'],
            'created' => strtotime('now')
            ) );
          echo json_encode(array('success'=> $uniq_key, 'file_name' => $file_name_new ));
          die();
        } else if ( $file_done['error']==true ) {
          echo json_encode(array('failed' => esc_html__('Failed','formcraft'), 'debug' => $file_done['error'] ));
          die();
        }
      }
    }
    die();
  }

  /*
  Save Imported Form File
  */
  add_action( 'wp_ajax_formcraft3_import_file', 'formcraft3_import_file' );
  function formcraft3_import_file() {
    global $wpdb, $fc_meta;
    if (!current_user_can($fc_meta['user_can'])) {
      die();
    }

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');

    if (isset($_FILES['form_file'])) {
      if (!isset($_FILES['form_file']['type']) || $_FILES['form_file']['type']!='text/plain') {
        echo json_encode(array('failed' => esc_html__('Invalid File Format','formcraft') ));
        die();
      } else {
        $filename = urldecode($_FILES['form_file']['name']);
        $filename = sanitize_file_name($filename);
        if ( ! defined( 'FS_CHMOD_DIR' ) ) {
            define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
        }
        if ( ! defined( 'FS_CHMOD_FILE' ) ) {
            define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
        }         
        $FSD = new WP_Filesystem_Direct(false);
        $file = formcraft3_upload_bits($filename, null, $FSD->get_contents($_FILES['form_file']['tmp_name']));
        if ($file['error']==true) {
          echo json_encode(array('failed' => esc_html__('Failed','formcraft'), 'debug' => $file['error'] ));
          die();
        }
        else {
          echo json_encode(array('success' => urlencode($file['name'])));
          die();
        }
      }
    }
    die();
  }

  /*
  Add Dashboard Menu Page
  Every user who can activate a plugin (i.e. every admin user) can access FormCraft
  */
  add_action('admin_menu', 'formcraft3_admin' );
  function formcraft3_admin() {
    global $wp_version, $fc_meta;
    add_menu_page( 'FormCraft Dashboard', 'FormCraft', $fc_meta['user_can'], 'formcraft-dashboard', 'formcraft3_dashboard_page', '', '31.3503' );
    add_submenu_page('formcraft-dashboard', 'FormCraft Dashboard', esc_html__('Forms', 'formcraft'), $fc_meta['user_can'], 'formcraft-dashboard', 'formcraft3_dashboard_page' );
    add_submenu_page('formcraft-dashboard', 'FormCraft Entries', esc_html__('Entries', 'formcraft'), $fc_meta['user_can'], 'formcraft-entries', 'formcraft3_dashboard_page' );
    add_submenu_page('formcraft-dashboard', 'FormCraft Insights', esc_html__('Insights', 'formcraft'), $fc_meta['user_can'], 'formcraft-insights', 'formcraft3_dashboard_page' );
    add_submenu_page('formcraft-dashboard', 'FormCraft Uploads', esc_html__('Uploads', 'formcraft'), $fc_meta['user_can'], 'formcraft-uploads', 'formcraft3_dashboard_page' );
    if ( $fc_meta['preview_mode'] == false ) {
      add_submenu_page('formcraft-dashboard', 'FormCraft License Info', esc_html__('License', 'formcraft'), $fc_meta['user_can'], 'formcraft-license', 'formcraft3_dashboard_page' );      
    }

    add_action( 'admin_enqueue_scripts', 'formcraft3_admin_assets' );    
  }
  function formcraft3_dashboard_page() {
    global $wp_version, $fc_meta;
    formcraft3_update_datetime_unix();
    if (isset($_GET['id'])) {
      require_once('views/builder.php');
    } else {
      echo wp_nonce_field('formcraft3_wpnonce', 'formcraft3_wpnonce', true, false)."<div id='formcraft_dashboard' class='formcraft-css'></div>";      
    }
  }
  function formcraft3_update_datetime_unix() {
    global $fc_meta, $wpdb, $fc_forms_table, $fc_submissions_table, $fc_files_table, $fc_progress_table;
    if (get_site_option('f3_unix_fix')) {
      return true;
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_submissions_table' AND COLUMN_NAME = 'created'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_submissions_table` CHANGE  `created`  `created` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_submissions_table` SET `created` = UNIX_TIMESTAMP( `created` );");
      $wpdb->get_results("ALTER TABLE  `$fc_submissions_table` CHANGE  `created`  `created` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_forms_table' AND COLUMN_NAME = 'created'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_forms_table` CHANGE  `created`  `created` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_forms_table` SET `created` = UNIX_TIMESTAMP( `created` );");
      $wpdb->get_results("ALTER TABLE  `$fc_forms_table` CHANGE  `created`  `created` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_forms_table' AND COLUMN_NAME = 'modified'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_forms_table` CHANGE  `modified`  `modified` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_forms_table` SET `modified` = UNIX_TIMESTAMP( `modified` );");
      $wpdb->get_results("ALTER TABLE  `$fc_forms_table` CHANGE  `modified`  `modified` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_files_table' AND COLUMN_NAME = 'created'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_files_table` CHANGE  `created`  `created` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_files_table` SET `created` = UNIX_TIMESTAMP( `created` );");
      $wpdb->get_results("ALTER TABLE  `$fc_files_table` CHANGE  `created`  `created` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_progress_table' AND COLUMN_NAME = 'created'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `created`  `created` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_progress_table` SET `created` = UNIX_TIMESTAMP( `created` );");
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `created`  `created` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_progress_table' AND COLUMN_NAME = 'modified'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `modified`  `modified` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_progress_table` SET `modified` = UNIX_TIMESTAMP( `modified` );");
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `modified`  `modified` INT( 11 ) NULL DEFAULT NULL;");
    }
    $type = $wpdb->get_var( "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$fc_progress_table' AND COLUMN_NAME = 'to_delete'");
    if ($type === 'datetime') {
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `to_delete`  `to_delete` VARCHAR( 100 ) NULL DEFAULT NULL;");
      $wpdb->get_results("UPDATE `$fc_progress_table` SET `to_delete` = UNIX_TIMESTAMP( `to_delete` );");
      $wpdb->get_results("ALTER TABLE  `$fc_progress_table` CHANGE  `to_delete`  `to_delete` INT( 11 ) NULL DEFAULT NULL;");
    }
    update_site_option('f3_unix_fix', true);
  }
  function formcraft3_edit_page_title() {
    global $post, $title, $action, $current_screen, $wpdb, $fc_forms_table;
    if ($current_screen->base === 'toplevel_page_formcraft-dashboard' && isset($_GET['id']) ) {
      $query = $wpdb->prepare("SELECT name FROM $fc_forms_table WHERE id = %d", $_GET['id']);
      $form_name = stripslashes($wpdb->get_var( $query ));
      return esc_html__('Edit Form: ', 'formcraft').$form_name;
    }
    return $title;
  }
  add_action( 'admin_title', 'formcraft3_edit_page_title' );
  function formcraft3_admin_assets($hook) {
    global $fc_meta, $fc_templates, $f3_messages, $fc_translate;
    if ( strpos($hook, 'formcraft-') === FALSE ) {
      return false;
    }

    $page = explode('formcraft-', $hook);
    $page = $page[1];    

    // Common JS Between Dashboard and Builder
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-datepicker');


    if ( strpos($hook, 'formcraft-dashboard') !== FALSE && isset($_GET['id']) ) {

      wp_enqueue_script('jquery-ui-slider');
      wp_enqueue_script('fc-modal', plugins_url( 'assets/js/src/fc_modal.js', __FILE__ ), array(), $fc_meta['version']);
      wp_enqueue_script('tooltip', plugins_url( 'assets/js/vendor/tooltip.min.js', __FILE__ ));
      wp_enqueue_script('autosize', plugins_url( 'assets/js/vendor/autosize.js', __FILE__ ), array(), $fc_meta['version']);      
      
      /* Builder Styles and Scripts */
      wp_enqueue_style('wp-color-picker');
      wp_enqueue_style('formcraft-common', plugins_url('dist/formcraft-common.css', __FILE__), array(), $fc_meta['version']);
      wp_enqueue_style('formcraft-builder', plugins_url('dist/formcraft-builder.css', __FILE__), array(), $fc_meta['version']);     
      wp_enqueue_style('formcraft-form', plugins_url('dist/form.css', __FILE__), array(), $fc_meta['version']);     

      wp_enqueue_script( 'wp-color-picker' );

      wp_enqueue_script('selectize', plugins_url( 'assets/js/vendor/selectize.min.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_script('angular', plugins_url( 'assets/js/vendor/angular-1.5.0.min.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_script('ui-sortable', plugins_url( 'assets/js/vendor/ui.sortable.min.js', __FILE__ ), array('jquery-ui-core','jquery-ui-widget','jquery-ui-mouse','jquery-ui-sortable'), $fc_meta['version']);

      wp_enqueue_script('textAngular-rangy', plugins_url( 'lib/textAngular/textAngular-rangy.min.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_script('textAngular-sanitize', plugins_url( 'lib/textAngular/textAngular-sanitize.min.js', __FILE__ ),array('textAngular-rangy'), $fc_meta['version']);
      wp_enqueue_script('textAngular', plugins_url( 'lib/textAngular/textAngular.min.js', __FILE__ ), array('textAngular-rangy', 'textAngular-sanitize'), $fc_meta['version']);
      wp_enqueue_script('textAngularSetup', plugins_url( 'lib/textAngular/textAngularSetup.js', __FILE__ ),array(), $fc_meta['version']);

      wp_enqueue_script('fc-builder-js', plugins_url( 'dist/formcraft-builder.min.js', __FILE__ ), array('jquery-ui-core','jquery-ui-widget','jquery-ui-mouse','jquery-ui-sortable'), $fc_meta['version']);
      wp_enqueue_script('fc-builder-mask', plugins_url( 'assets/js/vendor/jquery.mask.js', __FILE__ ), array('fc-builder-js'), $fc_meta['version']);
      wp_localize_script( 'fc-builder-js', 'FC',
        array(
          'licenseKey' => get_site_option('f3_key'),
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
          'pluginurl' => plugins_url( '', __FILE__ ),
          'baseurl' => get_site_url(),
          'gzinflate' => function_exists('gzinflate'),
          'datepickerLang' => plugins_url( 'assets/js/datepicker-lang/', __FILE__ ),
          'form_id' => isset($_GET['id']) ? intval($_GET['id']) : 0,
          'fct' => $fc_translate
        )
      , array(), $fc_meta['version']);
      wp_enqueue_script('deflate', plugins_url( 'assets/js/vendor/deflate.all.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_script('htmlminifier', plugins_url( 'assets/js/vendor/htmlminifier.min.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_script('highlight', plugins_url( 'assets/js/vendor/highlight.pack.js', __FILE__ ),array(), $fc_meta['version']);
      wp_enqueue_style('highlight', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css', array(), $fc_meta['version']);


      wp_deregister_script('wp-emoji');
      wp_deregister_script('wpemoji');

      do_action('formcraft_addon_scripts');

    } else {

      $f3_messages = isset($f3_messages) ? $f3_messages : array();
      $f3_key_temp = get_site_option('f3_key');

      if ( !empty($f3_key_temp) ) {
        if (!ctype_digit(get_site_option('f3_purchased'))){
          update_site_option('f3_purchased', strtotime(get_site_option('f3_purchased')));
        }
        if (!ctype_digit(get_site_option('f3_registered'))){
          update_site_option('f3_registered', strtotime(get_site_option('f3_registered')));
        }
        if (!ctype_digit(get_site_option('f3_expires'))){
          update_site_option('f3_expires', strtotime(get_site_option('f3_expires')));
        }
      } else {
        if ($fc_meta['preview_mode'] == false ) {
          $f3_messages[] = array(
            'message' => esc_html__('Click here to register your copy of FormCraft', 'formcraft'),
            'link' => menu_page_url('formcraft-license', 0),
            'className' => 'IsRed'
          );
        }
      }

      if ( !empty($f3_key_temp) ) {
        $last_check = (strtotime('now') - get_site_option('f3_registered')) / (60 * 60 * 24);
        /* Re-verify the license key every 7 days */
        if ( $last_check > 7 ) {
          $key = $f3_key_temp;
          $email = get_site_option('f3_email');
          $args = array(
            'timeout'     => 15,
            'redirection' => 5,
            'sslverify'   => false
          );
          $siteURL = is_multisite() && $fc_meta['f3_multi_site_addon'] === true ? network_site_url() : site_url();
          $response = wp_remote_get("http://formcraft-wp.com?type=verify_license&v=2&key=".$key."&email=".$email."&site=".rawurlencode($siteURL));
          if ( !is_wp_error($response) && isset($response['body']) ) {
            $response = json_decode($response['body'], 1);
            if ( $response != NULL && !empty($response) ) {
              if ( isset($response['failed']) ) {
                delete_site_option('f3_key');
                delete_site_option('f3_email');
                delete_site_option('f3_verified');
                delete_site_option('f3_registered');
                delete_site_option('f3_expires');
                delete_site_option('f3_purchased');
                delete_site_option('f3_registered');
              } else if ( isset($response['success']) ) {
                update_site_option('f3_registered', $response['registered']);
              }
            }
          }
        }
      }

      if ( isset($_GET['no_license_message']) ) {
        update_site_option('f3_no_license_error', true);
      }
      if ( get_site_option('f3_expires') != '' && get_site_option('f3_no_license_error') != true ) {
        if ( ( (get_site_option('f3_expires') - strtotime('now')) / (60 * 60 * 24) ) < 0 ) {
          $f3_messages[] = array('message' => esc_html__('Your license seems to have expired.', 'formcraft').' <a target="_blank" href="http://formcraft-wp.com/buy/?addons=346&key='.get_site_option('f3_key').'">'.esc_html__('Click here to renew it.', 'formcraft').'</a> <a target="_blank" href="http://formcraft-wp.com/help/faq-plugin-license/">'.esc_html__('Read More.', 'formcraft').'</a>. <a href="admin.php?page=formcraft-'.$page.'&no_license_message">'.esc_html__('Dismiss', 'formcraft').'</a>.');
        }
      }

      // Common CSS Files for All Pages
      wp_enqueue_style('formcraft-common', plugins_url('dist/formcraft-common.css', __FILE__), array(), $fc_meta['version']);

      // Load React for Some Pages
      $load_react = array('dashboard', 'entries', 'insights', 'uploads', 'license');
      if (in_array($page, $load_react)) {
        wp_enqueue_script('react', plugins_url( 'lib/react.min.js', __FILE__ ));
        wp_enqueue_script('react-dom', plugins_url( 'lib/react-dom.min.js', __FILE__ ));
        wp_enqueue_script('chart', plugins_url( 'assets/js/vendor/chart.min.js', __FILE__ ));
        // Load MomentJS
        wp_enqueue_script('moment', plugins_url( 'lib/moment/moment.min.js', __FILE__ ), array('react', 'react-dom'), $fc_meta['version']);
        $locale = strtolower(str_replace('_','-',get_locale()));
        $localeLanguage = substr($locale, 0, strpos($locale, '-'));
        if (file_exists(plugin_dir_path( __FILE__ ) . 'lib/moment/locale/'.$locale.'.js')) {
          wp_enqueue_script('moment-'.$locale, plugins_url( 'lib/moment/locale/'.$locale.'.js', __FILE__ ), array(), $fc_meta['version']); 
        } else if (file_exists(plugin_dir_path( __FILE__ ) . 'lib/moment/locale/'.$localeLanguage.'.js')) {
          wp_enqueue_script('moment-'.$localeLanguage, plugins_url( 'lib/moment/locale/'.$localeLanguage.'.js', __FILE__ ), array(), $fc_meta['version']);
        }
      }

      // Load ChartJS for Some Pages
      $load_chart = array('dashboard', 'insights');
      if (in_array($page, $load_chart)) {
        wp_enqueue_script('chart', plugins_url( 'assets/js/vendor/chart.min.js', __FILE__ ));
      }       

      // Load FormCraft Admin CSS for Some Pages
      $load_admin = array('dashboard', 'entries', 'insights', 'uploads', 'license');
      if (in_array($page, $load_admin)) {
        wp_enqueue_style('formcraft-admin', plugins_url('dist/formcraft-admin.css', __FILE__), array(), $fc_meta['version']);
      }

      if ($page==='dashboard') {
        wp_enqueue_style('formcraft-form', plugins_url( 'dist/form.css', __FILE__ ),array(), $fc_meta['version']);
        do_action('formcraft_form_scripts');
      }

      $templates = array();
      foreach ($fc_templates as $key => $templatesGroup) {
        if ( empty($templatesGroup) ) { continue; }
        if ( !file_exists($templatesGroup) ) { continue; }
        $templatesTemp = scandir($templatesGroup);
        if ( !$templatesTemp ){continue;}
        $templates[$key] = array();
        foreach ($templatesTemp as $key2 => $value) {
          $temp1 = explode('.', $value);
          if ( isset($temp1[ count($temp1) - 1 ]) && $temp1[ count($temp1) - 1 ] == 'txt' ) {
            $templates[$key][] = array('name'=>str_replace('.txt', '', $value),'path'=>str_replace(WP_PLUGIN_DIR,'',$templatesGroup).$value);
          }
        }
      }
      foreach ($templates as $key => $value) {
        if (count($value) === 0) {
          unset($templates[$key]);
        }
      }

      $global_data = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'baseurl' => get_site_url(),
        'version' => $fc_meta['version'],
        'templates' => $templates,
        'notices' => $f3_messages,
        'disableAnalytics' => get_site_option('f3_disable_analytics'),
        'keepData' => get_site_option('f3_keep_data'),
        'fct' => $fc_translate
      );

      if ( $page === 'license' ) {
        $global_data['keyVerified'] = get_site_option('f3_verified') === 'yes';
        $global_data['key'] = get_site_option('f3_key');
        $global_data['email'] = get_site_option('f3_email');
        $global_data['purchased'] = date(get_option('date_format'), get_site_option('f3_purchased'));
        $global_data['registered'] = date(get_option('date_format'), get_site_option('f3_registered'));
        $global_data['expires'] = date(get_option('date_format'), get_site_option('f3_expires'));
        $global_data['expires_days'] = ( get_site_option('f3_expires') - strtotime('now') ) / ( 60 * 60 * 24 );
      }

      // Load Page Specifc React View
      wp_enqueue_script('fc-'.$page.'', plugins_url( 'dist/formcraft-'.$page.'.min.js', __FILE__ ), array('moment', 'react', 'react-dom'), $fc_meta['version']);
      wp_localize_script( 'fc-'.$page, 'FormCraftGlobal', $global_data);
    }
  }


  /* General Function to Remove Text */
  function formcraft3_replace_comments($beginning, $end, $string, $replace) {
    $loop = false;
    while ($loop==false) {
      $beginningPos = null;
      $endPos = null;
      $beginningPos = strpos($string, $beginning);
      $endPos = strpos($string, $end);
      if ( $beginningPos===false || $endPos===false)
      {
        return $string;
        $loop = true;
      }
      $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
      $string = str_replace($textToDelete, $replace, $string);
      $loop = false;
    }
    return $string;
  }
  function formcraft3_parse_emails($string, $nos = 20) {
    $emails = array();
    if(preg_match_all('/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/', $string, $matches, PREG_SET_ORDER) > 0) {
      $i = 0;
      foreach($matches as $m) {
        if ($i>=$nos){
          break;
        }
        if(! empty($m[2])) {
          if (!filter_var(trim($m[2], '<>'), FILTER_VALIDATE_EMAIL)) {continue;}
          $emails[trim($m[2], '<>')] = trim($m[1]);
        } else {
          if (!filter_var($m[1], FILTER_VALIDATE_EMAIL)) {continue;}
          $emails[$m[1]] = '';
        }
        $i++;
      }
    }
    return $emails;
  }

  function fc_template($content, $body, $allow_html = false) {
    return formcraft3_template($content, $body, $allow_html);
  }

  function formcraft3_template($content, $body, $allow_html = false) {
    foreach ($content as $label => $value) {
      if ( !is_string($value) && !is_int($value) ) {
        continue;
      }
      $value = nl2br($value);
      if ($allow_html == true) {
        $value = str_replace("'", '', str_replace('"', '', $value));
        $value = urlencode(html_entity_decode(stripslashes($value), ENT_QUOTES, 'utf-8'));
        $body = str_ireplace('['.$label.']', $value, $body);
      } else {
        if (substr($value, 0, 10) == 'data:image') {
          $body = str_ireplace('['.$label.']', "<img src='".$value."'/>", $body);
        } else {
          $value = html_entity_decode(stripslashes($value), ENT_QUOTES, 'utf-8');
          $body = str_ireplace('['.$label.']', stripslashes($value), $body);
        }
      }
    }
    return $body;
  }

  use jlawrence\eos\Parser;
  function formcraft3_math($formValues, $body) {
    require_once 'lib/eos/AdvancedFunctions.php';
    require_once 'lib/eos/Stack.php';
    require_once 'lib/eos/Math.php';
    require_once 'lib/eos/Parser.php';

    preg_match_all("/\[[^\]]*\]/", $body, $out);
    if (count($out[0])>0) {
      $areNumeric = array();
      foreach ($formValues as $key => $value) {
        if (is_array($value['value'])) {
          $temp = 0;
          foreach ($value['value'] as $k => $v) {
            $temp = is_numeric($v) ? $temp + $v : $temp;
          }
        } else {
          $temp = is_numeric($value['value']) ? $value['value'] : 0;
        }
        if ($temp != 0) {
          $areNumeric[str_replace('field', '', $formValues[$key]['identifier'])] = floatval($temp);
        }
      }
      krsort($areNumeric, SORT_NUMERIC);
      $temp = array();
      foreach ($areNumeric as $key => $value) {
        $temp['field'.$key] = $value;
      }

      foreach ($out[0] as $key => $value) {

        $outTemp = preg_replace('/[^a-zA-Z0-9.*()\-+\/]+/i', '', $out[0][$key]);
        $value = str_replace(' ', '', $value);
        $split = preg_split("/([*()\]\[\-+\/]+)/", $outTemp, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $newSplit = array();
        if ( $split != false && count($split) > 1 ) {

          // Replace empty or non-value fields with 0
          foreach ($split as $k => $v) {
            if (substr(strtolower($v), 0, 5)=='field') {
              $replaceWithZero = isset($temp[$v]) ? false : true;
              if ($replaceWithZero) {
                $newSplit[] = 0;
              } else {
                $newSplit[] = $temp[$v];
              }
            } else {
              $newSplit[] = $v;
            }
          }

          try {
            $result = Parser::solve(implode('', $newSplit));
            $body = str_replace($out[0][$key], round($result, 2), $body);
          } catch (Exception $e) {
          }
        }
      }
    }
    return $body;
  }
  function formcraft3_template_content($content, $body) {
    foreach ($content as $label => $value) {
      if ( $value['type'] == 'matrix' ) {
        $newValue = array();
        foreach ($value['value'] as $key2 => $value2) {
          $newValue[] = $value2['question'].': '.$value2['value'];
        }
        $value['value'] = implode("<br>", $newValue);
      }
      $value['value'] = $value['type'] == 'signature' ? "<img src='".$value['value']."'/>" : $value['value'];
      $value['value'] = is_array($value['value']) ? implode(', ', $value['value']) : $value['value'];
      $value['value'] = html_entity_decode(stripslashes($value['value']), ENT_QUOTES, 'utf-8');
      $body = str_ireplace('['.$value['identifier'].']', $value['value'], $body);
    }
    return $body;
  }
  function formcraft3_offset() {
    return floatval(get_option('gmt_offset')) * 60 * 60;
  }
  function formcraft3_stripslashes_deep($value) {
    $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);
    return $value;
  }
  function formcraft3_prepare_csv($data, $separator) {
    $separator = isset($separator) ? $separator : ',';
    $outputBuffer = fopen("php://output", 'w');
    fwrite($outputBuffer, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    foreach($data as $row) {
      foreach ($row as $columnKey => $columnData) {
        if (is_array($columnData)) {
          if (is_array($columnData[0])) {
            foreach ($columnData as $optionKey => $optionValue) {
              $columnData[$optionKey] = $optionValue['question'].': '.$optionValue['value'];
            }
          }
          $columnData = implode(', ', $columnData);
          $row[$columnKey] = $columnData;
        }
      }
      foreach ($row as $columnKey => $columnData) {
        $finalSheet[$columnKey] = html_entity_decode($columnData, ENT_QUOTES, 'utf-8');
      }
      fputcsv($outputBuffer, (array)$finalSheet, $separator);
    }
    fclose($outputBuffer);
  }

  function formcraft3_brightness($hex, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
      $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
      $color   = hexdec($color);
      $color   = max(0,min(255,$color + $steps));
      $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }

    return $return;
  }

  function formcraft3_upload_bits( $name, $deprecated, $bits, $time = null, $form = null ) {

    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');    

    if ( !empty( $deprecated ) )
      _deprecated_argument( __FUNCTION__, '2.0' );

    if (empty($name)) {
      return array( 'error' => esc_html__('Empty filename', 'formcraft') );
    }

    $upload = wp_upload_dir( $time );
    if ($form) {
      $upload['path'] = $upload['basedir'].'/formcraft3/'.$form;
      $upload['url'] = $upload['baseurl'].'/formcraft3/'.$form;
      $upload['subdir'] = '/formcraft3/'.$form;

    } else {
      $upload['path'] = $upload['basedir'].'/formcraft3';
      $upload['url'] = $upload['baseurl'].'/formcraft3';
      $upload['subdir'] = '/formcraft3';
    }

    if ( $upload['error'] !== false ) {
      return $upload;
    }
    $upload_bits_error = apply_filters('wp_upload_bits', array('name' => $name, 'bits' => $bits, 'time' => $time));
    if ( !is_array( $upload_bits_error ) ) {
      $upload[ 'error' ] = $upload_bits_error;
      return $upload;
    }
    // Set the permission constants if not already set.
    if ( ! defined( 'FS_CHMOD_DIR' ) ) {
        define( 'FS_CHMOD_DIR', ( fileperms( ABSPATH ) & 0777 | 0755 ) );
    }
    if ( ! defined( 'FS_CHMOD_FILE' ) ) {
        define( 'FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
    }     
    $FSD = new WP_Filesystem_Direct(false);

    $filename = wp_unique_filename( $upload['path'], $name );
    $new_file = $upload['path'] . "/$filename";

    if ( ! wp_mkdir_p( dirname( $new_file ) ) ) {
      if ( 0 === strpos( $upload['basedir'], ABSPATH ) ) { 
        $error_path = str_replace( ABSPATH, '', $upload['basedir'] ) . $upload['subdir'];
      } else {
        $error_path = basename( $upload['basedir'] ) . $upload['subdir'];
      }
    }

    if ( !$FSD->exists($upload['path'].'/index.php') ) {
      $FSD->put_contents($upload['path'].'/index.php', '<?php ?>');
    }    

    $fileCreated = $FSD->put_contents($new_file, $bits);

    if (!$fileCreated ) {
      return array( 'error' => esc_html__('Could not write file. Please ensure file permissions are correct.', 'formcraft') );
    }

    $url = $upload['url'] . "/$filename";

    return array( 'file' => $new_file, 'url' => $url, 'name'=> $filename, 'error' => false );
  }

  function formcraft3_htmlentities($content) {
    if (is_array($content)) {
      $temp = array();
      foreach ($content as $key => $value) {
        $temp[$key] = htmlentities($value, ENT_QUOTES, "UTF-8");
      }
      return $temp;
    } else {
      return htmlentities($content, ENT_QUOTES, "UTF-8");
    }
  }

  function formcraft3_email_template($content) {
    $content = str_ireplace('<p></br>', '<p>', $content);
    $content = str_ireplace('<p><br/>', '<p>', $content);
    $content = str_ireplace('<p><br>', '<p>', $content);
    return '
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html>
    <head>
      <meta name="viewport" content="width=device-width" />
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body style="font-size: 100%; line-height: 1.6; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; height: 100%">
      <table style="font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0px;">
        <tr>
          <td style="clear: both !important; margin: 0; padding: 0px;">
            <div style="font-size: 100%; line-height: 1.6; display: block; margin: 0; padding: 0;">
              '.$content.'
            </div>
          </td>
        </tr>
      </table>
    </body>
    </html>';
  }


?>