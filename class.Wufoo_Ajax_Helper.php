<?php

/**
 * Contains a class to define the Wufoo Ajax Plugin
 *
 * PHP Version 5.5+
 *
 * @category Wufoo Ajax_Helper
 * @package  Plugin
 * @author   Beau Watson <beau@beauwatson.com>
 * @license  Copyright 2015 Beau Watson. All rights reserved.
 * @link     http://docwatson.net
 */

// see https://github.com/wufoo/Wufoo-PHP-API-Wrapper
include 'vendor/WufooApiWrapper.php';

/**
 * A class to define the Wufoo Ajax Plugin
 *
 * @category Wufoo Ajax
 * @package  Plugin
 * @author   Beau Watson <beau@beauwatson.com>
 * @license  Copyright 2015 Beau Watson. All rights reserved.
 * @link     http://docwatson.net
 */
class Wufoo_Ajax_Helper {
  private $_api_key;
  private $_wufoo_id;
  private $_hashes;
  private $_hash_labels;
  private $_version = '1.0.1';


	/**
	 * Constructor function. Registers action and filter hooks.
	 *
	 * @access public
	 * @return Wufoo_Ajax_Helper
	 */
  	public function __construct() {
      //set up properties
      $this->_api_key  = get_option('wa_wufoo_api_key');
      $this->_wufoo_id = get_option('wa_wufoo_id');
      $this->_hashes   = unserialize(get_option('wa_wufoo_hashes'));
      $this->_hash_labels   = unserialize(get_option('wa_wufoo_hash_labels'));

  		/* Register action hooks. */
      add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ), 1000 );
      add_action( 'wp_ajax_wufoo_post', array( $this, 'action_wp_ajax_wufoo_post' ));
      add_action( 'wp_ajax_nopriv_wufoo_post', array( $this, 'action_wp_ajax_wufoo_post' ));

      //enable admin functions only if the user is an administrator
      if ( current_user_can('install_plugins') ) {
        add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );
        add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
      }
  	}


    /**
     * private function that loops through the serialized data from form and
     * sanitizes it for Wufoo Submission
     *
     * @access private
     * @param $arg - serialized string from POST request
     * @return array of form data
     */
    private function _wufoo_sanitize($arg) {
      $post_data  = urldecode($arg);
      $arr        = explode('&', $post_data);
      $new_arr    = array();
      $wufoo_arr  = array();

      foreach($arr as $val) {
        $val_arr              = explode('=', $val);
        $new_arr[$val_arr[0]] = $val_arr[1];
      }

      foreach ($new_arr as $key => $value) {
        array_push($wufoo_arr, new WufooSubmitField($key, $value));
      }

      return $wufoo_arr;
    }

    /**
     * Function to enqueue administration area scripts.
     *
     * @access public
     * @return void
     */
    public function action_admin_enqueue_scripts() {

      /* Add JavaScript. */
      wp_enqueue_script( 'wa_script_admin', plugins_url( 'js/wa_script_admin.js',  __FILE__ ), false, $this->_version );
    }

    /**
   * Function to hook menus into the WordPress admin panel.
	   *
	   * @access public
	   * @return void
	   */
	  public function action_admin_menu() {

	    /* Add main menu page. */
	    add_menu_page(
	      __( 'Wufoo Ajax Settings' ),
	      __( 'Wufoo Ajax' ),
	      'activate_plugins',
	      'wufoo-ajax-settings',
	      array( $this, 'admin_panel' )
	    );
    }

    /**
	 * Function to hook Wufoo Ajax Settings Page into Wordpress Admin Panel
	 *
	 * @access public
	 * @return void
	 */
  	public function admin_panel() {
      if ( !current_user_can('install_plugins') ) {
        die('You do not have the correct permissions to use this page.');
      }

  		/* Update options if necessary. */
  		if ( count( $_POST ) > 0 ) {
  			/* Update global options. */
  			update_option( 'wa_wufoo_api_key', $_POST['wa_wufoo_api_key'] );
  			update_option( 'wa_wufoo_id', $_POST['wa_wufoo_id'] );
        update_option( 'wa_wufoo_hashes', serialize(array_filter($_POST["wa_wufoo_hash"])));
        update_option( 'wa_wufoo_hash_labels', serialize(array_filter($_POST["wa_wufoo_hash_label"])));
  		}

      //set variables for the admin panel 
      $wa_wufoo_api_key = $this->_api_key;
      $wa_wufoo_id = $this->_wufoo_id;
      $wa_wufoo_hashes = $this->_hashes;
      $wa_wufoo_labels = $this->_hash_labels;

  		include 'views/admin-panel.php';
  	}

    /**
     * Action hook to process forms from AJAX request
     *
     * @access public
     * @return void
     */
    public function action_wp_ajax_wufoo_post() {
      //look up the index of the hash of the form we're processing
      $index = $this->get_hash_by_label($_POST["form_type"]);

      //sanitize the data using wufoo's helpers
      $data       = $this->_wufoo_sanitize($_POST['fields']);
      //set the hash
      $hash       = $this->_hashes[$index];
      //create an API wrapper object
      $api        = new WufooApiWrapper($this->_api_key, $this->_wufoo_id);
      //make the API call
      $response   = $api->entryPost($hash, $data);

      //record the response in JSON
      header('Content-Type: application/json');
      echo json_encode($response);
      wp_die();
    }

    /**
     * Action hook to add frontend javascript
     *
     * @access public
     * @return void
     */
    public function action_wp_enqueue_scripts() {
      wp_enqueue_script(
    		'wufoo-ajax-script',
        plugins_url( 'js/wa_script.js', __FILE__ ) ,
    		array( 'jquery' ),
        $this->_version,
        true
    	);
    }

    /*
    * Function to easily return an index of the hash associated with a label
    *
    * @param  {string} - $label
    *
    * @access public
    * @return {int} - $index
     */
    public function get_hash_by_label($label) {
      $index = array_search($label, $this->_hash_labels);

      return $index;
    }
}
