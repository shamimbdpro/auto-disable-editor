<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Disable Gutenberg
 * Plugin URI:        https://wordpress.org/plugins/auto-disable-editor
 * Description:       Auto Disable Gutenberg will help to enable classic editor. its will disable new gutenberg block plugin.
 * Version:           1.0.7
 * Author:            CodePopular
 * Author URI:        https://www.codepopular.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-disable-editor
 * Requires at least: 4.0
 * Tested up to: 6.4
 * Requires PHP: 5.6
   @coypright: -2024 CodePopular (support: info@codepopular.com)
 */



// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  echo "can't access directly";
  exit();
}

if ( ! defined( 'ADE_PLUGIN_DIR_PATH' ) ) {
  define( 'ADE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );
}


if ( ! defined( 'ADE_PLUGIN_URL' ) ) {
  define( 'ADE_PLUGIN_URL', plugin_dir_url(__FILE__) );
}




// main plugin class
class CodePopular_disable_gutenberg
{
  static function init()
  {
    if (is_admin()) {
      add_filter('install_plugins_table_api_args_featured', array(__CLASS__, 'featured_plugins_tab'));
    }
      
  
  } // init

  
  // add our plugins to recommended list
  static function plugins_api_result($res, $action, $args) {
    remove_filter('plugins_api_result', array(__CLASS__, 'plugins_api_result'), 10, 1);

    $res = self::add_plugin_favs('wp-maximum-upload-file-size', $res);
    $res = self::add_plugin_favs('unlimited-theme-addons', $res);

    return $res;
  } // plugins_api_result
  
  
  // helper function for adding plugins to fav list
  static function featured_plugins_tab($args) {
    add_filter('plugins_api_result', array(__CLASS__, 'plugins_api_result'), 10, 3);

    return $args;
  } // featured_plugins_tab


  // add single plugin to list of favs
  static function add_plugin_favs($plugin_slug, $res) {
    if (!empty($res->plugins) && is_array($res->plugins)) {
      foreach ($res->plugins as $plugin) {
        if (is_object($plugin) && !empty($plugin->slug) && $plugin->slug == $plugin_slug) {
          return $res;
        }
      } // foreach
    }

    if ($plugin_info = get_transient('wf-plugin-info-' . $plugin_slug)) {
      array_unshift($res->plugins, $plugin_info);
    } else {
      $plugin_info = plugins_api('plugin_information', array(
        'slug'   => $plugin_slug,
        'is_ssl' => is_ssl(),
        'fields' => array(
            'banners'           => true,
            'reviews'           => true,
            'downloaded'        => true,
            'active_installs'   => true,
            'icons'             => true,
            'short_description' => true,
        )
      ));
      if (!is_wp_error($plugin_info)) {
        $res->plugins[] = $plugin_info;
        set_transient('wf-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7);
      }
    }

    return $res;
  } // add_plugin_favs
} // class Wp_auto_disable_gutenburg




// Disable Gutenberg

if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
  
  // WP > 5 beta
  add_filter('use_block_editor_for_post_type', '__return_false', 10);
  
} else {
  
  // WP < 5 beta
  add_filter('gutenberg_can_edit_post_type', '__return_false', 10);
  
}

add_action('init', array('CodePopular_disable_gutenberg', 'init'));


if (is_admin()) {
    require_once ADE_PLUGIN_DIR_PATH . 'Admin/admin.php';
  }


