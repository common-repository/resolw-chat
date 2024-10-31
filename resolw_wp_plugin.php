<?php
/**
 * Plugin Name: Resolw Chat
 * Description: Efficiently organise your incoming customer requests with Resolw’s multifaceted customer support plugin. Integrate live chat, video and image pointing calls, and audio calls on your website with Resolw’s chat button.
 * Version: 1.0.4
 * Auhtor: Resolw
 * Plugin URI: https://wordpress.org/plugins/resolw-chat
 * Author URI: https://resolw.com
 * License: GPLv2 or later
 * Text Domain: resolw-chat
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
global $api_url;
$api_url = "https://api.resolw.com/";
global $api_version;
$api_version = 0;


/*
   =========================================================
            Plugin Activation actions
   =========================================================
*/

   function resolw_chat_activation() {
   		global $wpdb;
   		$table_name = $wpdb->prefix."resolw_settings";
   		$charset_collate = $wpdb->get_charset_collate();

      if($wpdb->get_var("SHOW TABLES LIKE '$table_name'" ) != $table_name){
        $sql = "CREATE TABLE $table_name (
          id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
          author_id INT(6) UNSIGNED NOT NULL,
          resolw_key VARCHAR(100) NOT NULL,
          resolw_value VARCHAR(1000),
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
      }

      $table_name2 = $wpdb->prefix."resolw_link";
      if($wpdb->get_var("SHOW TABLES LIKE '$table_name2'" ) != $table_name2){
        $sql = "CREATE TABLE $table_name2 (
          id INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
          author_id INT(6) UNSIGNED NOT NULL,
          resolw_link_text VARCHAR(1000),
          resolw_image_link VARCHAR(1000),
          resolw_link_title VARCHAR(1000),
          PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
      }
    }

   register_activation_hook( __FILE__, 'resolw_chat_activation' );

/* ======== End of Plugin Activation actions =============== */



/*
   =========================================================
            Plugin Deactivation actions
   =========================================================
*/

/* Log out */

/* Delete me after database structures are set */
register_deactivation_hook( __FILE__, 'delete_resolw_tables' );
/* ======== End of Plugin Dectivation actions ============== */



/*
   =========================================================
            Plugin Uninstall actions
   =========================================================
*/

   function delete_resolw_tables() {
   		global $wpdb;
        $tableArray = [   
          	$wpdb->prefix."resolw_settings",
            $wpdb->prefix."resolw_link"
       	];
      	foreach ($tableArray as $tablename) {
         	$wpdb->query("DROP TABLE IF EXISTS $tablename");
      	}
  	}

   register_uninstall_hook( __FILE__, 'delete_resolw_tables' );

/* ======== End of Plugin Unintstall actions =============== */

/*
   =========================================================
            Link bubble actions
   =========================================================
*/

function resolwc_get_selected_unit_data(){
  global $wpdb;
  $resolw_link_table = $wpdb->prefix."resolw_link";
  $link_line = $wpdb->get_results("SELECT * FROM `" . $resolw_link_table . "`");
  if (!$link_line) {
    echo 'xxx';
  } else {
    $return_array = array(
      'imageLink' => $link_line[0]->resolw_image_link,
      'mainLinkText' => $link_line[0]->resolw_link_text,
      'title' => $link_line[0]->resolw_link_title
    );
    echo json_encode($return_array);
  }
  die();
}

add_action( 'wp_ajax_resolwc_get_selected_unit_data', 'resolwc_get_selected_unit_data' );
add_action( 'wp_ajax_nopriv_resolwc_get_selected_unit_data', 'resolwc_get_selected_unit_data' );

/* ======== End of Link bubble actions ===================== */


/*
   =========================================================
            Admin tab actions
   =========================================================
*/

    function resolw_settings_panel(){
      add_menu_page('Resolw Chat Settings', 'Resolw', 'manage_options', 'resolw-settings', 'resolwc_load_main', plugins_url('./assets/images/Icon_sidebar_resolw_active.png', __FILE__));
    }
    add_action('admin_menu', 'resolw_settings_panel');

    function resolwc_load_main(){
      ob_start();
      include "main.php";
      $html = ob_get_clean();
      echo $html;
      die();
    }


/* ======== End of Admin tab actions actions =============== */


/*
   =========================================================
            Admin Actions
   =========================================================
*/

function resolwc_load_login_component(){
  ob_start();
  include "login.php";
  $html = ob_get_clean();
  echo $html;
  die();
}
add_action( 'wp_ajax_resolwc_load_login_component', 'resolwc_load_login_component');

function resolwc_logout(){
  $token = resolwc_get_token();
  if ($token != "no_token") {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    global $wpdb;
    $resolw_settings_table = $wpdb->prefix."resolw_settings";
    $token_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE author_id = %d AND resolw_key = %s", $user_id, "auth_header"));
    if ($token_row) {
      $wpdb->delete($resolw_settings_table, array("author_id" => $user_id, "resolw_key" => "auth_header"), array('%d','%s'));
    }
    $selection_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE resolw_key = %s", "unit_selected"));
    if ($selection_row) {
      $wpdb->delete($resolw_settings_table, array("resolw_key" => "unit_selected"), array('%s'));
    }
    $resolw_link_table = $wpdb->prefix . "resolw_link";
    $delete_link_line = $wpdb->query("TRUNCATE TABLE $resolw_link_table");
    ob_start();
    include "login.php";
    $html = ob_get_clean();
    echo $html;
    die();
  }
}
add_action( 'wp_ajax_resolwc_logout', 'resolwc_logout' );


function resolwc_save_token($token) {
  $user = wp_get_current_user();
  $user_id = $user->ID;
  global $wpdb;
  $resolw_settings_table = $wpdb->prefix."resolw_settings";
  $token_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE author_id = %d AND resolw_key = %s", $user_id, "auth_header"));
  if ($token_row) {
    $wpdb->delete($resolw_settings_table, array("author_id" => $user_id, "resolw_key" => "auth_header"), array('%d','%s'));
  }
  $data = array("author_id" => $user_id, "resolw_key" => "auth_header", "resolw_value" => $token);
  $format = array("%d", "%s", "%s");
  $wpdb->insert($resolw_settings_table, $data, $format);
}

function resolwc_get_token() {
  global $wpdb;
  $user = wp_get_current_user();
  $user_id = $user->ID;
  $resolw_settings_table = $wpdb->prefix."resolw_settings";
  $token_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE author_id = %s AND resolw_key = %s", $user_id, "auth_header"));
  if ($token_row) {
    return $token_row->resolw_value;
  } else {
    return "no_token";
  }
  die();
}

function resolwc_load_units() {
  $token = resolwc_get_token();
  if ($token == "no_token") {
    $return = array(
      "status" => "error",
      "message" => __("Unknown error!", "resolw-chat")
    );
    echo json_encode($return);
  } else {
    $url = "support/units";
    $response = json_decode(resolwc_httpGet($token, $url));
    $team_name = $response->team->name;
    $units = $response->supportUnits;
    $return = array(
      "status" => "ok",
      'team_name' => $team_name,
      'units' => $units
    );
    echo json_encode($return);
  }
  die();
}
add_action('wp_ajax_resolwc_load_units', 'resolwc_load_units');

function resolwc_load_units_component() {
  ob_start();
  include "units.php";
  $html = ob_get_clean();
  echo $html;
  die();
}
add_action('wp_ajax_resolwc_load_units_component', 'resolwc_load_units_component');

function resolwc_save_selected_unit() {
  $unit_id = sanitize_text_field($_POST['unit_id']);
  $imageLink = esc_url_raw($_POST['imageLink']);
  $mainLinkText = sanitize_text_field($_POST['mainLinkText']);
  $title = sanitize_text_field($_POST['title']);

  $user = wp_get_current_user();
  $user_id = $user->ID;
  global $wpdb;
  $resolw_settings_table = $wpdb->prefix."resolw_settings";

  $selection_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE resolw_key = %s", "unit_selected"));
  if ($selection_row) {
    $wpdb->delete($resolw_settings_table, array("resolw_key" => "unit_selected"), array('%s'));
  }
  $data = array("author_id" => $user_id, "resolw_key" => "unit_selected", "resolw_value" => $unit_id);
  $format = array("%d", "%s", "%s");
  $wpdb->insert($resolw_settings_table, $data, $format);

  $resolw_link_table = $wpdb->prefix . "resolw_link";
  $delete_link_line = $wpdb->query("TRUNCATE TABLE $resolw_link_table");

  $link_data = array("author_id" => $user_id, "resolw_link_text" => $mainLinkText, "resolw_image_link" => $imageLink, "resolw_link_title" => $title);
  $link_format = array("%d", "%s", "%s", "%s");
  $wpdb->insert($resolw_link_table, $link_data, $link_format);

  echo "ok";
  die();
}
add_action('wp_ajax_resolwc_save_selected_unit', 'resolwc_save_selected_unit');

function resolwc_get_selected_unit() {
  global $wpdb;
  $resolw_settings_table = $wpdb->prefix."resolw_settings";
  $selection_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM `" . $resolw_settings_table . "` WHERE resolw_key = %s", "unit_selected"));
  $response = 'xxx';
  if ($selection_row){
    $response = $selection_row->resolw_value;
  }
  echo $response;
  die();
}
add_action('wp_ajax_resolwc_get_selected_unit', 'resolwc_get_selected_unit');

function resolwc_pwlogin(){
  $return;
  $dat = array(
    'email' => sanitize_email($_POST['email']),
    'password' => $_POST['password']
  );
  $data = json_encode($dat, JSON_FORCE_OBJECT);
  $url = "user/login";
  $response = json_decode(resolwc_httpAnonPost($url, $data));
  if (!$response) {
    $return = array(
      "status" => "error",
      "message" => __("Login error!", "resolw-chat")
    );
  } else {
    if ($response->status == "ok") {
      if ($response->identity->teamRole == "owner" || $response->identity->teamRole == "admin") {
        $response = (array) $response;
        $token = $response['X-Resolw-Auth-Token'];
        resolwc_save_token($token);
        $return = array(
          "status" => "ok"
        );
      } else {
        resolwc_save_token("no_token");
        $return = array(
          "status" => "error",
          "message" => __("Must have Resolw admin rights!", "resolw-chat")
        );
      }
    } else {
      if ($response->reason == "invalid_email") {
        $return = array(
          "status" => "error",
          "message" => __("Invalid email!", "resolw-chat")
        );
      } else if ($response->reason == "invalid_login") {
        $return = array(
          "status" => "error",
          "message" => __("Invaid email or password!", "resolw-chat")
        );
      } else {
        $return = array(
          "status" => "error",
          "message" => __("Login error!", "resolw-chat")
        );
      }
    }
  }
  echo json_encode($return);
  die();
}
add_action('wp_ajax_resolwc_pwlogin', 'resolwc_pwlogin');

function resolwc_check_login(){
  $return;
  $token = resolwc_get_token();
  if ($token == "no_token") {
    $return = array(
      "status" => "ok",
      "proceed" => false
    );
  } else {
    $url = "user/me";
    $response = json_decode(resolwc_httpGet($token, $url));
    if ($response->status != "ok") {
      $return = array(
        "status" => "error",
        "message" => __("Login error!", "resolw-chat")
      );
    } else {
      if ($response->identity->teamRole == "owner" || $response->identity->teamRole == "admin") {
        $return = array(
          "status" => "ok",
          "proceed" => true
        );
      } else {
        resolwc_save_token("no_token");
        $return = array(
          "status" => "error",
          "message" => __("Must have Resolw admin rights!", "resolw-chat")
        );
      }
    }
  }
  echo json_encode($return);
  die();
}
add_action('wp_ajax_resolwc_check_login', 'resolwc_check_login');

/* ============= End of Admin Actions ====================== */



/*
   =========================================================
                HTTP API
   =========================================================
*/

   function resolwc_httpAnonPost($url, $data){
    global $api_version;
    global $api_url;
    $endpoint = $api_url.$url;
    $args = array(
      "body" => $data,
      "headers" => array(
        "Content-Type" => "application/json", "X-Resolw-API-Version" => $api_version
      )
    );
    return wp_remote_retrieve_body(wp_remote_post($endpoint, $args));
   }

  function resolwc_httpGet($token, $url){
    global $api_version;
    global $api_url;
    $endpoint = $api_url.$url;
    $args = array(
      "headers" => array(
        "Content-Type" => "application/json", "X-Resolw-API-Version" => $api_version, "X-Resolw-Auth-Token" => $token
      )
    );
    return wp_remote_retrieve_body(wp_remote_get($endpoint, $args));
  }


/* ============= End of HTTP API =========================== */



/*
   =========================================================
            Script Additions
   =========================================================
*/

function resolwc_enqueue_admin_scripts() {
  wp_enqueue_script('login_script', plugin_dir_url( __FILE__ ) . 'js/login.js', array('jquery'), '1.1');
  wp_enqueue_script('units_script', plugin_dir_url( __FILE__ ) . 'js/units.js', array('jquery'), '1.1');

  wp_enqueue_style('select2', plugin_dir_url( __FILE__ ) . 'vendor/select2/dist/css/select2.min.css');
  wp_enqueue_script('select2', plugin_dir_url( __FILE__ ) . 'vendor/select2/dist/js/select2.min.js', array('jquery') );

  wp_register_style('resolw_styles', plugins_url('resolw-chat/styles/admin.css'));
  wp_enqueue_style('resolw_styles');
}
add_action('admin_enqueue_scripts', 'resolwc_enqueue_admin_scripts');

function resolwc_enqueue_customer_scripts() {
    wp_register_style('resolw_customer_styles', plugins_url('resolw-chat/styles/customer.css'));
    wp_enqueue_style('resolw_customer_styles');

    wp_register_script('link_bubble_script', plugins_url('js/link_bubble.js', __FILE__), array('jquery'),'1.1', true); 
    wp_enqueue_script('link_bubble_script', plugin_dir_url( __FILE__ ) . 'js/link_bubble.js', array('jquery'), '1.1');
}
add_action( 'wp_enqueue_scripts', 'resolwc_enqueue_customer_scripts');


/* ============= End of Script Additions ================== */

/*
   =========================================================
            Translations
   =========================================================
*/

function load_resolw_translations() {
  load_plugin_textdomain('resolw-chat', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}
add_action('init', 'load_resolw_translations');

/* ============= End of Translations ======================= */


/*
   =========================================================
            Ajax Location Definition
   =========================================================
*/

add_action('wp_head', 'resolwplugin_ajaxurl');

function resolwplugin_ajaxurl() {
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

/* ============= End of Ajax Location Definition =========== */

?>