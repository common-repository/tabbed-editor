<?php

/**
 * The Tabbed Editor core plugin class
 */

 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_Tabbed_Editor' ) ) { 

  class PP_Tabbed_Editor {
    
    public $plugin_name;
    public $plugin_slug;
    public $version;
    private $wp_url;
    private $my_url;
    private $_file;
    private $min_wp_version = '4.0';
    private $admin_handle;
    
    
    /**
	   * here we go
     */
     public function __construct( $settings ) {
      
      $this->_file       = $settings['file'];
      $this->plugin_slug = $settings['slug'];
      $this->plugin_name = $settings['name'];
      $this->version     = $settings['version'];
      
      $this->init();
      
    }    
    
    
    /**
     * do plugin init 
     */
    private function init() {
      
      global $wp_version;
      
      $this->wp_url = 'https://wordpress.org/plugins/' . $this->plugin_slug;
      $this->my_url = 'https://petersplugins.com/free-wordpress-plugins/' . $this->plugin_slug;
      
      add_action( 'init', array( $this, 'add_text_domain' ) );
      
      if ( version_compare( $wp_version, $this->min_wp_version, '<' ) ) {
        
        // we need at least WP 4.0 to get this work...
        add_action( 'admin_init', array( $this, 'deactivate_plugin') );
        add_action( 'admin_notices', array( $this, 'plugin_deactivated') );
        
      } else {
        
        add_action( 'admin_head', array( $this, 'admin_css' ) );
        add_action( 'admin_menu', array( $this, 'adminmenu' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( $this->_file ), array( $this, 'add_links' ) ); 
        add_action( 'admin_enqueue_scripts', array( $this, 'do_it' ) );
        
      }
    }
    
    
    /**
     * add text domain
     */
    function add_text_domain() {  
    
      load_plugin_textdomain( 'tabbed-editor' );
      
    }
    
    
    /**
     * deactivate plugin if WP < 4.0
     */
    public function deactivate_plugin() {
      
      deactivate_plugins( plugin_basename( $this->_file ) );
      
    }
    
    
    /**
     * show message if WP < 4.0
     */
    public function plugin_deactivated() {
      
      echo '<div class="notice notice-error error"><p>' . __( 'The Tabbed Editor Plugin requires at least WordPress version 4.0 and was deactivated therefore.', 'tabbed-editor' ) . '</p></div>';
      
    }
    
    
    /**
     * add links to plugins table
     */
    function add_links( $links ) {
      
      return array_merge( $links, array( '<a href="' . menu_page_url( $this->plugin_slug, false ) . '" title="' . __( 'Show plugin info', 'tabbed-editor' ) . '">' . __( 'Show plugin info', 'tabbed-editor' ) . '</a>', '<a href="https://wordpress.org/support/plugin/' . $this->plugin_slug . '/reviews/" title="' . __( 'Please rate plugin', 'tabbed-editor' ) . '">' . __( 'Please rate plugin', 'tabbed-editor' ) . '</a>' ) );
      
    }
    
    
   /**
     * init backend
     */
    function adminmenu() {
      
      $this->admin_handle = add_submenu_page( null, $this->plugin_name, $this->plugin_name, 'read', $this->plugin_slug, array( $this, 'showadmin' ) );
      
    }
    
    
    /**
     * show admin page
     */
    function showadmin() {
      ?>
      <div class="wrap tabbed-editor">
        
        <h1>
          <span><?php echo $this->plugin_name; ?></span>
          <nav><a class="dashicons dashicons-star-filled" href="https://wordpress.org/support/plugin/<?php echo $this->plugin_slug; ?>/reviews/" title="<?php _e( 'Please rate plugin', 'tabbed-editor' ); ?>"></a><a class="dashicons dashicons-wordpress" href="<?php echo $this->wp_url; ?>/" title="<?php _e( 'wordpress.org plugin directory', 'tabbed-editor' ); ?>"></a><a class="dashicons dashicons-editor-help" href="https://wordpress.org/support/plugin/<?php echo $this->plugin_slug; ?>/" title="<?php _e( 'Support', 'tabbed-editor'); ?>"></a><a class="dashicons dashicons-admin-home" href="https://petersplugins.com/" title="<?php _e( 'Author Website', 'tabbed-editor' );?>"></a><a class="dashicons dashicons-googleplus" href="https://plus.google.com/+petersplugins" title="<?php _e( 'Authors Google+ Page', 'tabbed-editor' ); ?>"></a><a class="dashicons dashicons-facebook-alt" href="https://www.facebook.com/petersplugins/" title="<?php _e( 'Authors Facebook Page', 'tabbed-editor' ); ?>"></a></span>
        </h1>

        <p><?php _e( 'This plugin changes the post editor into a tabbed interface.', 'tabbed-editor' ); ?></p>
        <p><?php _e( 'There are no settings.', 'tabbed-editor' ); ?></p>
        <p><?php _e( 'Disable the plugin to return to the default editor interface.', 'tabbed-editor' ); ?></p>
  
      </div>
      <?php
    }
    
    
    /**
     * add admin css
     */
function admin_css() {
      if ( get_current_screen()->id == $this->admin_handle ) {
        
        ?>
        <style type="text/css">
          .tabbed-editor h1 span {
            display: block;
            line-height: 48px; 
            padding-left: 60px; 
            background-image: url(<?php echo plugins_url( 'assets/pluginicon.png', $this->_file ); ?>);
            background-repeat: no-repeat; 
            background-color: #4B6F87;
            color: #fff;
          }
          .tabbed-editor h1 nav {
            background-color: #fff;
            text-align: right;
            line-height: 1;
          }
          .tabbed-editor h1 nav a {
            display: inline-block;
            padding: 6px;
            color: #4B6F87;
          }
          .tabbed-editor h1 nav a:hover, .tabbed-editor h1 nav a:focus {
            color: #104060;
          }
          
        </style>
        <?php
        
      }
    }
    
    
    /**
	   * change editor interface
     */
    function do_it( $hook ) {
      if ( 'post.php'  == $hook ) {
        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script( 'pp_tabbed_editor', plugins_url( 'assets/js/tabbed-editor.js', $this->_file ), array( 'jquery' ) );
        wp_enqueue_style( 'pp_tabbed_editor', plugins_url( 'assets/css/tabbed-editor.css', $this->_file ) );
      }
    }
    
  }
}