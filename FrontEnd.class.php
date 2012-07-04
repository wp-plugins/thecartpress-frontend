<?php
/*
Plugin Name: TheCartPress Front End
Plugin URI: http://wordpress.org/extend/plugins/thecartpress-frontend/
Description: Allows to set some admin panels in the front end
Version: 1.0.4
Author: TheCartPress team
Author URI: http://thecartpress.com
License: GPL
Parent: thecartpress
*/

/**
 * This file is part of TheCartPress-Front-end.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class TCPFrontEnd {
	function activate_plugin() {
		//Page My Account
		$my_account_page_id = get_option( 'tcp_my_account_page_id' );
		if ( ! $my_account_page_id || ! get_page( $my_account_page_id ) ) {
			$my_account_page_id = $this->create_my_account_page();
		} else {
			wp_publish_post( $my_account_page_id );
		}
		//Page My Addresses
		$addresses_list_page_id = get_option( 'tcp_addresses_list_page_id' );
		if ( ! $addresses_list_page_id || ! get_page( $addresses_list_page_id ) ) {
			$addresses_list_page_id = $this->create_addresses_list_page( $my_account_page_id );
		} else {
			wp_publish_post( $addresses_list_page_id );
		}
		//Page Address Edit
		$address_edit_page_id = get_option( 'tcp_address_edit_page_id' );
		if ( ! $address_edit_page_id || ! get_page( $address_edit_page_id ) ) {
			$address_edit_page_id = $this->create_address_edit_page( $addresses_list_page_id );
		} else {
			wp_publish_post( $address_edit_page_id );
		}		
		//Page My Downloads
		$downloadable_list_page_id = get_option( 'tcp_downloadable_list_page_id' );
		if ( ! $downloadable_list_page_id || ! get_page( $downloadable_list_page_id ) ) {
			$this->create_downloadable_list_page( $my_account_page_id );
		} else {
			wp_publish_post( $downloadable_list_page_id );
		}
		//Page My Orders
		$orders_list_page_id = get_option( 'tcp_orders_list_page_id' );
		if ( ! $orders_list_page_id || ! get_page( $orders_list_page_id ) ) {
			$this->create_orders_list_page( $my_account_page_id );
		} else {
			wp_publish_post( $orders_list_page_id );
		}
	}

	function create_my_account_page() {
		$page = array(
			'comment_status'	=> 'closed',
			'post_content'		=> 'My Account',
			'post_content'		=> '[tcp_my_account]',
			'post_status'		=> 'publish',
			'post_title'		=> __( 'My Account','tcp-fe' ),
			'post_type'			=> 'page',
		);
		$my_account_page_id = wp_insert_post( $page );
		update_option( 'tcp_my_account_page_id', $my_account_page_id );
		return $my_account_page_id;
	}

	function create_addresses_list_page( $my_account_page_id ) {
		$page = array(
			'comment_status'	=> 'closed',
			'post_content'		=> '[tcp_addresses_list]',
			'post_status'		=> 'publish',
			'post_title'		=> __( 'My Addresses','tcp-fe' ),
			'post_type'			=> 'page',
			'post_parent'		=> $my_account_page_id,
		);
		$addresses_list_page_id = wp_insert_post( $page );
		update_option( 'tcp_addresses_list_page_id', $addresses_list_page_id );
		return $addresses_list_page_id;
	}

	function create_address_edit_page( $addresses_list_page_id ) {
		$page = array(
			'comment_status'	=> 'closed',
			'post_content'		=> '[tcp_address_edit]',
			'post_status'		=> 'publish',
			'post_title'		=> __( 'Address','tcp-fe' ),
			'post_type'			=> 'page',
			'post_parent'		=> $addresses_list_page_id,
		);
		$address_edit_page_id = wp_insert_post( $page );
		update_option( 'tcp_address_edit_page_id', $address_edit_page_id );
		return $address_edit_page_id;
	}

	function create_downloadable_list_page( $my_account_page_id ) {
		$page = array(
			'comment_status'	=> 'closed',
			'post_content'		=> '[tcp_downloadable_list]',
			'post_status'		=> 'publish',
			'post_title'		=> __( 'My Downloads','tcp-fe' ),
			'post_type'			=> 'page',
			'post_parent'		=> $my_account_page_id,
		);
		$downloadable_page_id = wp_insert_post( $page );
		update_option( 'tcp_downloadable_list_page_id', $downloadable_page_id );
		return $downloadable_page_id;
	}

	function create_orders_list_page( $my_account_page_id ) {
		$page = array(
			'comment_status'	=> 'closed',
			'post_content'		=> '[tcp_orders_list]',
			'post_status'		=> 'publish',
			'post_title'		=> __( 'My Orders','tcp-fe' ),
			'post_type'			=> 'page',
			'post_parent'		=> $my_account_page_id,
		);
		$orders_page_id = wp_insert_post( $page );
		update_option( 'tcp_orders_list_page_id', $orders_page_id );
		return $orders_page_id;
	}

	function tcp_check_the_plugin( $warnings ) {
		$page_id = get_option( 'tcp_my_account_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) )
			$warnings[] = __( '<strong>My Account page</strong> has been deleted.', 'tcp-fe' );
		$page_id = get_option( 'tcp_addresses_list_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) )
			$warnings[] = __( '<strong>Addresses list page</strong> has been deleted.', 'tcp-fe' );
		$page_id = get_option( 'tcp_address_edit_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) )
			$warnings[] = __( '<strong>Address Edit page</strong> has been deleted.', 'tcp-fe' );
		global $thecartpress;
		$hide_downloadable_menu = isset( $thecartpress->settings['hide_downloadable_menu'] ) ? $thecartpress->settings['hide_downloadable_menu'] : false;
		if ( ! $hide_downloadable_menu ) {
			$page_id = get_option( 'tcp_downloadable_list_page_id' );
			if ( ! $page_id || ! get_page( $page_id ) )
				$warnings[] = __( '<strong>Downloadables list page</strong> has been deleted.', 'tcp-fe' );
		}
		$page_id = get_option( 'tcp_orders_list_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) )
			$warnings[] = __( '<strong>Orders list page</strong> has been deleted.', 'tcp-fe' );
		return $warnings;
	}

	function tcp_checking_pages( $warnings_msg, $shopping_cart_page_id ) {
		$my_account_page_id = get_option( 'tcp_my_account_page_id' );
		if ( ! $my_account_page_id || ! get_page( $my_account_page_id ) ) {
			$my_account_page_id = $this->create_my_account_page();
			$warnings_msg[] = __( 'My Account page has been created', 'tcp-fe' );
		}
		$page_id = get_option( 'tcp_addresses_list_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) ) {
			$addresses_list_page_id = $this->create_addresses_list_page( $my_account_page_id );
			$warnings_msg[] = __( 'The Addresses list page has been created', 'tcp-fe' );
		}
		
		$page_id = get_option( 'tcp_address_edit_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) ) {
			if ( ! isset( $addresses_list_page_id ) ) $addresses_list_page_id = get_option( 'tcp_addresses_list_page_id' );
			$this->create_address_edit_page( $addresses_list_page_id );
			$warnings_msg[] = __( 'The Address edit page has been created', 'tcp-fe' );
		}
		
		global $thecartpress;
		if ( $thecartpress ) {
			$hide_downloadable_menu = isset( $thecartpress->settings['hide_downloadable_menu'] ) ? $thecartpress->settings['hide_downloadable_menu'] : false;
			if ( ! $hide_downloadable_menu ) {
				$page_id = get_option( 'tcp_downloadable_list_page_id' );
				if ( ! $page_id || ! get_page( $page_id ) ) {
					$this->create_downloadable_list_page( $my_account_page_id );
					$warnings_msg[] = __( 'The Downloadables list page has been created', 'tcp-fe' );
				}
			}
		}
		$page_id = get_option( 'tcp_orders_list_page_id' );
		if ( ! $page_id || ! get_page( $page_id ) ) {
			$this->create_orders_list_page( $my_account_page_id );
			$warnings_msg[] = __( 'The orders list page has been created', 'tcp-fe' );
		}
		return $warnings_msg;
	}

	function tcp_my_account() {
		tcp_login_form( array( 'echo' => true ) );
	}

	function tcp_addresses_list() {
		require_once( ABSPATH . 'wp-content/plugins/thecartpress/admin/AddressesList.class.php' );
		$addresses_list = new TCPAddressesList();
		return $addresses_list->show( false );
	}

	function tcp_address_edit() {
		require_once( ABSPATH . 'wp-content/plugins/thecartpress/admin/AddressEdit.class.php' );
		$address_edit = new TCPAddressEdit();
		return $address_edit->show( false );
	}

	function tcp_downloadable_list() {
		require_once( ABSPATH . 'wp-content/plugins/thecartpress/admin/DownloadableList.class.php' );
		$downloadable_list = new TCPDownloadableList();
		return $downloadable_list->show( false );
	}

	function tcp_orders_list() {
		require_once( dirname( __FILE__ ) . '/admin/OrdersList.class.php' );
		$orders_list = new TCPOrdersList();
		return $orders_list->show( false );
	}	

	function init() {
		if ( function_exists( 'load_plugin_textdomain' ) ) load_plugin_textdomain( 'tcp-fe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		if ( ! is_admin() ) wp_enqueue_style( 'tcp_frontend_style', plugins_url( 'thecartpress-frontend/css/frontend.css' ) );
	}

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		if ( is_admin() ) {
			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
			add_filter( 'tcp_check_the_plugin', array( $this, 'tcp_check_the_plugin' ) );
			add_filter( 'tcp_checking_pages', array( $this, 'tcp_checking_pages' ), 10, 2 );
		} else {
			add_shortcode( 'tcp_my_account', array( $this, 'tcp_my_account' ) );
			add_shortcode( 'tcp_addresses_list', array( $this, 'tcp_addresses_list' ) );
			add_shortcode( 'tcp_address_edit', array( $this, 'tcp_address_edit' ) );
			add_shortcode( 'tcp_downloadable_list', array( $this, 'tcp_downloadable_list' ) );
			add_shortcode( 'tcp_orders_list', array( $this, 'tcp_orders_list' ) );
		}
	}
}

new TCPFrontEnd();
?>
