<?php 

add_action('wp_footer', 'wec_footer');
function wec_footer(){
	global $current_user;
	$config = get_option('wec_options'); 
	
	
	if( !is_user_logged_in() ){
		return;
	}
	if( $config['is_active'] == 'no' ){
		return;
	}

	
	// checking loigns num	
	 $cur_count = (int)get_user_meta( $current_user->ID, 'cdp_login_num', true );
	//var_Dump( $cur_count );
	if( $cur_count >= (int)$config['each_x_logins']   ){
		$show_logins = 1;
		update_user_meta( $current_user->ID, 'cdp_login_num', 0 );
		update_option( 'cdp_need2show', 'yes' );
	}
	
	
	// checking days num	
	 $last_time = get_option('wec_time');
	
	 $diff = (int)( ( time() - $last_time ) / (60*60*24) );

	if( $diff >= (int)$config['each_x_days'] ){
		$show_days = 1;
		update_option( 'wec_time', time() );
		update_option( 'cdp_need2show', 'yes' );
	}


	if( get_option( 'cdp_need2show') == 'yes' ){
		echo '<div class="email_container tw-bs">
			Welcome back, is this still your email address?  '.esc_html( $current_user->user_email ).'
			<div class="control_block">
				<input type="button" value="Yes" class="btn btn-success yes_button" />
				<input type="button" value="No"   class="btn btn-danger no_button" />
			</div>
			<div class="email_block">
				<div class="email_bl_cont">
					<input type="text" placeholder="Your new email address" id="new_email" >
					<input type="button" value="Save" class="btn btn-warning save_button" />
					<input type="button" value="Cancel" class="btn btn-danger cancel_button" />
				</div>
			</div>
			<img class="ajax_loader" src="'.plugins_url( '/images/ajax-loader.gif', __FILE__ ).'" />
		</div>
		<style>
		'.$config['css'].'
		</style>';
		
		echo "
		<script>
		jQuery(document).ready(function($){
			function wcc_submit_email(){	

				var data = {
					s: jQuery('#search_input').val(),
					action: 'make_search_action',
					security: '".wp_create_nonce('search_security_nonce')."'
				};
					jQuery.ajax({url: '".admin_url( 'admin-ajax.php' )."',
						type: 'POST',
						data: data,            
						beforeSend: function(msg){
							jQuery('#search_input').css('background', 'url(".plugins_url( '/images/ajax-loader.gif', __FILE__ ).") no-repeat right center');					
							},
							success: function(msg){
								console.log( msg );
								jQuery('#search_input').css('background', 'none');
								jQuery('.search_result_container').html( msg );
							} , 
							error:  function(msg) {
											
							}          
					});
				}
				
			jQuery('.yes_button').click(function(){
				wcc_email_confirm();
			})
			jQuery('.no_button').click(function(){
				jQuery('.control_block').hide();
				jQuery('.email_bl_cont').fadeIn();
			})
			jQuery('.cancel_button').click(function(){
				jQuery('.control_block').fadeIn();
				jQuery('.email_bl_cont').hide();
			})			
				
			function wcc_email_confirm(){	

				var data = {
					action: 'email_confirm_action',
					security: '".wp_create_nonce('email_confirm_security_nonce')."'
				};
					jQuery.ajax({url: '".admin_url( 'admin-ajax.php' )."',
						type: 'POST',
						data: data,            
						beforeSend: function(msg){
							jQuery('.ajax_loader').fadeIn();					
							},
							success: function(msg){
								console.log( msg );
								jQuery('.ajax_loader').fadeOut();
								jQuery('.email_container').fadeOut();
							} , 
							error:  function(msg) {
											
							}          
					});
				}
				
				
				
				jQuery('.save_button').click(function(){
					wcc_email_save();
				})
				function wcc_email_save(){	

				
				if( !isValidEmail(jQuery('#new_email').val()) ){
					alert('Please enter valid email address');
					return;
				}
				
				var data = {
					email: jQuery('#new_email').val(),
					action: 'email_save_action',
					security: '".wp_create_nonce('email_save_security_nonce')."'
				};
					jQuery.ajax({url: '".admin_url( 'admin-ajax.php' )."',
						type: 'POST',
						data: data,            
						beforeSend: function(msg){
							jQuery('.ajax_loader').fadeIn();					
							},
							success: function(msg){
								console.log( msg );
								jQuery('.ajax_loader').fadeOut();
								jQuery('.email_container').fadeOut();
							} , 
							error:  function(msg) {
											
							}          
					});
				}";
				
		echo '
			function isValidEmail(email){
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					return re.test(email);
			}
		})
		</script>
		';
	}
}

add_action('wp_login', 'cdp_function', 10, 2);
function cdp_function( $user_login, $user ) {
    $cur_count = (int)get_user_meta( $user->data->ID, 'cdp_login_num', true );
	$cur_count++;
	update_user_meta( $user->data->ID, 'cdp_login_num', $cur_count );
}
	
?>