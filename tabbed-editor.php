<?php

/**
 * The Tabbed Editor Plugin
 *
 * Tabbed Editor changes the editor screen into a tabbed interface for copywriters
 *
 * @wordpress-plugin
 * Plugin Name: Tabbed Editor
 * Plugin URI: https://petersplugins.com/free-wordpress-plugins/tabbed-editor
 * Description: Changes the editor screen into a tabbed interface for copywriters
 * Version: 2
 * Author: Peter Raschendorfer
 * Author URI: https://petersplugins.com
 * Text Domain: tabbed-editor
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load core plugin class and run the plugin
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-tabbed-editor.php' );
$pp_tabbed_editor = new PP_Tabbed_Editor(array(
  'file'    => __FILE__,
  'slug'    => pathinfo( __FILE__, PATHINFO_FILENAME ),
  'name'    => 'Tabbed Editor',
  'version' => '2'
  ) 
);

?>