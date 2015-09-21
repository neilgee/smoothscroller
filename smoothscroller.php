<?php     namespace ng_smoothscroller;

/*
Plugin Name: Smoothscroller
Plugin URI: http://wpbeaches.com/
Description: Smooth Scroll to internal links in WordPress
Author: Neil Gee
Version: 1.0.0
Author URI: http://wpbeaches.com
Text Domain: smoothscroller
Domain Path: /languages/
@package    Smoothscroller
@author     Neil Gee
@since      1.0.0
@license    GPL-2.0+
*/


// If called direct, refuse
  if ( ! defined( 'ABSPATH' ) ) {
          die;
  }

/* Assign global variables */

$plugin_url = WP_PLUGIN_URL . '/smoothscroller';
$options = array();

/**
 * Register our text domain.
 *
 * @since 1.0.0
 */

function load_textdomain() {
  load_plugin_textdomain( 'smoothscroller', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );

/**
 * Register and Enqueue Scripts and Styles
 *
 * @since 1.0.0
 */

//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued
function scripts_styles() {
  $options = get_option( 'smoothscroller_settings' );
//if( !isset( $options['ss_all_pages'] ) ) $options['ss_all_pages'] = 0;
  if( isset($options['ss_all_pages'] )) {

	wp_register_script( 'scrollto', '//cdn.jsdelivr.net/jquery.scrollto/2.1.1/jquery.scrollTo.min.js', array( 'jquery' ), '2.1.1', true );
	wp_register_script( 'localscroll', '//cdn.jsdelivr.net/jquery.localscroll/1.4.0/jquery.localScroll.min.js', array( 'scrollto' ), '1.4.0', true );
	wp_register_script( 'localscroll-init', plugins_url( '/js/localscroll-init.js',  __FILE__ ), array( 'localscroll' ), '1', true );

	wp_enqueue_script( 'scrollto' );
	wp_enqueue_script( 'localscroll' );

     $data = array (

      'ss_smooth' => array(
          
          'ss_smoothscroll_speed'  => (int)$options['ss_speed_duration'], // this is an integer

      ),
  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'localscroll-init', 'scrollVars', $data );

    wp_enqueue_script( 'localscroll-init' );
  }


   elseif( !isset($options['ss_all_pages'] )) {
     $page_ids = explode( ',', $options['ss_some_pages'] );
     $post_ids = explode( ',', $options['ss_some_posts'] );

      if( is_page( $page_ids ) ||  is_single( $post_ids ) ) {
  wp_register_script( 'scrollto', '//cdn.jsdelivr.net/jquery.scrollto/2.1.0/jquery.scrollTo.min.js', array( 'jquery' ), '2.1.0', true );
  wp_register_script( 'localscroll', '//cdn.jsdelivr.net/jquery.localscroll/1.4.0/jquery.localScroll.min.js', array( 'scrollto' ), '1.4.0', true );
  wp_register_script( 'localscroll-init', plugins_url( '/js/localscroll-init.js',  __FILE__ ), array( 'localscroll' ), '1', true );

  wp_enqueue_script( 'scrollto' );
  wp_enqueue_script( 'localscroll' );

     $data = array (

      'ss_smooth' => array(
          
          'ss_smoothscroll_speed'  => (int)$options['ss_speed_duration'], // this is an integer

      ),

  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'localscroll-init', 'scrollVars', $data );

    wp_enqueue_script( 'localscroll-init' );
    }
  }
  //endif;
     if( isset($options['ss_front_page'] )) {
      if( is_home() || is_front_page() ) {
  wp_register_script( 'scrollto', '//cdn.jsdelivr.net/jquery.scrollto/2.1.0/jquery.scrollTo.min.js', array( 'jquery' ), '2.1.0', true );
  wp_register_script( 'localscroll', '//cdn.jsdelivr.net/jquery.localscroll/1.4.0/jquery.localScroll.min.js', array( 'scrollto' ), '1.4.0', true );
  wp_register_script( 'localscroll-init', plugins_url( '/js/localscroll-init.js',  __FILE__ ), array( 'localscroll' ), '1', true );

  wp_enqueue_script( 'scrollto' );
  wp_enqueue_script( 'localscroll' );

     $data = array (

      'ss_smooth' => array(
          
          'ss_smoothscroll_speed'  => (int)$options['ss_speed_duration'], // this is an integer

      ),
  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'localscroll-init', 'scrollVars', $data );

    wp_enqueue_script( 'localscroll-init' );
    }
  }
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_styles' );

/**
 * Register our option fields
 *
 * @since 1.0.0
 */

function plugin_settings(){
  register_Setting(
        'ss_settings-group', //option name
        'smoothscroller_settings',// option group setting name and option name
        __NAMESPACE__ . '\\smoothscroller_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'ss_smoothscroller_section', //declare the section id
        'Smoothscroller Settings', //page title
         __NAMESPACE__ . '\\ss_smoothscroller_section_callback', //callback function below
        'smoothscroller' //page that it appears on

    );
  add_settings_field(
        'ss_all_pages', //unique id of field
        'Apply to all Posts/Pages', //title
         __NAMESPACE__ . '\\ss_all_pages_callback', //callback function below
        'smoothscroller', //page that it appears on
        'ss_smoothscroller_section' //settings section declared in add_settings_section
    );
  add_settings_field(
        'ss_front_page', //unique id of field
        'Apply to Front/Home Page', //title
         __NAMESPACE__ . '\\ss_front_page_callback', //callback function below
        'smoothscroller', //page that it appears on
        'ss_smoothscroller_section' //settings section declared in add_settings_section
    );
  add_settings_field(
        'ss_some_pages', //unique id of field
        'Apply to some Pages', //title
         __NAMESPACE__ . '\\ss_some_pages_callback', //callback function below
        'smoothscroller', //page that it appears on
        'ss_smoothscroller_section' //settings section declared in add_settings_section
    );
  add_settings_field(
        'ss_some_posts', //unique id of field
        'Apply to some Posts', //title
         __NAMESPACE__ . '\\ss_some_posts_callback', //callback function below
        'smoothscroller', //page that it appears on
        'ss_smoothscroller_section' //settings section declared in add_settings_section
    );
  add_settings_field(
        'ss_speed_duration', //unique id of field
        'Speed of Scroll', //title
         __NAMESPACE__ . '\\ss_smoothscroller_speed_callback', //callback function below
        'smoothscroller', //page that it appears on
        'ss_smoothscroller_section' //settings section declared in add_settings_section
    );
}
add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');

/**
 * Sanitize our inputs
 *
 * @since 1.0.0
 */

function smoothscroller_validate_input( $input ) {
  // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } // end if
         
    } // end foreach
     
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'smoothscroller_validate_input' , $output, $input );
}

function ss_smoothscroller_section_callback() {

}

/**
 * Register our Speed Duration callback
 *
 * @since 1.0.0
 */

function ss_smoothscroller_speed_callback(){
$options = get_option( 'smoothscroller_settings' );  

  if( !isset( $options['ss_speed_duration'] ) ) $options['ss_speed_duration'] = 200;
  
  ?>
 
  <select name="smoothscroller_settings[ss_speed_duration]" id="ss_speed_duration">
    <option value="200" <?php selected($options['ss_speed_duration'], '200'); ?>>200</option>
    <option value="400" <?php selected($options['ss_speed_duration'], '400'); ?>>400</option>
    <option value="600" <?php selected($options['ss_speed_duration'], '600'); ?>>600</option>
    <option value="800" <?php selected($options['ss_speed_duration'], '800'); ?>>800</option>
    <option value="1000" <?php selected($options['ss_speed_duration'], '1000'); ?>>1000</option>
    <option value="2000" <?php selected($options['ss_speed_duration'], '2000'); ?>>2000</option>
  </select>
   <label for="ss_speed_duration"><?php esc_attr_e( 'Speed of scroll (Lower numbers are faster)', 'smoothscroller' ); ?></label>
  <?php
}

/**
 * Register All Pages have scroll option
 *
 * @since 1.0.0
 */

function ss_all_pages_callback() {
$options = get_option( 'smoothscroller_settings' ); 

if( !isset( $options['ss_all_pages'] ) ) $options['ss_all_pages'] = 0;

  echo'<input type="checkbox" id="ss_all_pages" name="smoothscroller_settings[ss_all_pages]" value="1"' . checked( 1, $options['ss_all_pages'], false ) . '/>';
  echo'<label for="ss_all_pages">' . esc_attr_e( 'Check to enable Smoothscroller on all posts/pages','smoothscroller') . '</label>';
} 

/**
 * Register Front Page has scroll option
 *
 * @since 1.0.0
 */

function ss_front_page_callback() {
$options = get_option( 'smoothscroller_settings' ); 

if( !isset( $options['ss_front_page'] ) ) $options['ss_front_page'] = 0;

  echo'<input type="checkbox" id="ss_front_page" name="smoothscroller_settings[ss_front_page]" value="1"' . checked( 1, $options['ss_front_page'], false ) . '/>';
  echo'<label for="ss_front_page">' . esc_attr_e( 'Check to enable Smoothscroller on Home/Front page','smoothscroller') . '</label>';
} 

/**
 * Register Some Pages have scroll option
 *
 * @since 1.0.0
 */

function ss_some_pages_callback() {
$options = get_option( 'smoothscroller_settings' ); 

if( !isset( $options['ss_some_pages'] ) ) $options['ss_some_pages'] = '';


echo '<input type="text" id="ss_some_pages" name="smoothscroller_settings[ss_some_pages]" value="' . sanitize_text_field($options['ss_some_pages']) . '" placeholder="add page IDs comma separated">';
echo '<label for="ss_some_pages">' . esc_attr_e( 'Comma Separate the ID values','smoothscroller') . '</label>';
}

/**
 * Register Some Posts have scroll option
 *
 * @since 1.0.0
 */

function ss_some_posts_callback() {
$options = get_option( 'smoothscroller_settings' ); 

if( !isset( $options['ss_some_posts'] ) ) $options['ss_some_posts'] = '';


echo '<input type="text" id="ss_some_posts" name="smoothscroller_settings[ss_some_posts]" value="' . sanitize_text_field($options['ss_some_posts']) . '" placeholder="add post IDs comma separated">';
echo '<label for="ss_some_posts">' . esc_attr_e( ' Comma Separate the ID values','smoothscroller') . '</label>';
}

/**
 * Create the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_page() {

    /*
     * Use the add options_page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
     */

     add_options_page(
        __( 'Smoothscroller Options Plugin','smoothscroller' ), //$page_title
        __( 'Smoothscroller', 'smoothscroller' ), //$menu_title
        'manage_options', //$capability
        'smoothscroller', //$menu-slug
        __NAMESPACE__ . '\\plugin_options_page' //$function
      );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );

/**
 * Include the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}