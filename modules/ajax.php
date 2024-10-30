<?php 

add_action('wp_ajax_email_confirm_action', 'cdp_email_confirm_action');
add_action('wp_ajax_nopriv_email_confirm_action', 'cdp_email_confirm_action');

function cdp_email_confirm_action(){
	global $current_user;
	if( check_ajax_referer( 'email_confirm_security_nonce', 'security') ){
		update_option( 'cdp_need2show', 'no' );
	}
	die();
}

add_action('wp_ajax_email_save_action', 'cdp_email_save_action');
add_action('wp_ajax_nopriv_email_save_action', 'cdp_email_save_action');

function cdp_email_save_action(){
	global $current_user;
	if( check_ajax_referer( 'email_save_security_nonce', 'security') ){
		$user_email = sanitize_email( $_POST['email'] );
		$user_id = wp_update_user( array( 'ID' => $current_user->ID, 'user_email' => $user_email ) );
		update_option( 'cdp_need2show', 'no' );
		if ( is_wp_error( $user_id ) ) {
			echo 'error';
		} else {
			echo 'success';
		}
		
	}
	die();
}

?>